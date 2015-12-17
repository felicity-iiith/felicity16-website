<?php

class sap_portal extends Controller {
    public function __construct() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->sap_auth->force_authentication();
        $this->username = $this->sap_auth->get_current_username();
        $this->load_library('http_lib');
        $this->load_model('sap_model');
    }

    public function index() {
        $this->http_lib->redirect(base_url() . 'sap/portal/dashboard/');
    }

    public function dashboard() {
        $missions = $this->sap_model->get_missions();
        $this->load_view('sap/dashboard', [
            'username' => $this->username,
            'is_admin' => $this->sap_auth->is_current_user_admin(),
            'missions' => $missions,
        ]);
    }

    public function mission($id) {
        if (isset($id) && ctype_digit($id)) {
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

    public function create_mission() {
        if ($this->sap_auth->is_current_user_admin() === false) {
            $this->http_lib->response_code(403);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $posted_data = [];

            foreach ($_POST as $key => $value) {
                $posted_data[$key] = trim($value);
            }

            $errors = $this->validate_create_mission_data($posted_data);

            if (count($errors) != 0) {
                $this->load_view('sap/create_mission', [
                    'errors' => $errors,
                ]);
            } else {
                $result = $this->sap_model->create_mission(
                    $posted_data['title'],
                    $posted_data['level'],
                    $posted_data['points'],
                    $posted_data['description']
                );
                // View handles $result = false case
                $this->load_view('sap/create_mission', [
                    'result' => $result,
                ]);
            }
        } else {
            $this->load_view('sap/create_mission');
        }
    }

    private function validate_create_mission_data($posted_data) {
        $required_fields = [
            'title', 'points', 'level', 'description',
        ];
        $errors = [];
        $required_unfilled = false;
        foreach ($required_fields as $name) {
            if (empty($posted_data[$name])) {
                $required_unfilled = true;
            }
        }
        if ($required_unfilled === true) {
            // No additional details, because the HTML5 required attribute is set to true
            // and this is in case it gets bypassed.
            $errors[] = "Please fill in all the fields.";
        }

        return $errors;
    }
}
