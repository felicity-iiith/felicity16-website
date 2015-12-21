<?php

class sap extends Controller {

    function index() {
        $this->load_library('csrf_lib');
        $this->load_library('sap_auth_lib', 'sap_auth');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['csrf_token'])) {
                $recvd_token = $_POST['csrf_token'];
            } else {
                $recvd_token = NULL;
            }
            $this->csrf_lib->check_csrf_token($recvd_token);
            $this->csrf_lib->reset_csrf_token();

            $posted_data = [];

            foreach ($_POST as $key => $value) {
                $posted_data[$key] = trim($value);
            }

            $errors = $this->validateData($posted_data);

            if (count($errors) != 0) {
                $this->load_view('sap/register', [
                    'errors' => $errors,
                    'csrf_token' => $this->csrf_lib->new_csrf_token(),
                    'logged_in' => $this->sap_auth->is_authenticated(),
                ]);
            } else {
                $this->handleRegistration($posted_data);
            }

        } else {
            $this->load_view('sap/register', [
                'csrf_token' => $this->csrf_lib->new_csrf_token(),
                'logged_in' => $this->sap_auth->is_authenticated(),
            ]);
        }
    }

    public function login() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->load_library('csrf_lib');
        $this->load_library('http_lib');

        if ($this->sap_auth->is_authenticated()) {
            $this->http_lib->redirect(base_url() . 'sap/portal/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['csrf_token'])) {
                $recvd_token = $_POST['csrf_token'];
            } else {
                $recvd_token = NULL;
            }
            $this->csrf_lib->check_csrf_token($recvd_token);
            $this->csrf_lib->reset_csrf_token();
            $success = $this->sap_auth->login($_POST['email'], $_POST['password']);
            if ($success) {
                $this->http_lib->redirect(base_url() . 'sap/portal/');
            } else {
                $this->load_view('sap/login', [
                    'error' => true,
                    'csrf_token' => $this->csrf_lib->new_csrf_token(),
                ]);
            }
        } else {
            $this->load_view('sap/login', [
                'csrf_token' => $this->csrf_lib->new_csrf_token(),
            ]);
        }
    }

    public function logout() {
        $this->load_library('sap_auth_lib', 'sap_auth');
        $this->sap_auth->logout();
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
        $this->load_library('sap_auth_lib', 'sap_auth');
        $success = $this->sap_model->registerEntry($posted_data);

        if ($success) {
            $this->load_library('email_lib', 'email');
            $subject = "Felicity '16 Student Ambassador Program";

            ob_start();
            $this->load_view('sap/register_success_email_html', [
                'subject' => $subject,
                'name' => $posted_data['name'],
            ]);
            $email_body_html = ob_get_contents();
            ob_end_clean();

            ob_start();
            $this->load_view('sap/register_success_email_text', [
                'name' => $posted_data['name'],
            ]);
            $email_body_text = ob_get_contents();
            ob_end_clean();

            $this->email->send_mail([
                'from_email'=> 'noreply@felicity.iiit.ac.in',
                'from_name' => 'Team Felicity',
                'to_email'  => $posted_data['email'],
                'to_name'   => $posted_data['name'],
                'subject'   => $subject,
                'html_body' => $email_body_html,
                'alt_body'  => $email_body_text,
            ]);
        }

        // View handles if success is false.
        $this->load_view('sap/register', [
            'success' => $success,
            'csrf_token' => $this->csrf_lib->new_csrf_token(),
            'logged_in' => $this->sap_auth->is_authenticated(),
        ]);
    }
}
