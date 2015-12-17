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
        $this->load_model('sap_model');
        $missions = $this->sap_model->get_missions();
        $this->load_view('sap/dashboard', [
            'username' => $this->username,
            'is_admin' => $this->sap_auth->is_current_user_admin(),
            'missions' => $missions,
        ]);
    }

    public function mission($id) {
        if (isset($id) && ctype_digit($id)) {
            $this->load_model('sap_model');
            $mission = $this->sap_model->get_mission($id);
            if ($mission) {
                $tasks = $this->sap_model->get_tasks($id);
                $this->load_view('sap/mission', [
                    'mission' => $mission,
                    'tasks' => $tasks,
                ]);
                return;
            }
        }
        $this->load_library('http_lib');
        $this->http_lib->response_code(404);
    }
}
