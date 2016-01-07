<?php

class auth extends Controller {

    function __construct() {
        $this->load_library("auth_lib");
        $this->load_library("http_lib", "http");
        $this->load_library("session_lib");

        $this->load_model("auth_model");
    }

    function index() {
        $this->auth_lib->force_authentication();
        var_dump($this->auth_lib->get_user());
        var_dump($this->auth_lib->get_user_details());
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
        $verified = $this->auth_model->verify_mail($hash);
        if (!$verified) {
            $this->http->response_code(400);
        }

        $email = $verified["email"];
        $action = $verified["action"];

        if ($action == "verify_email") {
            $success = false;
            $user = $this->auth_model->get_user_by_mail($email);
            if ($user) {
                var_dump($user);
                $updated = $this->auth_model->update_user($user["id"], [
                    "email_verified" => "1"
                ]);
                if ($updated) {
                    $success = true;
                }
            }
            if (!$success) {
                echo "failed";
            } else {
                echo "done";
            }
        } elseif ($action == "reset_password") {
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

        $this->load_view("auth/mail_resent");
    }

    private function send_verification_mail($email, $action) {
        if (!in_array($action, ["reset_password", "verify_email"])) {
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
            $subject = "Password reset link - Felicity";
        } elseif ($action == "verify_email") {
            $subject = "Verify email link - Felicity";
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

            if ($mail != $user["mail"]) {
                if ($this->auth_model->get_user_by_mail($mail)) {
                    $error[] = "The email id you gave is already registered";
                } else {
                    $updated = $this->auth_model->update_user($user["id"], [
                        "mail" => $mail,
                        "email_verified" => "0",
                        "resitration_status" => "incomplete"
                    ]);
                    if ($updated) {
                        $sent = $this->send_verification_mail($mail, "verify_email");
                        if (!$sent) {
                            $error[] = "Could not send mail";
                        }
                    }
                }
            } else {
                $updated = $this->auth_model->update_user($user["id"], [
                    "resitration_status" => "incomplete"
                ]);
            }

            if (!$updated) {
                $error[] = "Could not update email";
            }

            $this->session_lib->flash_set("auth_last_error", implode("\n", $error));

            $this->http->redirect(base_url() . "auth/register");
        } elseif ($action == "update_profile") {
            if ($user["resitration_status"] == "complete") {
                return;
            }

            $user_data = [
                "nick" => $_POST["nick"],
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
                    $error[] = "Please fill all the required fields";
                    $complete = false;
                }
            }

            if ($user["nick"] != $user_data["nick"]
                && $this->auth_model->get_user_by_nick($user_data["nick"])
            ) {
                unset($user_data["nick"]);
                $complete = false;
                $error[] = "Nick is already taken";
            }

            if ($complete) {
                $user_data["resitration_status"] = "complete";
            }

            $updated = $this->auth_model->update_user($user["id"], $user_data);

            if (!$updated) {
                $error[] = "Could not update profile";
            }

            if (count($error)) {
                $this->session_lib->flash_set("auth_last_error", implode("\n", $error));
            }

            $this->http->redirect(base_url() . "auth/register");
        }
    }

    function register($action = false) {
        $user = $this->auth_lib->get_user_details();

        $this->handle_user_update($action, $user);

        if ($user === false) {
            $this->load_library("cas_lib");
            $oauth_id = $this->cas_lib->getUser();
            $oauth_attr = $this->cas_lib->getAttributes();

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
                "error" => $this->session_lib->flash_get("auth_last_error")
            ]);
        } elseif ($reg_status == "incomplete") {
            $this->load_view("auth/profile", [
                "user_data" => $user,
                "error" => $this->session_lib->flash_get("auth_last_error")
            ]);
        } elseif (!$user["email_verified"]){
            $this->load_view("auth/email_resend", [
                "error" => $this->session_lib->flash_get("auth_last_error")
            ]);
        } else {
            // Everything is fine!
            $goto_url = $this->session_lib->flash_get("auth_go_back");
            if ($goto_url) {
                $this->http->redirect($goto_url);
            } else {
                $this->http->redirect(base_url());
            }
        }
    }

    function login() {
        $this->auth_lib->force_authentication();
        $this->http->redirect(base_url());
    }

    function logout() {
        $this->auth_lib->logout();
    }
}
