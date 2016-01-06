<?php

class auth_model extends Model {

    function __construct() {
        $this->load_library("db_lib");
    }

    // Is the user super-admin for Jugaad
    function is_admin($user) {
        global $admins;

        if (!isset($admins) || !is_array($admins)) {
            return false;
        }

        if (in_array($user, $admins)) {
            return true;
        }
        return false;
    }

    function get_user($oauth_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "SELECT u.*
            FROM `users` u JOIN `oauth_users` o ON u.`id` = o.`user_id`
            WHERE o.`oauth_id`=?",
            "s",
            [$oauth_id]
        );
        if (!$stmt) {
            return false;
        }
        if ($user = $stmt->get_result()->fetch_assoc()) {
            return $user;
        }
        return false;
    }

    function get_user_by_nick($nick) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "SELECT * FROM `users` WHERE `nick`=?",
            "s",
            [$nick]
        );
        if ($stmt && $user = $stmt->get_result()->fetch_assoc()) {
            return $user;
        }
        return false;
    }

    function get_user_by_mail($mail) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "SELECT * FROM `users` WHERE `mail`=?",
            "s",
            [$mail]
        );
        if ($stmt && $user = $stmt->get_result()->fetch_assoc()) {
            return $user;
        }
        return false;
    }

    function create_user($oauth_id, $user_data) {
        $db_error = false;
        $this->DB->users->autocommit(false);

        $nick = isset($user_data["nick"]) ? $user_data["nick"] : "";
        $mail = isset($user_data["mail"]) ? $user_data["mail"] : "";
        $name = isset($user_data["name"]) ? $user_data["name"] : "";
        $gender = isset($user_data["gender"]) ? $user_data["gender"] : "";
        $location = isset($user_data["location"]) ? $user_data["location"] : "";
        $country = isset($user_data["country"]) ? $user_data["country"] : "";
        $dob = isset($user_data["dob"]) ? $user_data["dob"] : "";
        $organization = isset($user_data["organization"]) ? $user_data["organization"] : "";
        $raw_attributes = isset($user_data["raw_attributes"]) ? $user_data["raw_attributes"] : "";
        $email_verified = isset($user_data["email_verified"]) ? $user_data["email_verified"] : "0";
        $resitration_status = isset($user_data["resitration_status"]) ? $user_data["resitration_status"] : "email_required";

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "INSERT INTO `users`
            (`nick`, `mail`, `name`, `gender`, `location`, `country`, `dob`, `organization`,
                `raw_attributes`, `email_verified`, `resitration_status`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "sssssssssss",
            [$nick, $mail, $name, $gender, $location, $country, $dob, $organization, $raw_attributes, $email_verified, $resitration_status]
        );
        var_dump($this->DB->users->error);
        if (!$stmt) {
            $db_error = true;
        }

        if (!$db_error) {
            $insert_id = $stmt->insert_id;

            $stmt = $this->db_lib->prepared_execute(
                $this->DB->users,
                "INSERT INTO `oauth_users` (`oauth_id`, `user_id`) VALUES (?, ?)",
                "si",
                [$oauth_id, $insert_id]
            );
            if (!$stmt) {
                $db_error = true;
            }
        }

        if ($db_error) {
            $this->DB->users->rollback();
        } else {
            $this->DB->users->commit();
        }

        $this->DB->users->autocommit(true);
        return !$db_error;
    }

    function update_user($user_id, $user_data) {
        $params = [];
        $query = "UPDATE `users` SET";
        $param_type = "";

        $update_query = [];

        $db_fields = [
            "nick", "mail", "name", "gender", "location", "country", "dob", "organization",
            "raw_attributes", "email_verified", "resitration_status"
        ];
        foreach ($db_fields as $field) {
            if (isset($user_data[$field])) {
                $update_query[] = "`$field`=?";
                $params[] = $user_data[$field];
                $param_type .= "s";
            }
        }

        if (!count($update_query)) {
            return true;
        }

        $query .= " " . implode(", ", $update_query);

        $query .= " WHERE `id`=?";
        $params[] = $user_id;
        $param_type .= "i";

        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            $query,
            $param_type,
            $params
        );
        if (!$stmt) {
            return false;
        }
        return true;
    }
}
