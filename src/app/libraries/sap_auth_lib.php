<?php

class sap_auth_lib extends Library {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function force_authentication() {
        if (empty($_SESSION['sap_username'])) {
            $this->load_library('http_lib');
            $this->http_lib->redirect(base_url() . 'sap/login/');
        }
    }

    public function is_authenticated() {
        if (empty($_SESSION['sap_username'])) {
            return false;
        }
        return true;
    }

    public function get_current_username() {
        if ($this->is_authenticated()) {
            return $_SESSION['sap_username'];
        }
        return false;
    }

    public function login($username, $password) {
        $this->load_model('sap_model');
        $success = $this->sap_model->check_credentials($username, $password);
        if ($success) {
            $_SESSION['sap_username'] = $username;
            $is_admin = $this->sap_model->is_admin($username);
            $_SESSION['sap_is_admin'] = $is_admin;
            return true;
        }
        return false;
    }

    public function is_current_user_admin() {
        if ($this->is_authenticated()) {
            return $_SESSION['sap_is_admin'];
        }
        return false;
    }

    public function logout() {
        $this->load_library('http_lib');

        if ($this->is_authenticated()) {
            unset($_SESSION['sap_username']);
            unset($_SESSION['sap_is_admin']);
        }

        $this->http_lib->redirect(base_url() . 'sap/');
    }
}
