<?php

class page extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("cas_lib", "cas");

        $this->load_model("jugaad_model");
        $this->load_model("template_model");
        $this->load_model("perms_model");

        $this->user = $this->cas->getUser() ?: "";
    }

    function show() {
        $path = func_get_args();

        $file_id = $this->jugaad_model->get_path_id($path);
        $file_type = $this->jugaad_model->get_file_type($path);

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

        $view_name = $this->template_model->get_view_name($file["template"]);

        $this->load_view($view_name, $data);
    }

}
