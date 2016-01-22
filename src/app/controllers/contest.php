<?php

class contest extends Controller {

    public function __construct() {
        $this->load_library('auth_lib', 'auth');
        $this->auth->force_authentication();
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
                if (strlen(trim($_POST['team'])) < 6 || !preg_match('/^[a-z0-9_]+$/i', trim($_POST['team']))) {
                    $errors['team'] = "Team name should be at least 6 characters long and can have only alphanumeric characters or underscore";
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
