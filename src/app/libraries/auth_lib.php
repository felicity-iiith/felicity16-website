<?php

/**
 * Auth Library
 */
class auth_lib extends Library {

    public function __construct() {
        $this->load_library("cas_lib");
        $this->load_model("auth_model");
    }

    private function register_new_user() {
        $this->load_library("session_lib");
        $this->load_library("http_lib");

        $current_path = base_url()
            . substr((empty($_SERVER["PATH_INFO"]) ? "" : $_SERVER["PATH_INFO"]), 1)
            . (empty($_SERVER["QUERY_STRING"]) ? "" : ("?" . $_SERVER["QUERY_STRING"]));

        $this->session_lib->flash_set("auth_go_back", $current_path);

        $this->http_lib->redirect(base_url() . "auth/register");
    }

    public function force_authentication() {
        $this->cas_lib->forceAuthentication();

        $oauth_id = $this->cas_lib->getUser();
        $user = $this->auth_model->get_user($oauth_id);

        if ($user === false
            || $user["resitration_status"] != "complete"
            || !$user["email_verified"]
        ) {
            $this->register_new_user();
        }
    }

    public function is_authenticated() {
        return $this->cas_lib->isAuthenticated();
    }

    public function logout() {
        $this->cas_lib->logout();
    }

    public function get_user() {
        $user = $this->get_user_details();

        if ($user && !empty($user["nick"])) {
            return $user["nick"];
        }
        return false;
    }

    public function get_user_details() {
        if (!$this->cas_lib->isAuthenticated()) {
            return false;
        }

        $oauth_id = $this->cas_lib->getUser();
        $user = $this->auth_model->get_user($oauth_id);

        if ($user) {
            return $user;
        }
        return false;
    }
}
