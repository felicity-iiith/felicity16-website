<?php

class http_lib extends Library {
    function response_code($code, $view_and_exit = true, $view_name = false) {
        http_response_code($code);
        if ($view_and_exit) {
            if (empty($view_name)) {
                $view_name = strval($code);
            }
            // Check if a dedicated view exists; if not, load common error view
            $dedicated_view = $this->load_view($view_name, [
                'error_code' => $code,
            ]);
            if (! $dedicated_view) {
                $this->load_view('http_error', [
                    'error_code' => $code,
                ]);
            }
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
