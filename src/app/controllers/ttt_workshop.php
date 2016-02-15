<?php

class ttt_workshop extends Controller {

    public function __construct() {
        $this->load_library('auth_lib', 'auth');
        $this->load_model('contest_model', 'model');
        load_helper('validations');
    }

    private function get_ttt_payment_url($nick, $phone) {
        global $payment_cfg;
        $user_details = $this->auth->get_user_details();

        $name  = $user_details['name'];
        $email = $user_details['mail'];

        $salt  = $payment_cfg['ttt']['salt'];
        $url   = $payment_cfg['ttt']['gateway_url'];
        $nick_field = $payment_cfg['ttt']['nick_field'];

        $sign  = implode('|', [$email, $name, $phone]);
        $hash  = hash_hmac('sha1', $sign, $salt);

        $data = [
            'data_sign'         => $hash,
            'data_name'         => $name,
            'data_email'        => $email,
            'data_phone'        => $phone,
            $nick_field         => $nick,
            'data_hidden'       => $nick_field,
        ];
        $readonly_str = 'data_readonly=data_name&data_readonly=data_email&data_readonly=data_phone';
        $query_str = http_build_query($data);

        return $url . '&' . $query_str . '&' . $readonly_str;
    }

    public function register() {
        $this->auth->force_authentication();
        $user_nick = $this->auth->get_user();
        $errors = [];
        $user_details = $this->model->is_registered_for_ttt($user_nick);
        if (!$user_details && $_SERVER['REQUEST_METHOD'] === 'POST'){
            required_post_params([
                'contact_number',
            ], $errors);
            if (!empty($_POST['contact_number']) && !is_valid_phone_number($_POST['contact_number']) ) {
                $errors['contact_number'] = 'Please enter a valid phone number';
            }
            if (!$errors) {
                $success = $this->model->register_for_ttt(
                    $user_nick,
                    $_POST['contact_number']
                );
                if ($success) {
                    $redirect_url = $this->get_ttt_payment_url($user_nick, $_POST['contact_number']);
                    $this->load_library('http_lib', 'http');
                    $this->http->redirect( $redirect_url );
                }
                else {
                    $errors['common'] = 'Some unexpected error occured';
                }
            }
        }
        $payment_url = $this->get_ttt_payment_url($user_nick, $user_details['contact_number']);
        $this->load_view('skeleton_template/header', [
            'title'             => __('Register').' · '.__('Terribly Tiny Tales Workshop'),
            'is_authenticated'  => true,
            'user_nick'         => $user_nick,
        ]);

        $this->load_view('contest/ttt_workshop', [
            'user_nick'     => $user_nick,
            'user_details'  => $user_details,
            'payment_url'   => $payment_url,
            'errors'        => $errors
        ]);

        $this->load_view('skeleton_template/footer');
    }

    public function success() {
        $this->load_library('http_lib', 'http');
        if (!isset($_GET['payment_id'])) {
            $this->http->response_code( 400 );
            exit();
        }
        global $payment_cfg;

        $id  = urlencode($_GET['payment_id']);
        $url = $payment_cfg['ttt']['api_url'] . $id . '/';

        $ch  = curl_init();
        //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $payment_cfg['ttt']['api_headers']);
        $response = curl_exec($ch);
        $curl_error = curl_errno($ch);
        curl_close($ch);
        $response_array = json_decode($return, true);
        if (@$response_array['success']) {
            $nick = $response_array['payment']['custom_fields']['Field_12972']['value'];
            $status = $response_array['payment']['status'];
            $payment_id = $_GET['payment_id'];
            $payment_data = $response;

            $this->model->ttt_payment_success($payment_id,
                $nick,
                $status == 'Credit' ? 'success' : 'failed',
                $payment_data
            );
        }
        $this->http->redirect(base_url() . "litcafe/ttt-workshop/register/");
    }

    public function webhook() {
        global $payment_cfg;

        $nick_field = $payment_cfg['ttt']['nick_field'];
        if (isset($_POST['custom_fields'][$nick_field])) {
            $nick = $_POST['custom_fields'][$nick_field]['value'];
            $this->model->ttt_dump_data($nick, 'webhook', json_encode($_POST));
        }
    }
}
