<?php

class ajax extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("auth_lib", "auth");

        $this->load_model("jugaad_model");
        $this->load_model("perms_model");
        $this->load_model("auth_model");

        $this->user = $this->auth->get_user() ?: "";
    }

    /**
     * @param string $param Get GET parameter called $param
     * @return mixed GET parameter or false if not found
     */
    private function get_param($param) {
        if (isset($_GET[$param])) {
            return $_GET[$param];
        }
        return false;
    }

    function latest_version_id() {
        $file_id = $this->get_param("file_id");

        $file = $this->jugaad_model->get_file($file_id);

        if ($file === false || $file["type"] != "file") {
            $this->http->response_code(404);
        }

        $user_can = $this->perms_model->get_permissions($file_id, $this->user);

        if (!$user_can['read_file']) {
            $this->http->response_code(403);
        }

        $version_id = $this->jugaad_model->get_latest_version_id($file_id);

        echo $version_id;
    }

    function latest_version() {
        $file_id = $this->get_param("file_id");

        $file = $this->jugaad_model->get_file($file_id);

        if ($file === false || $file["type"] != "file") {
            $this->http->response_code(404);
        }

        $user_can = $this->perms_model->get_permissions($file_id, $this->user);

        if (!$user_can['read_file']) {
            $this->http->response_code(403);
        }

        $file["data"] = $this->jugaad_model->get_file_data($file_id);

        echo json_encode($file, false);
    }

}
