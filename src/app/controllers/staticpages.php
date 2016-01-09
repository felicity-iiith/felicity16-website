<?php
class staticpages extends Controller {
    public function view($view = false) {
        $user_data = array();
        $user_data['mail'] = 'megh@gmail.com';
        $user_data['gender'] = "male";
        $path = func_get_args();
        $this->load_view("auth/$view", [
            'user_data' => $user_data,
            'is_ajax'   => false,
            "page_slug" => implode('__', $path),
            "error" => "Unable to connect.",
            'sent' => false,
            "hash" => "1234",
            "success" => false,
        ]);
    }
}
