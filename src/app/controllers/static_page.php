<?php

class static_page extends Controller {

    public function __construct() {
        $this->load_library('http_lib');
    }

    public function login() {
        $this->http_lib->redirect( locale_base_url() . 'auth/login/' );
    }

    public function logout() {
        $this->http_lib->redirect( locale_base_url() . 'auth/logout/' );
    }

    public function register() {
        $this->http_lib->redirect( locale_base_url() . 'auth/login/' );
    }

    public function wdw() {
        $this->http_lib->redirect( locale_base_url() . 'talks-and-workshops/web-development/' );
    }

    public function codecraft() {
        $this->load_view('contest/codecraft_scores/');
    }
}
