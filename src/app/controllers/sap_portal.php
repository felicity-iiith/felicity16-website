<?php

class sap_portal extends Controller {
    public function __construct() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->sap_auth->force_authentication();
        $this->email = $this->sap_auth->get_current_email();
        $this->load_library('http_lib');
        $this->load_model('sap_model');
        $this->load_library('session_lib');
    }

    public function index() {
        $this->http_lib->redirect(base_url() . 'sap/portal/dashboard/');
    }

    public function dashboard() {
        $missions = $this->sap_model->get_missions();
        $this->load_view('sap/dashboard', [
            'email' => $this->email,
            'is_admin' => $this->sap_auth->is_current_user_admin(),
            'missions' => $missions,
        ]);
    }

    public function mission($id = null, $action = '') {
        if (isset($id) && ctype_digit($id)) {
            $mission = $this->sap_model->get_mission($id);
            if ($mission) {
                $tasks = $this->sap_model->get_tasks_with_submissions(
                    $this->sap_auth->get_current_user_id(),
                    $id,
                    true // Deletes rejected task submissions
                );
                if ($action == 'createtask') {
                    $this->create_task($mission);
                    return;
                } elseif ($action == 'submittask') {
                    $this->submit_task($mission, $tasks);
                    return;
                } else {
                    $this->load_view('sap/mission', [
                        'mission' => $mission,
                        'tasks' => $tasks,
                        'is_admin' => $this->sap_auth->is_current_user_admin(),
                        'errors' => $this->session_lib->flash_get('errors'),
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
                    isset($posted_data['has-text-answer']) // sends just the boolean
                );
                if ($result) {
                    $this->http_lib->redirect(base_url() . "sap/portal/mission/$mission_id/");
                }

                // If we reach here, an error has occurred.
                $this->load_view('sap/create_task', [
                    'errors' => ['Something went wrong. :/'],
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
        $mission_id = $mission['id'];
        if (isset($_POST['submit-task'])) {
            $user_id = $this->sap_auth->get_current_user_id();
            $text_answer = null;
            if (isset($_POST['text-answer'])) {
                $text_answer = $_POST['text-answer'];
            }
            $success = $this->sap_model->submit_task($_POST['submit-task'], $user_id, $text_answer);
            if (!$success) {
                $this->session_lib->flash_set('errors', ['Sorry, could not submit task. :/']);
            }
        }
        $this->http_lib->redirect(base_url() . "sap/portal/mission/$mission_id/");
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
                // Returns mission ID if successful, else false
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

    public function review_mission($mission_id) {
        if (! $this->sap_auth->is_current_user_admin()) {
            $this->http_lib->response_code(403);
        }
        $submissions = $this->sap_model->get_task_submissions_for_review($mission_id);
        $this->load_view('sap/review_mission', [
            'submissions' => $submissions,
            'mission_id' => $mission_id,
            'result' => $this->session_lib->flash_get('result'),
            'success' => $this->session_lib->flash_get('success'),
        ]);
    }

    public function review_submission($submission_id) {
        if (! $this->sap_auth->is_current_user_admin()) {
            $this->http_lib->response_code(403);
        }
        $action = $_POST['action'];
        $success = $this->sap_model->submit_review(
            $submission_id,
            ($_POST['action'] == 'approve') // boolean indicating approved or not
        );
        // TODO: Check if all tasks have been completed and if so, award points!
        $mission_id = $_POST['mission-id'];
        if ($success) {
            $this->session_lib->flash_set('result', "Action \"$action\" successful!");
            $this->session_lib->flash_set('success', true);
        } else {
            $this->session_lib->flash_set('result', "Failed to $action submission! :/");
            $this->session_lib->flash_set('success', false);
        }
        $this->http_lib->redirect(base_url() . "sap/portal/review/mission/$mission_id/");
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
