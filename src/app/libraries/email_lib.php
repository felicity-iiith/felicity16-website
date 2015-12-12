<?php

/**
 * Email Library
 */
class email_lib extends Library {

    public function __construct() {
        global $email_cfg;

        $this->mailer = new PHPMailer();

        $this->mailer->isSMTP();
        $this->mailer->Host       = $email_cfg['server_host'];
        $this->mailer->Hostname   = $email_cfg['server_domain'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->SMTPSecure = "tls";
        $this->mailer->Port       = $email_cfg['server_port'];
        $this->mailer->Username   = $email_cfg['username'];
        $this->mailer->Password   = $email_cfg['password'];
        $this->mailer->isHTML(true);
        $this->mailer->Encoding = 'quoted-printable';
    }

    public function send_mail($email_data) {

        $this->mailer->ClearAddresses();
        
        $this->mailer->setFrom($email_data['from_email'], $email_data['from_name']);
        $this->mailer->addAddress($email_data['to_email'], $email_data['to_name']);
        $this->mailer->Subject = $email_data['subject'];
        $this->mailer->Body    = $email_data['html_body'];
        $this->mailer->AltBody = $email_data['alt_body'];
        return $this->mailer->send();
    }
}
