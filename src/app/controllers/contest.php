<?php

class contest extends Controller {

    public function __construct() {
        $this->load_library('auth_lib', 'auth');
        $this->auth->force_authentication();
        $this->load_model('contest_model', 'model');
        load_helper('validations');
    }

    public function futsal() {
        $user_nick = $this->auth->get_user();
        $this->load_model('contest_model');
        $team_info = $this->contest_model->is_registered_for_futsal($user_nick);
        $errors = [];
        if (!$team_info && $_SERVER['REQUEST_METHOD'] === 'POST') {
            required_post_params([
                'team_name',
                'contact_number',
                'nick2',
                'nick3',
                'nick4',
            ], $errors);

            if (!empty($_POST['contact_number']) && !is_valid_phone_number($_POST['contact_number']) ) {
                $errors['contact_number'] = 'Please enter a valid phone number';
            }

            if (!empty($_POST['team_name']) && $this->contest_model->check_futsal_team_exists($_POST['team_name']) ) {
                $errors['team_name'] = 'This team name already exists';
            }

            $team = [$user_nick];
            $this->load_model('auth_model');
            for ($i=2; $i <= 6; $i++) {
                if ( ! empty($_POST['nick'.$i]) ) {
                    $teammate = $this->auth_model->get_user_by_nick($_POST['nick'.$i]);
                    if (!empty($teammate)
                        && isset($teammate["resitration_status"]) && $teammate["resitration_status"] == "complete"
                        && isset($teammate["email_verified"])     && $teammate["email_verified"]
                    ) {
                        if ($this->contest_model->check_futsal_participant_exists($_POST['nick'.$i])) {
                            $errors['nick'.$i] = 'This member is already registered';
                        } else {
                            $team[] = $_POST['nick'.$i];
                        }
                    } else {
                        $errors['nick'.$i] = 'This member is not registered';
                    }
                }
            }

            if (count(array_unique($team)) != count($team)) {
                $errors['common'] = 'Duplicate members in team';
            }

            if (!$errors) {
                $team_info = [
                    'team_name' => $_POST['team_name'],
                ];
                $success = $this->model->register_for_futsal(
                    $_POST['team_name'],
                    $_POST['contact_number'],
                    $team
                );
                if (!$success) {
                    $errors['common'] = 'Some unexpected error occured';
                }
            }

        }

        $this->load_view('skeleton_template/header', [
            'title'             => __('Register').' · '.__('Futsal'),
            'is_authenticated'  => true,
            'user_nick'         => $user_nick,
        ]);

        $this->load_view('contest/futsal', [
            'user_nick' => $user_nick,
            'team_info' => $team_info,
            'errors'    => $errors
        ]);

        $this->load_view('skeleton_template/footer');
    }

    public function paper_presentation() {
        $user_nick = $this->auth->get_user();
        $user_details = $this->model->is_registered_for_paper_presentation($user_nick);
        $errors = [];
        if (!$user_details && $_SERVER['REQUEST_METHOD'] === 'POST') {
            required_post_params(['contact_number', 'paper_link'], $errors);
            if (!empty($_POST['contact_number']) && !is_valid_phone_number($_POST['contact_number']) ) {
                $errors['contact_number'] = 'Please enter a valid phone number';
            }
            if (!empty($_POST['paper_link']) && !is_valid_url($_POST['paper_link']) ) {
                $errors['paper_link'] = 'Please enter a valid link';
            }
            if (!$errors) {
                $user_details = [
                    'nick'              => $user_nick,
                    'contact_number'    => $_POST['contact_number'],
                    'paper_link'        => $_POST['paper_link'],
                ];
                $success = $this->model->register_for_paper_presentation($user_details);
                if (!$success) {
                    $errors['common'] = 'Some unexpected error occured';
                }
            }
        }
        $this->load_view('skeleton_template/header', [
            'title'             => __('Register').' · '.__('Paper Presentation'),
            'is_authenticated'  => true,
            'user_nick'         => $user_nick,
        ]);
        $this->load_view('contest/paper_presentation', [
            'user_details'  => $user_details,
            'errors'        => $errors
        ]);
        $this->load_view('skeleton_template/footer');
    }

    private function go_to_webdev_workshop_payment($workshop_user_details) {
        $user_details = $this->auth->get_user_details();
        global $payment_cfg;
        $data = [
            'nick'  => $user_details['nick'],
            'name'  => $user_details['name'],
            'email' => $user_details['mail'],
            'phone' => $workshop_user_details['contact_number'],
            'hash'  => hash('sha256', implode('|', [
                $user_details['nick'],
                $user_details['name'],
                $user_details['mail'],
                $workshop_user_details['contact_number'],
                $payment_cfg['salt'],
            ]))
        ];
        $query_str = http_build_query($data);
        $this->load_library('http_lib', 'http');
        $this->http->redirect( $payment_cfg['url'] . '?' . $query_str );
    }

