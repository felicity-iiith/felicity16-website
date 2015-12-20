<?php

class sap_auth_lib extends Library {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    /**
     * Ensures that the user is logged in.
     * If already logged in, it does nothing. Else, it redirects to the login page.
     */
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

    /**
     * Gets username of current logged in user.
     * @return string|bool Username as a string, or false if not logged in
     */
    public function get_current_username() {
        if ($this->is_authenticated()) {
            return $_SESSION['sap_username'];
        }
        return false;
    }

    /**
     * Gets user ID (DB field) of current logged in user.
     * @return int|bool User ID as an int, or false if not logged in
     */
    public function get_current_user_id() {
        if ($this->is_authenticated()) {
            return $_SESSION['sap_user_id'];
        }
        return false;
    }

    /**
     * Checks credentials and if valid, logs user in by setting $_SESSION variables
     * @param  string $username
     * @param  string $password
     * @return bool Whether login was successful
     */
    public function login($username, $password) {
        $this->load_model('sap_model');
        $user_id = $this->sap_model->check_credentials($username, $password);
        if ($user_id) {
            $_SESSION['sap_username'] = $username;
            $_SESSION['sap_user_id'] = $user_id;
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

    /**
     * Logs the user out by unsetting $_SESSION variables and then redirects to the /sap/ page
     */
    public function logout() {
        $this->load_library('http_lib');

        if ($this->is_authenticated()) {
            unset($_SESSION['sap_username']);
            unset($_SESSION['sap_user_id']);
            unset($_SESSION['sap_is_admin']);
        }

        $this->http_lib->redirect(base_url() . 'sap/');
    }
}
