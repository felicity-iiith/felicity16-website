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

    function show() {
        $path = func_get_args();

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

        $data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, true);

        $header = getallheaders();
        if (isset($header["X-Ajax-Request"]) && $header["X-Ajax-Request"]) {
            $data["is_ajax"] = true;
        } else {
            $data["is_ajax"] = false;
        }

        $view_name = $this->template_model->get_view_name($file["template"]);

        $data["page_slug"] = implode('__', $path);

        $this->load_view($view_name, $data);
    }

}