    public function webdev_workshop() {
        $user_nick = $this->auth->get_user();
        $user_details = $this->model->is_registered_for_webdev($user_nick);
        if ($user_details) {
            $this->go_to_webdev_workshop_payment($user_details);
        }
        else {
            $errors = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                required_post_params(['contact_number', 'stream', 'year', 'experience', 'why_join'], $errors);
                if (!empty($_POST['contact_number']) && !is_valid_phone_number($_POST['contact_number']) ) {
                    $errors['contact_number'] = 'Please enter a valid phone number';
                }
                if (!$errors) {
                    $user_details = [
                        'nick'              => $user_nick,
                        'contact_number'    => $_POST['contact_number'],
                        'stream'            => $_POST['stream'],
                        'year'              => $_POST['year'],
                        'experience'        => $_POST['experience'],
                        'why_join'          => $_POST['why_join'],
                    ];
                    if ($this->model->register_for_webdev($user_details)) {
                        $this->go_to_webdev_workshop_payment($user_details);
                    } else {
                        $errors['common'] = __('Some unexpected error occurred');
                    }
                }
            }
            $this->load_view('skeleton_template/header', [
                'title'             => __('Register').' · '.__('Web development Workshop'),
                'is_authenticated'  => true,
                'user_nick'         => $user_nick,
            ]);

            $this->load_view('contest/webdev_workshop', [
                'user_nick' => $user_nick,
                'errors'    => $errors
            ]);
            $this->load_view('skeleton_template/footer');
        }
    }

    public function breakin() {
        $user_nick = $this->auth->get_user();

        $this->load_model('breakin_model');
        $team_info = $this->breakin_model->get_team_info($user_nick);
        $errors = [];
        if (!$team_info && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $team_name = null;
            $teammate_nick = null;
            if (isset($_POST['teammate_nick']) && trim($_POST['teammate_nick'])) {
                $this->load_model('auth_model');
                if ($_POST['teammate_nick'] == $user_nick) {
                    $errors['teammate_nick'] = "<pre>¯\_(ツ)_/¯</pre>";
                } else {
                    $teammate = $this->auth_model->get_user_by_nick($_POST['teammate_nick']);
                    if (!empty($teammate)
                        && isset($teammate["resitration_status"]) && $teammate["resitration_status"] == "complete"
                        && isset($teammate["email_verified"])     && $teammate["email_verified"]
                    ) {
                        $other_team = $this->breakin_model->get_team_info($teammate['nick']);
                        if ($other_team) {
                            $errors['teammate_nick'] = "This person is already registered in some other team";
                        } else {
                            $teammate_nick = $_POST['teammate_nick'];
                        }
                    } else {
                        $errors['teammate_nick'] = "Please enter a valid <strong>registered user's</strong> nick";
                    }
                }
            }

            if (isset($_POST['team']) && trim($_POST['team'])) {
                if (strlen(trim($_POST['team'])) < 4 || !preg_match('/^[a-z0-9_]+$/i', trim($_POST['team']))) {
                    $errors['team'] = "Team name should be at least 4 characters long and can have only alphanumeric characters or underscore";
                } elseif ($this->breakin_model->does_team_exists($_POST['team'])) {
                    $errors['team'] = "This team name is already taken";
                } else {
                    $team_name = trim($_POST['team']);
                }
            } else {
                $errors['team'] = "Please enter a valid team name";
            }

            if (count($errors) == 0) {
                $team_info = [
                    'team_name' => $team_name,
                    'nick1'     => $user_nick,
                    'nick2'     => $teammate_nick
                ];
                $this->breakin_model->register($team_info);
                if ($teammate_nick) {
                    $this->load_library('email_lib');
                    $mail = $this->email_lib->compose_mail('noreply_threads');
                    $this->email_lib->set_html_view($mail, 'contest/breakin_email_html', $team_info);
                    $this->email_lib->set_text_view($mail, 'contest/breakin_email_text', $team_info);
                    $this->email_lib->send_mail($mail, [
                        'to_email'  => $teammate['mail'],
                        'to_name'   => $teammate['name'],
                        'subject'   => "Break In CTF Registration",
                    ]);
                }
            }
        }

        $this->load_view('skeleton_template/header', [
            'title'             => __('Register').' · '.__('Breakin'),
            'is_authenticated'  => true,
            'user_nick'         => $user_nick,
        ]);

        $this->load_view('contest/breakin', [
            'user_nick' => $user_nick,
            'team_info' => $team_info,
            'errors'    => $errors
        ]);

        $this->load_view('skeleton_template/footer');
    }
}
