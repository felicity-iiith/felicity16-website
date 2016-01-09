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

        $mail = new PHPMailer();

        $from = $email_cfg['accounts'][$from_account];
        if (!empty($from_name)) {
            $from['from_name'] = $from_name;
        }

        $mail->isSMTP();
        $mail->Host       = $email_cfg['server_host'];
        $mail->Hostname   = $email_cfg['server_domain'];
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = $email_cfg['server_port'];
        $mail->Username   = $from['username'];
        $mail->Password   = $from['password'];
        $mail->isHTML(true);
        $mail->Encoding   = 'quoted-printable';
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($from['email'], $from['from_name']);

        return $mail;
    }

    public function set_html_view($mail, $html_view, $data) {
        $mail->Body = $this->get_view_output($html_view, $data);
    }

    public function set_text_view($mail, $text_view, $data) {
        $mail->AltBody = $this->get_view_output($text_view, $data);
    }

    public function send_mail($mail, $email_data) {
        $to_name = isset($email_data['to_name']) ? $email_data['to_name'] : "";
        $mail->addAddress($email_data['to_email'], $to_name);
        $mail->Subject = $email_data['subject'];
        if (isset($email_data['html_body'])) {
            $mail->Body = $email_data['html_body'];
        }
        if (isset($email_data['alt_body'])) {
            $mail->AltBody = $email_data['alt_body'];
        }
        return $mail->send();
    }
}
