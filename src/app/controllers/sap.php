<?php

class sap extends Controller {

    public function __construct() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->load_library('csrf_lib');
        $this->load_library('http_lib');
        $this->load_library('session_lib');
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['csrf_token'])) {
                $recvd_token = $_POST['csrf_token'];
            } else {
                $recvd_token = null;
            }
            $this->csrf_lib->check_csrf_token($recvd_token);
            $this->csrf_lib->reset_csrf_token();

            $posted_data = [];

            foreach ($_POST as $key => $value) {
                $posted_data[$key] = trim($value);
            }

            $errors = $this->validateData($posted_data);

            if (count($errors) != 0) {
                $this->session_lib->flash_set('errors', $errors);
                $this->http_lib->redirect(base_url() . 'sap/');
            } else {
                $this->handleRegistration($posted_data);
            }

        } else {
            $this->load_view('sap/register', [
                'csrf_token' => $this->csrf_lib->new_csrf_token(),
                'logged_in' => $this->sap_auth->is_authenticated(),
                'errors' => $this->session_lib->flash_get('errors'),
                'success' => $this->session_lib->flash_get('success'),
            ]);
        }
    }

    public function login() {
        if ($this->sap_auth->is_authenticated()) {
            $this->http_lib->redirect(base_url() . 'sap/portal/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['csrf_token'])) {
                $recvd_token = $_POST['csrf_token'];
            } else {
                $recvd_token = null;
            }
            $this->csrf_lib->check_csrf_token($recvd_token);
            $this->csrf_lib->reset_csrf_token();
            $success = $this->sap_auth->login($_POST['email'], $_POST['password']);
            if ($success) {
                $this->http_lib->redirect(base_url() . 'sap/portal/');
            } else {
                $this->session_lib->flash_set('error', true);
                $this->http_lib->redirect(base_url() . 'sap/login/');
            }
        } else {
            $this->load_view('sap/login', [
                'csrf_token' => $this->csrf_lib->new_csrf_token(),
                'error' => $this->session_lib->flash_get('error'),
            ]);
        }
    }

    public function logout() {
        $this->sap_auth->logout();
    }

    public function verify($hash = "") {
        $this->load_library('http_lib');
        if ($hash == "") {
            $this->http_lib->response_code(404);
        }
        $this->load_model('sap_model');
        $user = $this->sap_model->verify_hash($hash);
        if (! $user) {
            $this->http_lib->response_code(404);
        }
        $this->load_library('csrf_lib');
        $this->load_library('session_lib');
        $this->load_view('sap/create_password', [
            'csrf_token' => $this->csrf_lib->new_csrf_token(),
            'hash' => $hash,
            'error' => $this->session_lib->flash_get('error'),
        ]);
    }

    public function confirm() {
        $this->load_library('http_lib');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->http_lib->response_code(400);
        }
        foreach (['hash', 'password', 'csrf_token'] as $name) {
            if (! isset($_POST[$name])) {
                $this->http_lib->response_code(400);
            }
        }
        $this->load_library('csrf_lib');
        $this->csrf_lib->check_csrf_token($_POST['csrf_token']);
        $this->csrf_lib->reset_csrf_token();
        $this->load_model('sap_model');
        $user = $this->sap_model->verify_hash($_POST['hash']);
        if (! $user) {
            $this->http_lib->response_code(400);
        }
        $success = $this->sap_model->create_user_password($user, $_POST['password']);
        if ($success) {
            $this->http_lib->redirect(base_url() . 'sap/login/');
        } else {
            $this->load_library('session_lib');
            $this->session_lib->flash_set('error', true);
            $this->http_lib->redirect(base_url() . 'sap/verify/' . $_POST['hash']);
        }
    }

    private function validateData($posted_data) {
        $errors = [];

        // Keeping the human readable fields in case a better error message
        // is made in the future.
        $required_fields = [
            'name' => "your name",
            'email' => "your email",
            'phone-number' => "your phone number",
            'college' => "your college/university",
            'program-of-study' => "your program of study",
            'year-of-study' => "your year of study",
            'facebook-profile-link' => "a link to your Facebook profile",
            'why-apply' => "why you're applying",
            'about-you' => "a little about yourself"
        ];

        $required_unfilled = false;
        foreach ($required_fields as $name => $message) {
            if (empty($posted_data[$name])) {
                $required_unfilled = true;
            }
        }
        if ($required_unfilled === true) {
            // No additional details, because the HTML5 required attribute is set to true
            // and this is in case it gets bypassed.
            $errors[] = "Please fill in the required fields.";
        }

        return $errors;
    }

    private function handleRegistration($posted_data) {
        $this->load_model('sap_model');
        $success = $this->sap_model->registerEntry($posted_data);

        if ($success) {
            $this->load_library('email_lib');
            $subject = "Felicity '16 Student Ambassador Program";

            $mail = $this->email_lib->compose_mail("noreply");

            $this->email_lib->set_html_view($mail, 'sap/register_success_email_html', [
                'subject' => $subject,
                'name' => $posted_data['name'],
            ]);
            $this->email_lib->set_text_view($mail, 'sap/register_success_email_text', [
                'name' => $posted_data['name'],
            ]);

            $this->email_lib->send_mail($mail, [
                'to_email'  => $posted_data['email'],
                'to_name'   => $posted_data['name'],
                'subject'   => $subject,
            ]);
        }

        // View handles if success is false.
        $this->session_lib->flash_set('success', $success);
        $this->http_lib->redirect(base_url() . 'sap/');
    }
}
