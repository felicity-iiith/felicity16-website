<?php

class auth extends Controller {

    function __construct() {
        $this->load_library("auth_lib");
        $this->load_library("http_lib", "http");
        $this->load_library("session_lib");

        $this->load_model("auth_model");
    }

    function index() {
        $this->http->redirect(locale_base_url() . "auth/login/");
    }

    private function extract_user_info_oauth($id, $attributes) {
        $user_data = [];
        unset($attributes["access_token"]);
        $user_data["raw_attributes"] = json_encode($attributes);
        if (strpos($id, "GitHubProfile") === 0) {
            if (!empty($attributes["email"])) {
                $user_data["mail"] = $attributes["email"];
                $user_data["email_verified"] = "1";
            }
            if (!empty($attributes["name"])) {
                $user_data["name"] = $attributes["name"];
            }
            if (!empty($attributes["login"])) {
                $user_data["nick"] = $attributes["login"];
            }
        } elseif(strpos($id, "Google2Profile") === 0) {
            if (!empty($attributes["emails"])) {
                $mails = json_decode($attributes["emails"], true);
                if (count($mails) && !empty($mails[0]["value"])) {
                    $user_data["mail"] = $mails[0]["value"];
                    $user_data["email_verified"] = "1";
                }
            }
            if (!empty($attributes["displayName"])) {
                $user_data["name"] = $attributes["displayName"];
            }
            if (!empty($attributes["gender"])) {
                if ($attributes["gender"] == "MALE") {
                    $user_data["gender"] = "male";
                } elseif ($attributes["gender"] == "FEMALE") {
                    $user_data["gender"] = "female";
                } else {
                    $user_data["gender"] = "other";
                }
            }
        } elseif(strpos($id, "FacebookProfile") === 0) {
            if (!empty($attributes["email"])) {
                $user_data["mail"] = $attributes["email"];
                $user_data["email_verified"] = "1";
            }
            if (!empty($attributes["name"])) {
                $user_data["name"] = $attributes["name"];
            }
            if (!empty($attributes["gender"])) {
                if ($attributes["gender"] == "MALE") {
                    $user_data["gender"] = "male";
                } elseif ($attributes["gender"] == "FEMALE") {
                    $user_data["gender"] = "female";
                } else {
                    $user_data["gender"] = "other";
                }
            }
        } else {
            if (!empty($attributes["email"])) {
                $user_data["mail"] = $attributes["email"];
                $user_data["email_verified"] = "1";
                $user_data["resitration_status"] = "incomplete";
            }
            if (isset($attributes["firstname"]) && isset($attributes["lastname"])) {
                $user_data["name"] = $attributes["firstname"] . " " . $attributes["lastname"];
            }
            if (!empty($attributes["nickname"])) {
                $user_data["nick"] = $attributes["nickname"];
            }
            if (!empty($attributes["gender"])) {
                if ($attributes["gender"] == "Male") {
                    $user_data["gender"] = "male";
                } elseif ($attributes["gender"] == "Female") {
                    $user_data["gender"] = "female";
                } else {
                    $user_data["gender"] = "other";
                }
            }
            if (!empty($attributes["location"])) {
                $user_data["location"] = $attributes["location"];
            }
            if (!empty($attributes["country"])) {
                $user_data["country"] = $attributes["country"];
            }
            if (!empty($attributes["birthdate"])) {
                $date = date_create_from_format('d/m/Y', $attributes["birthdate"]);
                if ($date) {
                    $user_data["dob"] = date_format($date, "Y-m-d");
                }
            }
            if (!empty($attributes["organization"])) {
                $user_data["organization"] = $attributes["organization"];
            }
        }

        return $user_data;
    }

