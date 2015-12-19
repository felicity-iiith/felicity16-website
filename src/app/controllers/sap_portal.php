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

    public function mission($id, $action = '') {
        if (isset($id) && ctype_digit($id)) {
            $mission = $this->sap_model->get_mission($id);
            if ($mission) {
                $tasks = $this->sap_model->get_tasks($id);
                $submissions = $this->sap_model->get_task_submissions(
                    $this->sap_auth->get_current_user_id()
                );
                // TODO: Make this faster than O(n^2)?
                foreach ($submissions as $submission) {
                    foreach ($tasks as &$task) {
                        if ($submission['task_id'] == $task['id']) {
                            $task['submission'] = $submission;
                        }
                    }
                }
                if ($action == 'createtask') {
                    $this->create_task($mission);
                    return;
                } elseif ($action == 'submittask') {
                    $this->submit_task($mission, $tasks, $submissions);
                    return;
                } else {
                    $this->load_view('sap/mission', [
                        'mission' => $mission,
                        'tasks' => $tasks,
                        'is_admin' => $this->sap_auth->is_current_user_admin(),
                    ]);
                }
                return;
            }
        }
        $this->load_library('http_lib');
        $this->http_lib->response_code(404);
    }

    private function create_task($mission) {
        if ($this->sap_auth->is_current_user_admin() === false) {
            $this->http_lib->response_code(403);
        }
        $mission_title = $mission['title'];
        $mission_id = $mission['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $posted_data = [];

            foreach ($_POST as $key => $value) {
                $posted_data[$key] = trim($value);
            }

            $errors = $this->validate_data($posted_data, [
                'required_fields' => ['description'],
            ]);
            if (count($errors) != 0) {
                $this->load_view('sap/create_task', [
                    'errors' => $errors,
                    'mission_title' => $mission_title,
                ]);
            } else {
                $result = $this->sap_model->create_task(
                    $mission_id,
                    $posted_data['description'],
                    isset($posted_data['has-text-answer'])
                );
                if ($result) {
                    $this->http_lib->redirect(base_url() . "sap/portal/mission/$mission_id/");
                }
                // View handles $result = false case
                $this->load_view('sap/create_task', [
                    'result' => $result,
                    'mission_title' => $mission_title,
                ]);
            }
        } else {
            $this->load_view('sap/create_task', [
                'mission_title' => $mission_title,
            ]);
        }
    }

    private function submit_task($mission, $tasks) {
        // TODO: Refactor after flash data library is made
        $mission_id = $mission['id'];
        if (isset($_POST['submit-task'])) {
            $user_id = $this->sap_auth->get_current_user_id();
            $text_answer = null;
            if (isset($_POST['text-answer'])) {
                $text_answer = $_POST['text-answer'];
            }
            $success = $this->sap_model->submit_task($_POST['submit-task'], $user_id, $text_answer);
            if ($success) {
                $this->http_lib->redirect(base_url() . "sap/portal/mission/$mission_id/");
            } else {
                $this->load_view('sap/mission', [
                    'mission' => $mission,
                    'tasks' => $tasks,
                    'is_admin' => $this->sap_auth->is_current_user_admin(),
                    'error' => 'Could not submit task. :/',
                ]);
            }
        } else {
            $this->http_lib->redirect(base_url() . "sap/portal/mission/$mission_id/");
        }
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

            $errors = $this->validate_data($posted_data, [
                'required_fields' => ['title', 'points', 'level', 'description'],
            ]);

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

    private function validate_data($posted_data, $rules) {
        $required_fields = [];
        if (isset($rules['required_fields'])) {
            $required_fields = $rules['required_fields'];
        }
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
            $errors[] = "Please fill in all the required fields.";
        }

        return $errors;
    }
}
