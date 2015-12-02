<?php

class auth extends Controller {

    function __construct() {
        $this->load_library("cas_lib", "cas");
        $this->load_library("http_lib", "http");
    }

    function index() {
        $this->login();
    }

    function login() {
        $this->cas->forceAuthentication();
        $this->http->redirect(base_url());
    }

    function logout() {
        $this->cas->logout();
    }
}
