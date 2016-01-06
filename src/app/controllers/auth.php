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

        if (isset($user_data["nick"])
            && $this->auth_model->get_user_by_nick($user_data["nick"])
        ) {
            unset($user_data["nick"]);
        }
        return $user_data;
    }

    private function mail_updated($mail, $user) {
        // Send email verification mail
    }

    private function handle_user_update($action) {
        $user = $this->auth_lib->get_user_details();

        if ($user === false) {
            return;
        }

        if ($action == "update_mail" && isset($_POST["mail"])) {
            $mail = $_POST["mail"];

            if ($mail != $user["mail"]) {
                $updated = $this->auth_model->update_user($user["id"], [
                    "mail" => $mail,
                    "email_verified" => "0",
                    "resitration_status" => "incomplete"
                ]);
                if ($updated) {
                    $this->mail_updated($mail, $user);
                }
            } else {
                $updated = $this->auth_model->update_user($user["id"], [
                    "resitration_status" => "incomplete"
                ]);
            }

            if (!$updated) {
                $this->session_lib->flash_set("auth_last_error", "Could not update email");
            }

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
        $this->handle_user_update($action);

        $user = $this->auth_lib->get_user_details();

        if ($user === false) {
            $this->load_library("cas_lib");
            $oauth_id = $this->cas_lib->getUser();
            $oauth_attr = $this->cas_lib->getAttributes();

            $user_data = $this->extract_user_info_oauth($oauth_id, $oauth_attr);

            $created = $this->auth_model->create_user($oauth_id, $user_data);
            $user = $this->auth_lib->get_user_details();
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
