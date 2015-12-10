<?php

class sap extends Controller {

    function index() {
        $this->load_library('csrf_lib');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['csrf_token'])) {
                $recvd_token = $_POST['csrf_token'];
            } else {
                $recvd_token = NULL;
            }
            $this->csrf_lib->check_csrf_token($recvd_token);

            $errors = [];
            $posted_data = [];

            foreach ($_POST as $key => $value) {
                $posted_data[$key] = trim($value);
            }

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
            if ($required_unfilled == true) {
                // No additional details, because the HTML5 required attribute is set to true
                // and this is in case it gets bypassed.
                $errors[] = "Please fill in the required fields.";
            }

            if (count($errors) != 0) {
                $this->load_view('sap/register', [
                    'errors' => $errors,
                    'csrf_token' => $this->csrf_lib->new_csrf_token(),
                ]);
            } else {
                $this->load_model('sap_model');
                $success = $this->sap_model->registerEntry($posted_data);

                // View handles if success is false.
                $this->load_view('sap/register', [
                    'success' => $success,
                    'csrf_token' => $this->csrf_lib->new_csrf_token(),
                ]);
            }

        } else {
            $this->load_view('sap/register', [
                'csrf_token' => $this->csrf_lib->new_csrf_token(),
            ]);
        }
    }

}
