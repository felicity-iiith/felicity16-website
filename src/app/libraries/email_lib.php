<?php

/**
 * Email Library
 */
class email_lib extends Library {

    private function get_view_output($view_name, $data) {
        ob_start();
        $this->load_view($view_name, $data);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function compose_mail($from_account, $from_name = null) {
        global $email_cfg;

        $mailer = new PHPMailer();

        if (!isset($email_cfg['server_host'])
            || !isset($email_cfg['server_domain'])
            || !isset($email_cfg['server_port'])
            || !isset($email_cfg['accounts'])
            || !isset($email_cfg['accounts'][$from_account])
            || !isset($email_cfg['accounts'][$from_account]['username'])
            || !isset($email_cfg['accounts'][$from_account]['password'])
            || !isset($email_cfg['accounts'][$from_account]['email'])
        ) {
            return false;
        }

        $from = $email_cfg['accounts'][$from_account];
        if (!empty($from_name)) {
            $from['from_name'] = $from_name;
        }

        $mailer->isSMTP();
        $mailer->Host       = $email_cfg['server_host'];
        $mailer->Hostname   = $email_cfg['server_domain'];
        $mailer->SMTPAuth   = true;
        $mailer->SMTPSecure = "tls";
        $mailer->Port       = $email_cfg['server_port'];
        $mailer->Username   = $from['username'];
        $mailer->Password   = $from['password'];
        $mailer->isHTML(true);
        $mailer->Encoding   = 'quoted-printable';
        $mailer->CharSet    = 'UTF-8';

        $mailer->setFrom($from['email'], $from['from_name']);

        return $mailer;
    }

    public function set_html_view($mail, $html_view, $data) {
        $mail->Body = $this->get_view_output($html_view, $data);
    }

    public function set_text_view($mail, $text_view, $data) {
        $mail->AltBody = $this->get_view_output($text_view, $data);
    }

    public function send_mail($mail, $email_data) {
        $mail->addAddress($email_data['to_email'], $email_data['to_name']);
        $mail->Subject = $email_data['subject'];
        if (isset($email_data['html_body'])) {
            $mail->Body    = $email_data['html_body'];
        }
        if (isset($email_data['alt_body'])) {
            $mail->AltBody = $email_data['alt_body'];
        }
        return $mail->send();
    }
}