    public function verify($hash=null) {
        if (!$hash) {
            $this->http->response_code(400);
        }
        $verified = $this->auth_model->verify_mail($hash, false);
        if (!$verified) {
            $this->http->response_code(400);
        }

        $email = $verified["email"];
        $action = $verified["action"];

        if ($action == "verify_email") {
            $success = false;
            $user = $this->auth_model->get_user_by_mail($email);
            if ($user) {
                $updated = $this->auth_model->update_user($user["id"], [
                    "email_verified" => "1"
                ]);
                if ($updated) {
                    $success = true;
                }
            }
            if (!$success) {
                $this->session_lib->flash_set("auth_last_error", __("Could not verify email"));
            } else {
                $this->auth_model->remove_verify_hash($hash);
            }
            $this->load_view('auth/email_confirmed', [
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        } elseif ($action == "reset_password") {
            $this->load_view("auth/password_reset", [
                "success" => false,
                "hash" => $hash,
                "error" => "",
                "action" => "reset_password",
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        } elseif ($action == "create_user") {
            $this->load_view("auth/password_reset", [
                "success" => false,
                "hash" => $hash,
                "error" => "",
                "action" => "create_user",
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        }
    }

    public function resend_mail() {
        $user = $this->auth_lib->get_user_details();

        if ($user !== false) {
            $email = $user["mail"];
        } else{
            if (!empty($_POST["mail"])) {
                $email = $_POST["mail"];
            } else {
                $this->http->response_code(400);
            }
        }

        $already_sent = $this->auth_model->get_mail_verification($email);
        if ($already_sent) {
            $action = $already_sent["action"];
        } else {
            $this->http->response_code(400);
        }

        $sent = $this->send_verification_mail($email, $action);

        $this->http->redirect(locale_base_url() . "auth/register");
    }

    private function send_verification_mail($email, $action) {
        if (!in_array($action, ["reset_password", "verify_email", "create_user"])) {
            // Something wrong, go away
            return false;
        }

        $already_sent = $this->auth_model->get_mail_verification($email);

        if ($already_sent) {
            $hash = $already_sent["hash"];
            $action = $already_sent["action"];
        } else {
            $hash = sha1(rand(169, 93367) . $email . $action . strrev($email)[0] . rand(109, 93267));
            $success = $this->auth_model->set_mail_verification($email, $hash, $action);

            if (!$success) {
                return false;
            }
        }

        $verify_link = base_url() . "auth/verify/$hash";

        $mail_data = [
            "verify_link" => $verify_link
        ];
        if ($action == "reset_password") {
            $subject = "Password reset link - Felicity '16";
        } elseif ($action == "verify_email") {
            $subject = "Please verify your email - Felicity '16";
        } elseif ($action == "create_user") {
            $subject = "Please verify your email - Felicity '16";
        }

        $this->load_library("email_lib");
        $mail = $this->email_lib->compose_mail("noreply");
        $this->email_lib->set_html_view($mail, "auth/mail/html_$action", $mail_data);
        $this->email_lib->set_text_view($mail, "auth/mail/text_$action", $mail_data);
        return $this->email_lib->send_mail($mail, [
            "to_email"  => $email,
            "subject"   => $subject
        ]);
    }

    private function handle_user_update($action, $user) {
        if ($user === false) {
            return;
        }

        if ($action == "update_mail" && isset($_POST["mail"])) {
            $mail = $_POST["mail"];
            $error = [];

            if (!$mail) {
                // TODO: email verification
                $error[] = __("Please enter a valid email address");
            } elseif ($mail != $user["mail"]) {
                if (!$this->auth_model->is_good_email($mail)) {
                    $error[] = __("The email id you gave is not valid");
                } else {
                    $already_registered = $this->auth_model->get_user_by_mail($mail);
                    if ($already_registered && $already_registered["id"] != $user["id"]) {
                        $error[] = __("The email id you gave is already registered");
                    } else {
                        $updated = $this->auth_model->update_user($user["id"], [
                            "mail" => $mail,
                            "email_verified" => "0",
                            "resitration_status" => "incomplete"
                        ]);
                        if ($updated) {
                            $sent = $this->send_verification_mail($mail, "verify_email");
                            if (!$sent) {
                                $error[] = __("Could not send mail");
                            }
                        }
                        if (!$updated) {
                            $error[] = __("Could not update email");
                        }
                    }
                }
            } else {
                $updated = $this->auth_model->update_user($user["id"], [
                    "resitration_status" => "incomplete"
                ]);
                if (!$updated) {
                    $error[] = __("Could not update email");
                }
            }

            $this->session_lib->flash_set("auth_last_error", implode("\n", $error));

            $this->http->redirect(locale_base_url() . "auth/register/");
        } elseif ($action == "update_profile") {
            if ($user["resitration_status"] == "complete") {
                return;
            }

            $user_data = [
                "nick" => strtolower($_POST["nick"]),
                "name" => $_POST["name"],
                "gender" => $_POST["gender"],
                "location" => $_POST["location"],
                "country" => $_POST["country"],
                "dob" => $_POST["dob"],
                "organization" => $_POST["organization"],
            ];

            $complete = true;
            $error = [];
            foreach ($user_data as $key => $value) {
                if (empty($value)) {
                    $error[] = __("Please fill all the required fields");
                    $complete = false;
                }
            }

            if (!preg_match('/^[a-z0-9_]+$/i', $user_data["nick"])) {
                unset($user_data["nick"]);
                $complete = false;
                $error[] = __("You can use only alphanumeric characters and underscore in nick");
            } elseif(strlen($user_data["nick"]) < 6) {
                unset($user_data["nick"]);
                $complete = false;
                $error[] = __("Nick must be at least 6 characters long");
            } elseif ($user["nick"] != $user_data["nick"]
                && $this->auth_model->get_user_by_nick($user_data["nick"])
            ) {
                unset($user_data["nick"]);
                $complete = false;
                $error[] = __("Nick is already taken");
            }

            if (!in_array($user_data["gender"], ["male", "female", "other"])) {
                $user_data["gender"] = "";
                $error[] = __("Enter valid gender");
                $complete = false;
            }

            if ($user_data["dob"]) {
                if (date_parse($user_data["dob"])["error_count"]) {
                    $user_data["dob"] = "";
                    $error[] = __("Enter valid birthdate");
                    $complete = false;
                }
            }

            if ($user_data["country"]) {
                load_helper("country_list");
                $country_list = get_country_list();
                if (!in_array($user_data["country"], array_keys($country_list))) {
                    $user_data["country"] = "";
                    $error[] = __("Enter valid country");
                    $complete = false;
                }
            }

            if ($complete) {
                $user_data["resitration_status"] = "complete";
            }

            $updated = $this->auth_model->update_user($user["id"], $user_data);

            if (!$updated) {
                $error[] = __("Could not update profile");
            }

            if (count($error)) {
                $this->session_lib->flash_set("auth_last_error", implode("\n", $error));
            }

            $this->http->redirect(locale_base_url() . "auth/register");
        }
    }

    private function handle_registration_by_email($action) {
        $error = [];
        if ($action == "register_email") {
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
                if (!$this->auth_model->is_good_email($email)) {
                    $error[] = __("The email id you gave is not valid");
                } elseif ($this->auth_model->get_user_by_mail($email)
                    || $this->auth_model->get_user_old_ldap($email)
                ) {
                    $error[] = __("The email id you gave is already registered");
                } else {
                    $sent = $this->send_verification_mail($email, "create_user");
                    if (!$sent) {
                        $error[] = __("Could not send mail");
                    } else {
                        $this->session_lib->flash_set("auth_last_action", "email_sent");
                    }
                }
            } else {
                $error[] = "Invalid request";
            }
            $this->session_lib->flash_set("auth_last_error", implode("\n", $error));
            $this->http->redirect(locale_base_url() . "auth/register");
        } elseif ($action == "password_reset") {
            $hash = isset($_POST["hash"]) ? $_POST["hash"] : null;
            $success = false;
            if (!empty($_POST["password"]) && !empty($_POST["confirm_password"])
                && $_POST["password"] == $_POST["confirm_password"]
            ) {
                // TODO: password verification
                $password = $_POST["password"];

                $verified = $this->auth_model->verify_mail($hash, false);
                if (!$verified) {
                    $error[] = __("Invalid request");
                } elseif(strlen($password) < 6) {
                    $error[] = __("Password must be at least 6 characters long");
                } else {
                    $email = $verified["email"];
                    $action = $verified["action"];

                    if ($action == "reset_password") {
                        $updated = $this->auth_model->reset_ldap_password($email, $password);
                        if ($updated) {
                            $this->auth_model->remove_verify_hash($hash);
                            $success = true;
                        } else {
                            $error[] = __("Could not update");
                        }
                    } elseif ($action == "create_user") {
                        $updated = $this->auth_model->create_ldap_user($email, $password);
                        if ($updated) {
                            $this->auth_model->remove_verify_hash($hash);
                            $success = true;
                        } else {
                            $error[] = __("Could not create user");
                        }
                    } else {
                        $error[] = __("Invalid request");
                    }
                }
            } else {
                $error[] = __("Passwords does not match");
            }
            if (!isset($hash) || !$hash) {
                $this->http->response_code(400);
            }
            $this->load_view("auth/password_reset", [
                "success" => $success,
                "error" => implode("\n", $error),
                "hash" => $hash,
                "action" => $action,
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
            exit();
        }
    }

    private function register_by_email() {
        $this->load_view("auth/register_by_email", [
            "sent"  => ($this->session_lib->flash_get("auth_last_action") == "email_sent"),
            "error" => $this->session_lib->flash_get("auth_last_error"),
            "is_authenticated" => $this->auth_lib->is_authenticated()
        ]);
    }

    public function forgot_password() {
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
            $user = $this->auth_model->get_user_old_ldap($email);
            $error = [];

            if (!$user) {
                if ($this->auth_model->get_user_by_mail($email)) {
                    $error[] = __("You didn't registered with email, maybe you used Google, GitHub or Facebook to login?");
                } else {
                    $error[] = __("This email id is not registred");
                }
            } else {
                if ($this->send_verification_mail($email, "reset_password")) {
                    $this->session_lib->flash_set("auth_last_action", "email_sent");
                } else {
                    $error[] = __("Could not send mail");
                }
            }
            $this->session_lib->flash_set("auth_last_error", implode("\n", $error));
        }
        $this->load_view("auth/forgot_password", [
            "sent"  => ($this->session_lib->flash_get("auth_last_action") == "email_sent"),
            "error" => $this->session_lib->flash_get("auth_last_error"),
            "is_authenticated" => $this->auth_lib->is_authenticated()
        ]);
    }

    function register($action = false) {
        $user = $this->auth_lib->get_user_details();

        $this->handle_registration_by_email($action);
        $this->handle_user_update($action, $user);

        if ($user === false) {
            $this->load_library("cas_lib");
            $oauth_id = $this->cas_lib->getUser();
            $oauth_attr = $this->cas_lib->getAttributes();

            if (!$oauth_id) {
                // Actually a new user, without login
                $this->register_by_email();
                return;
            }

            $user_data = $this->extract_user_info_oauth($oauth_id, $oauth_attr);

            if (isset($user_data["mail"])) {
                $old_user_data = $this->auth_model->get_user_old_ldap($user_data["mail"]);
                if (is_array($old_user_data)) {
                    foreach ($old_user_data as $key => $value) {
                        if ($key == "uid") {
                            continue;
                        }
                        if (!isset($user_data[$key])) {
                            $user_data[$key] = $value;
                        }
                    }
                }
            }

            $create_user = true;

            if (isset($user_data["mail"])){
                $already_registered = $this->auth_model->get_user_by_mail($user_data["mail"]);
                if ($already_registered) {
                    if ($already_registered["email_verified"]) {
                        $this->auth_model->link_oauth_account($oauth_id, $already_registered["id"]);
                        $create_user = false;
                    } else {
                        // Discard old user
                        $this->auth_model->remove_user($already_registered["id"]);
                    }
                }
            }

            if ($create_user) {
                if (isset($user_data["nick"])
                    && $this->auth_model->get_user_by_nick($user_data["nick"])
                ) {
                    unset($user_data["nick"]);
                }

                $created = $this->auth_model->create_user($oauth_id, $user_data);
            }

            $user = $this->auth_lib->get_user_details();

            if (isset($old_user_data["uid"])) {
                $this->auth_model->link_oauth_account($old_user_data["uid"], $user["id"]);
            }
        }

        $reg_status = $user["resitration_status"];

        if ($reg_status == "email_required") {
            $this->load_view("auth/confirm_email", [
                "user_data" => $user,
                "error" => $this->session_lib->flash_get("auth_last_error"),
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        } elseif ($reg_status == "incomplete") {
            $this->load_view("auth/profile", [
                "user_data" => $user,
                "error" => $this->session_lib->flash_get("auth_last_error"),
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        } elseif (!$user["email_verified"]){
            $this->load_view("auth/email_resend", [
                "error" => $this->session_lib->flash_get("auth_last_error"),
                "is_authenticated" => $this->auth_lib->is_authenticated()
            ]);
        } else {
            // Everything is fine!
            $goto_url = $this->session_lib->flash_get("auth_go_back");
            if ($goto_url) {
                $this->http->redirect($goto_url);
            } else {
                $this->http->redirect(locale_base_url());
            }
        }
    }

    function get_magic_user() {
        // End point for checking and getting user data from oauth id
        if (empty($_POST["oauth_id"])) {
            $this->http->response_code(403, false);
            exit();
        }

        $oauth_id = $_POST["oauth_id"];

        $user = $this->auth_model->get_user($oauth_id);

        global $auth_cfg;
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        load_helper('ip_validation');

        if (!empty($user)
            && isset($user["resitration_status"]) && $user["resitration_status"] == "complete"
            && isset($user["email_verified"]) && $user["email_verified"]
            && check_ipv4_in_cidr($remote_ip, $auth_cfg['magic_hosts'])
        ) {
            $user_data = [];
            $user_data["nick"] = $user["nick"];
            $user_data["country"] = $user["country"];
            echo json_encode($user_data);
            exit();
        }

        $this->http->response_code(403, false);
        exit();
    }

    function login() {
        if (!empty($_GET['next'])) {
            $next_url = base_url() . $_GET['next'];
            $this->session_lib->flash_set("auth_next_page", $next_url);
        }

        $this->auth_lib->force_authentication();

        $next_page = $this->session_lib->flash_get("auth_next_page");
        if (empty($next_page)) {
            $next_page = locale_base_url();
        }
        $this->http->redirect($next_page);
    }

    function logout() {
        $this->auth_lib->logout();
    }
}
