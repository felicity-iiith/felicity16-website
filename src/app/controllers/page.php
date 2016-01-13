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
                base_url() . implode("/", array_slice($path, 0, -1)) . "/"
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

            $data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, true);

            echo json_encode([
                "version_id" => $this->jugaad_model->get_latest_version_id_with_external_data($file_id, $template_meta, $this->user),
                "page_data" => $data
            ]);
        } else {
            $header = getallheaders();
            $is_ajax = isset($header["X-Ajax-Request"]) && $header["X-Ajax-Request"];

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

}
