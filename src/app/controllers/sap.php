<?php

class sap extends Controller {

    function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            $postedData = [];

            foreach ($_POST as $key => $value) {
                $postedData[$key] = trim($value);
            }

            // Keeping the human readable fields in case a better error message
            // is made in the future.
            $requiredFields = [
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

            $requiredUnfilled = false;
            foreach ($requiredFields as $name => $message) {
                if (empty($postedData[$name])) {
                    $requiredUnfilled = true;
                }
            }
            if ($requiredUnfilled == true) {
                // No additional details, because the HTML5 required attribute is set to true
                // and this is in case it gets bypassed.
                $errors[] = "Please fill in the required fields.";
            }

            if (count($errors) != 0) {
                $this->load_view('sap/register', [
                    'errors' => $errors,
                ]);
            } else {
                $this->load_model('sap_model');
                $success = $this->sap_model->registerEntry($postedData);

                // View handles if success is false.
                $this->load_view('sap/register', [
                    'success' => $success
                ]);
            }

        } else {
            $this->load_view('sap/register');
        }
    }

}
