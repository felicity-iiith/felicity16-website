<?php

class page extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("auth_lib", "auth");

        $this->load_model("jugaad_model");
        $this->load_model("template_model");
        $this->load_model("perms_model");

        $this->user = $this->auth->get_user() ?: "";
    }

    function show($api = null) {
        $path = func_get_args();

        if ($api == "api") {
            $path = array_slice($path, 1);
        }

        $file_id = $this->jugaad_model->get_path_id($path);
        $file_type = $this->jugaad_model->get_file_type($file_id);

        if (end($path) == 'index') {
            $this->http->redirect(
                locale_base_url() . implode("/", array_slice($path, 0, -1)) . "/"
            );
        }

        // Check if index exists
        if ($file_type == 'directory') {
            $file_id = $this->jugaad_model->get_slug_id($file_id, 'index');
        }

        if ($file_id === false) {
            $this->http->response_code(404);
        }

        $file = $this->jugaad_model->get_file($file_id);

        $file["user_can"] = $this->perms_model->get_permissions($file_id, $this->user);

        if (!$file["user_can"]["read_file"]) {
            $this->http->response_code(404);
        }

        $template_meta = $this->template_model->get_meta($file["template"]);
        if ($template_meta === false) {
            $this->http->response_code(404);
        }

        if ($api == "api") {
            foreach ($template_meta as $name => $meta) {
                if (isset($meta["inApi"]) && $meta["inApi"] === false) {
                    unset($template_meta[$name]);
                }
            }

            $latest_version = $this->jugaad_model->get_latest_version_id_with_external_data($file_id, $template_meta, $this->user);

            if (!empty($_GET["prev_id"]) && $_GET["prev_id"] == $latest_version) {
                $this->http->response_code(304, false);
                exit();
            }

            $data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, true);

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                "version_id" => $latest_version,
                "page_data" => $data
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $is_ajax = isset($_SERVER["HTTP_X_AJAX_REQUEST"]) && $_SERVER["HTTP_X_AJAX_REQUEST"];

            if ($is_ajax) {
                foreach ($template_meta as $name => $meta) {
                    if (isset($meta["inAjax"]) && $meta["inAjax"] === false) {
                        unset($template_meta[$name]);
                    }
                }
            }

            $data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, true);

            $data["is_ajax"] = $is_ajax;

            $data["is_authenticated"] = $this->auth->is_authenticated();
            $data["user_nick"] = $this->auth->get_user();

            $view_name = $this->template_model->get_view_name($file["template"]);

            $data["page_slug"] = implode('__', $path);

            $this->load_view($view_name, $data);
        }
    }

    function locale_dump() {
        $strings = [];

        $files = $this->jugaad_model->get_file_data(0, [
            'files' => [
                'type' => 'external',
                'path' => '**',
                'data' => []
            ]
        ], $this->user);

        $files = $files['files'];

        foreach ($files as $file) {
            $template_meta = $this->template_model->get_meta($file["template"]);

            if ($template_meta === false) {
                continue;
            }

            $translatable_types = ['text', 'longtext'];

            foreach ($template_meta as $name => $meta) {
                if (!(in_array($meta['type'], $translatable_types)
                    || ($meta['type'] == 'list' && in_array($meta['listType'], $translatable_types)))
                ) {
                    unset($template_meta[$name]);
                }
            }

            $file_id = $this->jugaad_model->get_path_id(explode('/', $file['path']));
            $data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, true);

            foreach ($data as $value) {
                if (!$value) continue;
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $strings[] = $val;
                    }
                } else {
                    $strings[] = $value;
                }
            }
        }

        header('Content-Type: text/plain; charset=UTF-8');

        echo "<?php
/**
 * Save this code in src/locale/locale_dump.php
 * After saving this scan src/ with poedit to start translating!
 */

";
        foreach ($strings as $string) {
            echo '__("' . addslashes($string) . '");' . "\n";
        }
    }

}
