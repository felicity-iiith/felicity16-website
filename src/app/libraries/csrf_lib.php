<?php

class csrf_lib extends Library {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    /**
     * Generates a new CSRF token
     * @return string Base64 encoded token
     */
    public static function generate_token() {
        return base64_encode(openssl_random_pseudo_bytes(12));
    }

    /**
     * Generates a new CSRF token and sets it as the current token
     * @return string The token
     */
    public function new_csrf_token() {
        $csrf_token = self::generate_token();
        $_SESSION['csrf_token'] = $csrf_token;
        return $csrf_token;
    }

    /**
     * Checks whether CSRF is set and whether the current CSRF token from
     * the POST data is correct. If not, returns a 400 HTTP response code,
     * loads the 400 view and quits.
     */
    public function check_csrf_token($value) {
        if (! isset($_SESSION['csrf_token'])
            || $value !== $_SESSION['csrf_token']
        ) {
            // If no CSRF token is set, or the $value does not match it, error
            $this->load_library('http_lib', 'http');
            $this->http->response_code(400);
            return false;
        }
        return true;
    }
}
