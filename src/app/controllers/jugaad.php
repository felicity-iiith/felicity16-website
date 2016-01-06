<?php

class jugaad extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("auth_lib", "auth");
        $this->auth->force_authentication();

        $this->load_model("jugaad_model");
        $this->load_model("template_model");
        $this->load_model("perms_model");

        $this->user = $this->auth->get_user();
    }

    private function is_slug_valid($slug) {
        return preg_match('/^[a-z0-9-_]+$/i', $slug);
    }

    private function handle_save_file($file) {
        if (!empty($_POST["save"]) && isset($_POST["file_id"])
            && (!empty($_POST["slug"]) || $_POST["file_id"] == 0)
        ) {
            $file_id = $_POST["file_id"];
            $slug = htmlspecialchars(@$_POST["slug"] ?: "");
            $data = @$_POST["data"] ?: array();
            $template = $_POST["template"];
            $version_id = @$_POST["version_id"] ?: 0;

            if ($slug && !$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $latest_version = $this->jugaad_model->get_latest_version_id($file_id);
            if ($latest_version > $version_id) {
                return "Cannot save. Someone else also edited the file";
            }

            if ($file["type"] == "file") {
                $template_meta = $this->template_model->get_meta($file["template"]);
                $orig_data = $this->jugaad_model->get_file_data($file_id, $template_meta, $this->user, false);

                // Clean data
                foreach ($data as $key => $value) {
                    if (array_key_exists($key, $orig_data)) {
                        if ($data[$key] == $orig_data[$key]) {
                            unset($data[$key]);
                        }
                    }
                }
            }

            $save = $this->jugaad_model->update_file($file_id, $slug, $data, $template, $this->user);
            if ($save === false) {
                return "Could not save file";
            }

            $path = $this->jugaad_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "jugaad" . $path . "?edit");
        } else {
            return false;
        }
    }

    private function handle_add_file($file) {
        if (!empty($_POST["add"]) && isset($_POST["parent_id"])
            && !empty($_POST["slug"])
        ) {
            $parent_id = $_POST["parent_id"];
            $slug = $_POST["slug"];
            $type = $_POST["type"];
            $template = $_POST["template"];
            $default_role = $_POST["default_role"];

            if (!$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $add = $this->jugaad_model->new_file($parent_id, $slug, $type, $default_role, $template, $this->user);
            if ($add === false) {
                return "Could not add file";
            }

            $path = $this->jugaad_model->get_file_path($parent_id) . $slug . "/";
            $this->http->redirect(base_url() . "jugaad" . $path . "?edit");
        } else {
            return false;
        }
    }

    private function handle_update_default_role($file) {
        if (!empty($_POST["update_default_role"]) && isset($_POST["file_id"])) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $default_role = $_POST["default_role"];

            if (false === $this->perms_model->set_default_role($file_id, $default_role)) {
                return "Could not update default role";
            }

            $path = $this->jugaad_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "jugaad" . $path . "?edit#useredit");
        } else {
            return false;
        }
    }

    private function handle_add_user($file) {
        if (!empty($_POST["add_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $username = $_POST["username"];
            $role = $_POST["role"];

            $add = $this->perms_model->add_user_role($file_id, $username, $role);
            if ($add === false) {
                return "Could not add user";
            }

            $path = $this->jugaad_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "jugaad" . $path . "?edit#useredit");
        } else {
            return false;
        }
    }

    private function handle_revoke_user($file) {
        if (!empty($_POST["revoke_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $username = $_POST["username"];

            $add = $this->perms_model->remove_user_role($file_id, $username);
            if ($add === false) {
                return "Could not revoke permissions for user";
            }

            $path = $this->jugaad_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "jugaad" . $path . "?edit#useredit");
        } else {
            return false;
        }
    }

    private function handle_delete_file($file) {
        if (!empty($_POST["delete_file"]) && isset($_POST["file_id"])) {
            $file_id = $_POST["file_id"];
            $file = $this->jugaad_model->get_file($file_id);
            $parent_id = @$file['parent'] ?: 0;
            $file_type = @$file['type'] ?: false;

            if ($file_type == 'directory') {
                $file_list = $this->jugaad_model->get_directory($file_id);
                if (count($file_list)) {
                    return "Cannot delete non-empty directory";
                }
            }

            $delete = $this->jugaad_model->delete_file($file_id, $this->user);
            if ($delete === false) {
                return "Could not delete " . $file_type;
            }

            $path = $this->jugaad_model->get_file_path($parent_id);
            $this->http->redirect(base_url() . "jugaad" . $path);
        } else {
            return false;
        }
    }

    private function show_file_edit($file) {
        $file["template_meta"] = $this->template_model->get_meta($file["template"]);
        $file["data"] = $this->jugaad_model->get_file_data($file['id'], $file["template_meta"], $this->user, false);

        $this->load_view("file_edit", $file);
    }

    private function handle_edit_action($file) {
        return $this->handle_save_file($file)
            ?: $this->handle_add_file($file)
            ?: $this->handle_update_default_role($file)
            ?: $this->handle_add_user($file)
            ?: $this->handle_revoke_user($file)
            ?: $this->handle_delete_file($file);
    }

    private function handle_edit($file_id, $file) {
        if (!$this->user_can['write_file']) {
            $this->http->response_code(403);
        }

        $file_type = $file ? $file['type'] : false;

        $error = $this->handle_edit_action($file);

        $file["error"] = $error;
        $file["admins"] = $this->perms_model->get_user_list($file_id);
        $file["user"] = $this->user;
        $file["user_can"] = $this->user_can;
        $file["version_id"] = $this->jugaad_model->get_latest_version_id($file_id);

        $file["templates"] = $this->template_model->get_template_list();

        if ($file_type == "directory") {
            $this->load_view("directory_edit", $file);
        } elseif ($file_type == "file") {
            $this->show_file_edit($file);
        } else {
            $this->http->response_code(404);
        }
    }

    private function handle_history($file_id, $file) {
        if (!$this->user_can['read_file']) {
            $this->http->response_code(403);
        }

        $file_type = $file ? $file['type'] : false;

        if ($file_type == "file") {
            $file["history"] = $this->jugaad_model->get_history($file_id);

            $file["user_can"] = $this->user_can;
            if ($this->user_can["see_history_detail"] && isset($_GET["id"])) {
                $edit_id = $_GET["id"];
                foreach ($file["history"] as $value) {
                    if ($value["id"] == $edit_id) {
                        $file["history_item"] = $value;
                        break;
                    }
                }
            } elseif (isset($_GET["id"])) {
                $file["perm_error"] = true;
            }

            $this->load_view("file_history", $file);
        } else {
            $this->http->response_code(404);
        }
    }

    private function handle_read($file_id, $file) {
        if (!$this->user_can['read_file']) {
            $this->http->response_code(403);
        }

        $file_type = $file ? $file['type'] : false;

        if ($file_type == "directory") {
            $file["data"] = $this->jugaad_model->get_directory($file_id);
            $file["user_can"] = $this->user_can;
            $this->load_view("directory", $file);
        } elseif ($file_type == "file") {
            $this->http->redirect('?edit');
        } else {
            $this->http->response_code(404);
        }
    }

    function read() {
        $path = func_get_args();

        $action = false;
        if (isset($_GET["edit"])) {
            $action = "edit";
        } elseif (isset($_GET["history"])) {
            $action = "history";
        }

        $file_id = $this->jugaad_model->get_path_id($path);
        if ($file_id === false) {
            $this->http->response_code(404);
        }

        $file = $this->jugaad_model->get_file($file_id);

        $this->user_can = $this->perms_model->get_permissions($file_id, $this->user);

        if ($action == 'edit') {
            $this->handle_edit($file_id, $file);
        } elseif ($action == 'history') {
            $this->handle_history($file_id, $file);
        } else {
            $this->handle_read($file_id, $file);
        }
    }

    function trash() {
        $user_can = $this->perms_model->get_permissions(0, $this->user);
        if (!$user_can['see_global_trash']) {
            $this->http->response_code(403);
        }

        $error = "";
        $msg = "";
        if (!empty($_POST["restore_file"]) && isset($_POST["file_id"])) {
            $file_id = $_POST["file_id"];
            $recovered = $this->jugaad_model->recover_file($file_id, $this->user);
            if ($recovered === false) {
                $error = "Could not recover file";
            } else {
                $_SESSION['recovered_file'] = $file_id;
                $this->http->redirect(base_url() . "trash/");
            }
        }

        if (isset($_SESSION['recovered_file'])) {
            $file_id = $_SESSION['recovered_file'];
            unset($_SESSION['recovered_file']);

            $file = $this->jugaad_model->get_file($file_id);
            if ($file !== false) {
                $msg = ucfirst($file['type']) . ' recovered. See <a href="'
                    . base_url() . 'jugaad' . $this->jugaad_model->get_file_path($file['id'])
                    . '"> recovered '
                    . $file['type'] . '</a>.';
            }
        }

        $trash_list = $this->jugaad_model->get_trash_list();
        $this->load_view('trash', [
            'files' => $trash_list,
            'error' => $error,
            'msg' => $msg
        ]);
    }

}
