<?php

class sap_portal extends Controller {
    public function __construct() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->sap_auth->force_authentication();
        $this->username = $this->sap_auth->get_current_username();
    }

    public function index() {
        $this->load_library('http_lib');
        $this->http_lib->redirect(base_url() . 'sap/portal/dashboard/');
    }

    public function dashboard() {
        $this->load_view('sap/dashboard', [
            'username' => $this->username,
            'is_admin' => $this->sap_auth->is_current_user_admin(),
        ]);
    }
}
