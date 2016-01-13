<?php

/*
 This file is too big. Can be broken in multiple models.
 One containing all the file operations,
 another with all the operations related to templating and data.
 */

class jugaad_model extends Model {

    public function __construct() {
        $this->load_library("db_lib");
    }

    public function new_file($parent, $slug, $type, $default_role, $template, $user) {
        $db_error = false;
        $this->DB->jugaad->autocommit(false);

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `files` (`slug`, `parent`, `type`, `default_role`, `template`) VALUES (?, ?, ?, ?, ?)",
            "sisss",
            [$slug, $parent, $type, $default_role, $template]
        );
        if (!$stmt) {
            $db_error = true;
        }

        if (!$db_error) {
            $insert_id = $stmt->insert_id;

            $stmt = $this->db_lib->prepared_execute(
                $this->DB->jugaad,
                "INSERT INTO `file_versions` (`file_id`, `action`, `created_by`) VALUES (?, 'create', ?)",
                "is",
                [$insert_id, $user]
            );
            if (!$stmt) {
                $db_error = true;
            }
        }

        if ($db_error) {
            $this->DB->jugaad->rollback();
        } else {
            $this->DB->jugaad->commit();
        }

        $this->DB->jugaad->autocommit(true);
        return !$db_error;
    }

    public function update_file($file_id, $slug, $data, $template, $user) {
        if ($file_id === false) {
            return false;
        }
        $file_type = $this->get_file_type($file_id);
        if ($file_type === false) {
            return false;
        }
        $db_error = false;
        $this->DB->jugaad->autocommit(false);

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "UPDATE `files` SET `slug`=?, `template`=? WHERE `id`=?",
            "ssi",
            [$slug, $template, $file_id]
        );
        if (!$stmt) {
            $db_error = true;
        }

        if ($file_type != 'directory') {
            $stmt = $this->db_lib->prepared_execute(
                $this->DB->jugaad,
                "INSERT INTO `file_versions` (`file_id`, `action`, `created_by`) VALUES (?, 'edit', ?)",
                "is",
                [$file_id, $user]
            );
            if (!$stmt) {
                $db_error = true;
            }

            if (!$db_error) {
                $version_id = $stmt->insert_id;
                $db_error = $this->update_file_data($file_id, $version_id, $data);
            }
        }

        if ($db_error) {
            $this->DB->jugaad->rollback();
        } else {
            $this->DB->jugaad->commit();
        }

        $this->DB->jugaad->autocommit(true);
        return !$db_error;
    }

    public function delete_file($file_id, $user) {
        if ($file_id === false || $file_id <= 0) {
            return false;
        }

        $db_error = false;
        $this->DB->jugaad->autocommit(false);

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `trash_files` (`file_id`, `slug`, `parent`, `type`, `created_by`) SELECT `id`, `slug`, `parent`, `type`, ? FROM `files` WHERE `id`=?",
            "si",
            [$user, $file_id]
        );
        if (!$stmt) {
            $db_error = true;
        }

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "DELETE FROM `files` WHERE `id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            $db_error = true;
        }

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `file_versions` (`file_id`, `action`, `created_by`) VALUES (?, 'delete', ?)",
            "is",
            [$file_id, $user]
        );
        if (!$stmt) {
            $db_error = true;
        }

        if ($db_error) {
            $this->DB->jugaad->rollback();
        } else {
            $this->DB->jugaad->commit();
        }

        $this->DB->jugaad->autocommit(true);
        return !$db_error;
    }

    public function recover_file($file_id, $user) {
        if ($file_id === false || $file_id <= 0) {
            return false;
        }

        $db_error = false;
        $this->DB->jugaad->autocommit(false);

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `files` (`id`, `slug`, `parent`, `type`) SELECT `file_id`, `slug`, `parent`, `type` FROM `trash_files` WHERE `file_id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            $db_error = true;
        }

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "DELETE FROM `trash_files` WHERE `file_id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            $db_error = true;
        }

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `file_versions` (`file_id`, `action`, `created_by`) VALUES (?, 'recover', ?)",
            "is",
            [$file_id, $user]
        );
        if (!$stmt) {
            $db_error = true;
        }

        if ($db_error) {
            $this->DB->jugaad->rollback();
        } else {
            $this->DB->jugaad->commit();
        }

        $this->DB->jugaad->autocommit(true);
        return !$db_error;
    }

    public function get_slug_id($parent, $slug) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `id` FROM `files` WHERE `parent`=? AND `slug`=?",
            "is",
            [$parent, $slug]
        );
        if (!$stmt) {
            return false;
        }

        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    public function get_path_id($path) {
        $path = array_filter($path);
        $parent = 0;
        foreach ($path as $component) {
            $parent = $this->get_slug_id($parent, $component);
            if ($parent === false) {
                return false;
            }
        }
        return $parent;
    }

    public function get_file_path($file_id, $include_trash = false) {
        if ($file_id === false) {
            return false;
        }
        if ($file_id == 0) {
            return '/';
        }
        $path = '/';
        do {
            $file = $this->get_file($file_id, $include_trash);
            $file_id = $file['parent'];
            $path = '/' . $file['slug'] . $path;
        } while ($file_id !== 0);
        return $path;
    }

    public function get_file_type($file_id) {
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `type` FROM `files` WHERE `id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    public function get_directory($file_id, $regex = false, $type = false, $template = false) {
        // Get list of files in directory
        if ($file_id === false) {
            return false;
        }

        $query = "SELECT `id`, `slug`, `parent`, `type`, `template` FROM `files` WHERE `parent`=?";
        $param_types = "i";
        $params = [$file_id];

        if ($regex !== false) {
            $query .= " AND `slug` RLIKE ?";
            $param_types .= "s";
            $params[] = $regex;
        }

        if ($type !== false) {
            $query .= " AND `type`=?";
            $param_types .= "s";
            $params[] = $type;

            if ($type == 'file' && $template !== false) {
                $query .= " AND `template` RLIKE ?";
                $param_types .= "s";
                $params[] = $template;
            }
        } elseif ($template !== false) {
            $query .= " AND ((`type`='file' AND `template` RLIKE ?) OR `type`!='file')";
            $param_types .= "s";
            $params[] = $template;
        }

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            $query,
            $param_types,
            $params
        );
        if (!$stmt) {
            return false;
        }

        $page_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $page_list;
    }

    public function get_file($file_id, $include_trash = false) {
        // Get details of file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `id`, `slug`, `parent`, `type`, `default_role`, `template` FROM `files` WHERE `id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_assoc()) {
            return $row;
        } elseif ($include_trash && $row = $this->get_file_trashed($file_id)) {
            $row['trashed'] = true;
            return $row;
        }
        return false;
    }

    public function get_file_trashed($file_id) {
        // Get details of a trashed file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `file_id` as `id`, `slug`, `parent`, `type` FROM `trash_files` WHERE `file_id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_assoc()) {
            return $row;
        }
        return false;
    }

    /**
     * Recursively get files
     * @param  Array  $parent_dir Assosiative array containing atleast `id` and `path`
     * @param  string $type       Type (e.g. 'directory' or 'file')
     * @param  string $template   Regex for template name
     * @return array              Array of files
     */
    private function get_files_recursive($parent_dir, $type = false, $template = false) {
        $files = $this->get_directory(
            $parent_dir["id"],
            false,
            ($type == 'directory') ? 'directory' : false,
            $template
        );

        $return_files = [];

        if ($files) {
            foreach ($files as $file) {
                $file["path"] = $parent_dir["path"] . $file["slug"] . "/";

                if ($type === false || $type == $file["type"]) {
                    $return_files[] = $file;
                }

                if ($file["type"] == "directory") {
                    $return_files = array_merge(
                        $return_files,
                        $this->get_files_recursive($file, $type, $template)
                    );
                }
            }
        }

        return $return_files;
    }

    /**
     * Get all files satisfying regex path
     * @param  string $path     Path string
     * @param  string $template Template regex
     * @return array            Array of files
     */
    private function expand_regex_paths($path, $template = false) {
        $path = array_values(array_filter(explode("/", $path)));
        if (is_string($template)) {
            $template = "^" . $template . "$";
        }

        // Directories still satisfying the regex
        // Start with root
        $directories = [
            [
                "id" => 0,
                "path" => "/"
            ]
        ];

        $len = count($path);
        $last_segment = false;

        $files = [];

        for ($i = 0; $i < $len; $i++) {
            // Check if it is the last segment
            if ($i == $len - 1) {
                $last_segment = true;
            }

            $files = [];

            foreach ($directories as $parent) {
                $query_type = $last_segment ? "file" : "directory";
                $query_template = $last_segment ? $template : false;

                if ($path[$i] == "**") {
                    $new_files = $this->get_files_recursive(
                        $parent,
                        $query_type,
                        $query_template
                    );
                } else {
                    $new_files = $this->get_directory(
                        $parent["id"],
                        "^" . $path[$i] . "$",
                        $query_type,
                        $query_template
                    );
                }

                if (!$new_files) {
                    continue;
                }

                foreach ($new_files as $file) {
                    if (!isset($file["path"])) {
                        $file["path"] = $parent["path"] . $file["slug"] . "/";
                    }
                    array_push($files, $file);
                }
            }

            if (!$last_segment) {
                $directories = $files;
            }
        }

        return $files;
    }

    private function eval_regex_preprocessor($preprocessor, $file_info) {
        $pre_data = [];
        $pre_data["path"] = array_filter(explode("/", $file_info["path"]));
        $pre_data["template"] = $file_info["template"];

        // Remove `{{` and `}}`
        $location = substr($preprocessor, 2, -2);

        // Get variable name and offsets
        if (preg_match('/^([a-z0-9_]+)(\[(-?\d+)?(?:([:])(-?\d+)?)?\])?$/i', $location, $matches)) {
            $identifier = (count($matches) >= 2) ? $matches[1] : false;

            $is_array_access = (count($matches) >= 3 && $matches[2]) ? true : false;
            $start_index = (count($matches) >= 4) ? intval($matches[3]) : 0;
            $is_array_range = (count($matches) >= 5 && $matches[4]) ? true : false;
            $end_index = (count($matches) >= 6) ? intval($matches[5]) : null;

            if (!array_key_exists($identifier, $pre_data)) {
                // Something wrong
                return "";
            }

            $ret_value = $pre_data[$identifier];
            $is_ret_array = is_array($ret_value);

            if (!$is_ret_array) {
                $ret_value = str_split($ret_value);
            }

            if ($is_array_access) {
                if ($is_array_range) {
                    if ($end_index !== null && $end_index < 0) {
                        $array_length = count($ret_value);
                        $end_index = $array_length + $end_index - 1;
                    }
                    $length = ($end_index === null) ? null : ($end_index - $start_index + 1);
                    if ($length < 0) {
                        $length = 0;
                    }
                } else {
                    $length = 1;
                }

                $ret_value = array_slice($ret_value, $start_index, $length);
            }

            if ($is_ret_array) {
                $ret_value = implode("/", $ret_value);
            } else {
                $ret_value = implode("", $ret_value);
            }

            return $ret_value;
        }

        return "";
    }

    private function preprocess_regex($regex, $file_info) {
        while (preg_match('/\{\{.*\}\}/i', $regex, $matches)) {
            $replacement = $this->eval_regex_preprocessor($matches[0], $file_info);
            $regex = str_replace($matches[0], $replacement, $regex);
        }

        return $regex;
    }

    private function get_external_data($file_id, $meta, $user, $version_id_only = false) {
        if (empty($meta["path"])) {
            return false;
        }

        $path = $meta["path"];
        $data = $meta["data"];
        $template = isset($meta["template"]) ? $meta["template"] : false;

        $current_file = $this->get_file($file_id);
        $current_file["path"] = $this->get_file_path($file_id);
        $path = $this->preprocess_regex($path, $current_file);
        $template = $this->preprocess_regex($template, $current_file);

        // Get external files
        $files = $this->expand_regex_paths($path, $template);

        $this->load_model("perms_model");

        // Get data for external files
        if ($version_id_only === true) {
            $ext_data = 0;
        } else {
            $ext_data = [];
        }

        foreach ($files as $file) {
            // Check file permission and discard if user does not have enough permissions
            $user_can = $this->perms_model->get_permissions($file["id"], $user);
            if (!$user_can['read_file']) {
                continue;
            }

            if ($version_id_only === true) {
                $ext_data = max($ext_data, $this->get_latest_version_id($file["id"]));
            } else {
                $ext_file = [];
                $ext_file["slug"] = $file["slug"];
                $ext_file["path"] = $file["path"];
                $ext_file["template"] = $file["template"];
                // TODO: $ext_file["url"] to acount for cannonical paths, e.g. index
                $ext_file["data"] = [];

                foreach ($data as $name => $ext_name) {
                    $ext_file["data"][$name] =
                        $this->get_field_value($file["id"], $ext_name, false, false);
                }

                $ext_data[] = $ext_file;
            }
        }

        return $ext_data;
    }

    private function get_field_value($file_id, $name, $meta = false, $user = false) {
        if (!empty($meta) && $meta["type"] == "external") {
            return $this->get_external_data($file_id, $meta, $user);
        }

        // Else, it is normal data, get it from database
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `value` FROM `file_data` WHERE `file_id`=? AND `name`=? ORDER BY `id` DESC LIMIT 1",
            "is",
            [$file_id, $name]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0] ? json_decode($row[0], true) : $row[0];
        }
        return false;
    }

    public function get_file_data($file_id, $template_meta, $user, $return_default = true) {
        // Get data for file based on template meta given
        if ($file_id === false || !is_array($template_meta)) {
            return false;
        }
        $data = [];
        foreach ($template_meta as $name => $meta) {
            $field = $this->get_field_value($file_id, $name, $meta, $user);
            if ($field === false) {
                if ($return_default) {
                    if (isset($meta['optional']) && $meta['optional']) {
                        $data[$name] = @$meta['default'] ?: '';
                    } else {
                        $data[$name] = @$meta['default'] ?: $meta['name'];
                    }
                } else {
                    $data[$name] = '';
                }
            } else {
                $data[$name] = $field;
            }
        }
        return $data;
    }

    private function update_file_data($file_id, $version_id, $data) {
        $db_error = false;
        $name = "";
        $value = "";
        $stmt = $this->DB->jugaad->prepare("INSERT INTO `file_data` (`file_id`, `version_id`, `name`, `value`) VALUES (?, ?, ?, ?)");
        if (!$stmt->bind_param("iiss", $file_id, $version_id, $name, $value)) {
            $db_error = true;
        }
        foreach ($data as $name => $val) {
            $value = json_encode($val);
            if (!$stmt->execute()) {
                $db_error = true;
            }
        }
        return $db_error;
    }

    public function get_latest_version_id_with_external_data($file_id, $template_meta, $user) {
        if ($file_id === false || !is_array($template_meta)) {
            return false;
        }

        $version_id = $this->get_latest_version_id($file_id);

        $data = [];
        foreach ($template_meta as $name => $meta) {
            if ($meta["type"] == "external") {
                $version_id = max(
                    $version_id,
                    $this->get_external_data($file_id, $meta, $user, true)
                );
            }
        }
        return $version_id;
    }

    public function get_latest_version_id($file_id) {
        // Get latest version id
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `id` FROM `file_versions` WHERE `file_id`=? ORDER BY `id` DESC LIMIT 1",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    public function get_history($file_id) {
        // Get list of edits for file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `id`, `action`, `timestamp`, `created_by` FROM `file_versions` WHERE `file_id`=? ORDER BY `id` DESC",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        $history_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_list;
    }

    public function get_trash_list() {
        // Get list of files in trash
        $stmt = $this->DB->jugaad->prepare("SELECT `id`, `file_id`, `slug`, `parent`, `type`, `timestamp`, `created_by` FROM `trash_files` ORDER BY `timestamp` DESC");
        if (!$stmt->execute()) {
            return false;
        }
        $page_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($page_list as $key => $page) {
            $page_list[$key]['path'] = $this->get_file_path($page['file_id'], true);

            $parent = $this->get_file($page['parent'], true);

            if ($parent === false) {
                $page_list[$key]['recoverable'] = false;
                $page_list[$key]['reason'] = "Cannot find parent, the file is orphan. :/";
            } elseif (array_key_exists('trashed', $parent) && $parent['trashed']) {
                $page_list[$key]['recoverable'] = false;
                $page_list[$key]['reason'] = "Parent directory also in trash. Recover parent first.";
            } else {
                if ($this->get_slug_id($page['parent'], $page['slug']) !== false) {
                    $page_list[$key]['recoverable'] = false;
                    $page_list[$key]['reason'] = "A file currently exists at this path.";
                } else {
                    $page_list[$key]['recoverable'] = true;
                    $page_list[$key]['reason'] = "";
                }
            }
        }
        return $page_list;
    }

}
