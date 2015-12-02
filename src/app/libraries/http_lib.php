<?php

class http_lib extends Library {

    function response_code($code, $view_and_exit = true, $view_name = false) {
        http_response_code($code);
        if ($view_and_exit) {
            if (empty($view_name)) {
                $view_name = strval($code);
            }
            $this->load_view($view_name);
            exit();
        }
    }

    function redirect($url, $status_code = 302) {
        header('Location: ' . $url, true, $status_code);
        exit();
    }

    function canonical_of($url) {
        header('Link: <' . $url . '>; rel="canonical"');
    }

}
