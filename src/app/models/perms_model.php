<?php

class perms_model extends Model {

    private $all_permissions = [
        'read_file',
        'write_file',
        'manage_user',
        'see_global_trash',
        'see_history_detail'
    ];

    private $implied_permissions = [
        "write_file" => ["read_file"],
    ];

    private $default_rp = [
        "none" => [],
        "reader" => ['read_file'],
        "author" => ['write_file', 'see_history_detail'],
        "admin" => ['write_file', 'manage_user', 'see_global_trash', 'see_history_detail']
    ];

    private $role_permissions = [];

    function __construct() {
        $this->load_model("jugaad_model");
        $this->load_model("auth_model");

        $this->load_library("db_lib");

        $this->default_rp["superadmin"] = $this->all_permissions;

        $this->role_permissions = $this->get_role_info();
    }

    private function extend_permissions($permissions) {
        $e_perms = [];

        foreach ($permissions as $perm) {
            array_push($e_perms, $perm);
            if (isset($this->implied_permissions[$perm])) {
                $e_perms = array_merge($e_perms, $this->implied_permissions[$perm]);
            }
        }

        return array_unique($e_perms);
    }

    function get_role_info() {
        $perms = [];

        foreach ($this->default_rp as $role => $value) {
            $perms[$role] = $this->extend_permissions($value);
        }

        return $perms;
    }

    function add_user_role($file_id, $user, $role) {
        // Add admin user for a file
        if ($file_id === false || !$user) {
            return false;
        }
        return $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "INSERT INTO `file_permissions` (`file_id`, `user`, `role`) VALUES (?, ?, ?)",
            "iss",
            [$file_id, $user, $role],
            false
        );
    }

    function remove_user_role($file_id, $user) {
        // Remove admin user for a file
        if ($file_id === false || !$user) {
            return false;
        }
        return $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "DELETE FROM `file_permissions` WHERE `file_id`=? AND `user`=?",
            "is",
            [$file_id, $user],
            false
        );
    }

    function get_default_role($file_id) {
        // Get default role for a file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `default_role` FROM `files` WHERE `id`=?",
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

    function set_default_role($file_id, $role) {
        // Set default role for a file
        if ($file_id === false) {
            return false;
        }
        return $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "UPDATE `files` SET `default_role`=? WHERE `id`=?",
            "si",
            [$role, $file_id],
            false
        );
    }

    private function file_get_user_role($file_id, $user) {
        // Check permissions for a single file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `role` FROM `file_permissions` WHERE `file_id`=? AND `user`=?",
            "is",
            [$file_id, $user]
        );
        if (!$stmt) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_user_role($file_id, $user) {
        // Check permissions for file with inherited permissions
        if ($this->auth_model->is_admin($user)) {
            return 'superadmin';
        }

        if ($file_id === false) {
            return false;
        }
        $orig_file_id = $file_id;
        do {
            $file = $this->jugaad_model->get_file($file_id);
            if ($file === false) {
                return false;
            }
            $role = $this->file_get_user_role($file_id, $user);
            if ($role !== false) {
                return $role;
            }
            $file_id = $file['parent'];
        } while ($file_id >= 0);

        return $this->get_default_role($orig_file_id);
    }

    private function file_get_users($file_id) {
        // Get list of privileged users for a single file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->jugaad,
            "SELECT `file_id`, `user`, `role` FROM `file_permissions` WHERE `file_id`=?",
            "i",
            [$file_id]
        );
        if (!$stmt) {
            return false;
        }
        $user_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $user_list;
    }

    function get_user_list($file_id) {
        // Get list of privileged users for file with inherited permissions
        if ($file_id === false) {
            return false;
        }
        $user_list = [];
        do {
            $file = $this->jugaad_model->get_file($file_id);
            if ($file === false) {
                return false;
            }
            $file_users = $this->file_get_users($file_id);
            foreach ($file_users as $user) {
                if (!isset($user_list[$user['user']])) {
                    $user_list[$user['user']] = $user;
                }
            }
            $file_id = $file['parent'];
        } while ($file_id >= 0);

        return $user_list;
    }

    function get_role_permissions($role) {
        if ($role === false) {
            return false;
        }

        $perm_list = $this->role_permissions[$role];

        $user_can = [];

        foreach ($this->all_permissions as $perm) {
            $user_can[$perm] = false;
            if (in_array($perm, $perm_list)) {
                $user_can[$perm] = true;
            }
        }

        return $user_can;
    }

    function get_permissions($file_id, $user) {
        if ($file_id === false) {
            return false;
        }

        // Return false if file does not exists
        if (false === $this->jugaad_model->get_file_type($file_id)) {
            return false;
        }

        $role = $this->get_user_role($file_id, $user);
        $user_can = $this->get_role_permissions($role);

        return $user_can;
    }

}
