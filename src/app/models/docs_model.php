<?php

class docs_model extends Model {

    function new_file($parent, $name, $slug, $type, $default_role, $user) {
        $db_error = false;
        $this->DB->autocommit(false);

        $stmt = $this->DB->prepare("INSERT INTO `files` (`name`, `slug`, `parent`, `type`, `default_role`) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt->bind_param("ssiss", $name, $slug, $parent, $type, $default_role)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        $insert_id = $stmt->insert_id;

        $stmt = $this->DB->prepare("INSERT INTO `file_data` (`file_id`, `action`, `created_by`) VALUES (?, 'create', ?)");
        if (!$stmt->bind_param("is", $insert_id, $user)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        if ($db_error) {
            $this->DB->rollback();
        } else {
            $this->DB->commit();
        }

        $this->DB->autocommit(true);
        return !$db_error;
    }

    function update_file($file_id, $name, $slug, $data, $user) {
        if ($file_id === false) {
            return false;
        }
        $file_type = $this->get_file_type($file_id);
        if ($file_type === false) {
            return false;
        }
        $db_error = false;
        $this->DB->autocommit(false);

        $stmt = $this->DB->prepare("UPDATE `files` SET `name`=?, `slug`=? WHERE `id`=?");
        if (!$stmt->bind_param("ssi", $name, $slug, $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        if ($file_type != 'directory') {
            $stmt = $this->DB->prepare("INSERT INTO `file_data` (`file_id`, `data`, `action`, `created_by`) VALUES (?, ?, 'edit', ?)");
            if (!$stmt->bind_param("iss", $file_id, $data, $user)) {
                $db_error = true;
            }
            if (!$stmt->execute()) {
                $db_error = true;
            }
        }

        if ($db_error) {
            $this->DB->rollback();
        } else {
            $this->DB->commit();
        }

        $this->DB->autocommit(true);
        return !$db_error;
    }

    function delete_file($file_id, $user) {
        if ($file_id === false || $file_id <= 0) {
            return false;
        }

        $db_error = false;
        $this->DB->autocommit(false);

        $stmt = $this->DB->prepare("INSERT INTO `trash_files` (`file_id`, `name`, `slug`, `parent`, `type`, `created_by`) SELECT `id`, `name`, `slug`, `parent`, `type`, ? FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("ss", $user, $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        $stmt = $this->DB->prepare("DELETE FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        $stmt = $this->DB->prepare("INSERT INTO `file_data` (`file_id`, `action`, `created_by`) VALUES (?, 'delete', ?)");
        if (!$stmt->bind_param("is", $file_id, $user)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        if ($db_error) {
            $this->DB->rollback();
        } else {
            $this->DB->commit();
        }

        $this->DB->autocommit(true);
        return !$db_error;
    }

    function recover_file($file_id, $user) {
        if ($file_id === false || $file_id <= 0) {
            return false;
        }

        $db_error = false;
        $this->DB->autocommit(false);

        $stmt = $this->DB->prepare("INSERT INTO `files` (`id`, `name`, `slug`, `parent`, `type`) SELECT `file_id`, `name`, `slug`, `parent`, `type` FROM `trash_files` WHERE `file_id`=?");
        if (!$stmt->bind_param("s", $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        $stmt = $this->DB->prepare("DELETE FROM `trash_files` WHERE `file_id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        $stmt = $this->DB->prepare("INSERT INTO `file_data` (`file_id`, `action`, `created_by`) VALUES (?, 'recover', ?)");
        if (!$stmt->bind_param("is", $file_id, $user)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        if ($db_error) {
            $this->DB->rollback();
        } else {
            $this->DB->commit();
        }

        $this->DB->autocommit(true);
        return !$db_error;
    }

    function get_slug_id($parent, $slug) {
        $stmt = $this->DB->prepare("SELECT `id` FROM `files` WHERE `parent`=? AND `slug`=?");
        if (!$stmt->bind_param("is", $parent, $slug)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_path_id($path) {
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

    function get_file_path($file_id, $include_trash = false) {
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
        } while($file_id !== 0);
        return $path;
    }

    function get_file_type($file_id) {
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `type` FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_directory($file_id) {
        // Get list of files in directory
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slug`, `parent`, `type` FROM `files` WHERE `parent`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $page_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $page_list;
    }

    function get_file($file_id, $include_trash = false) {
        // Get details of file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slug`, `parent`, `type`, `default_role` FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_assoc()) {
            return $row;
        } else if($include_trash && $row = $this->get_file_trashed($file_id)) {
            $row['trashed'] = true;
            return $row;
        }
        return false;
    }

    function get_file_trashed($file_id) {
        // Get details of a trashed file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `file_id` as `id`, `name`, `slug`, `parent`, `type` FROM `trash_files` WHERE `file_id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_assoc()) {
            return $row;
        }
        return false;
    }

    function get_file_data($file_id) {
        // Get data for file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `data` FROM `file_data` WHERE `file_id`=? AND `action`='edit' ORDER BY `id` DESC LIMIT 1");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_file_data_version($file_id, $version_id) {
        // Get data for file at specific version
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `data` FROM `file_data` WHERE `file_id`=? AND `id`<=? AND `action`='edit' ORDER BY `id` DESC LIMIT 1");
        if (!$stmt->bind_param("ii", $file_id, $version_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_latest_version_id($file_id) {
        // Get latest version id
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id` FROM `file_data` WHERE `file_id`=? ORDER BY `id` DESC LIMIT 1");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_history($file_id) {
        // Get list of edits for file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `action`, `timestamp`, `created_by` FROM `file_data` WHERE `file_id`=? ORDER BY `id` DESC");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $history_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_list;
    }

    function get_history_item($file_id, $edit_id) {
        if ($file_id === false || $edit_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `file_id`, `action`, `data`, `timestamp`, `created_by` FROM `file_data` WHERE `file_id`=? AND `id`=?");
        if (!$stmt->bind_param("ii", $file_id, $edit_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $history_item = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_item ? $history_item[0] : false;
    }

    function get_history_diff($file_id, $edit_id) {
        if ($file_id === false || $edit_id === false) {
            return false;
        }

        $stmt = $this->DB->prepare("SELECT `id`, `data`, `action`, `timestamp`, `created_by` FROM `file_data` WHERE `file_id`=? AND `id`<=? AND `action`='edit' ORDER BY `id` DESC LIMIT 2");
        if (!$stmt->bind_param("ii", $file_id, $edit_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $history_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_list;
    }

    function get_trash_list() {
        // Get list of files in trash
        $stmt = $this->DB->prepare("SELECT `id`, `file_id`, `name`, `slug`, `parent`, `type`, `timestamp`, `created_by` FROM `trash_files` ORDER BY `timestamp` DESC");
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
            } else if (array_key_exists('trashed', $parent) && $parent['trashed']) {
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
