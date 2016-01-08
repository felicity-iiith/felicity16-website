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

    function get_user_old_ldap($email) {
        $attributes = [
            "uid" => "uid",
            "mail" => "mail",
            "givenName" => "firstname",
            "sn" => "lastname",
            "displayName" => "nick",
            "gender" => "gender",
            "birthdate" => "dob",
            "o" => "organization",
            "c" => "country",
            "l" => "location"
        ];

        $this->load_library("ldap_lib", "ldap");
        $ds = $this->ldap->get_link();
        $dn = "dc=felicity,dc=iiit,dc=ac,dc=in";
        $filter = '(&(mail='.$email.'))';
        $sr = ldap_search($ds, $dn, $filter, array_keys($attributes));
        $entry = ldap_first_entry($ds, $sr);

        if (!$entry) {
            return false;
        }

        $entry_data = ldap_get_attributes($ds, $entry);
        $user_data = [];

        foreach ($attributes as $key => $value) {
            if (isset($entry_data[$key]) &&  isset($entry_data[$key][0])) {
                $user_data[$value] = $entry_data[$key][0];
            }
        }

        if (isset($user_data["dob"])) {
            $date = date_create_from_format('d/m/Y', $user_data["dob"]);
            if ($date) {
                $user_data["dob"] = date_format($date, "Y-m-d");
            }
        }
        if (isset($user_data["firstname"]) && isset($user_data["lastname"])) {
            $user_data["name"] = implode(" ", [$user_data["firstname"], $user_data["lastname"]]);
            unset($user_data["firstname"]);
            unset($user_data["lastname"]);
        }
        if (isset($user_data["gender"])) {
            $user_data["gender"] = strtolower($user_data["gender"]);
        }

        return $user_data;
    }

    function link_oauth_account($oauth_id, $user_id) {
        return $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "INSERT INTO `oauth_users` (`oauth_id`, `user_id`) VALUES (?, ?)",
            "si",
            [$oauth_id, $user_id],
            false
        );
    }

    function create_user($oauth_id, $user_data) {
        $db_error = false;
        $this->DB->users->autocommit(false);

        $nick = isset($user_data["nick"]) ? $user_data["nick"] : null;
        $mail = isset($user_data["mail"]) ? $user_data["mail"] : null;
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
        if (!$stmt) {
            $db_error = true;
        }

        if (!$db_error) {
            $insert_id = $stmt->insert_id;

            $stmt = $this->link_oauth_account($oauth_id, $insert_id);
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

    function remove_user($user_id) {
        return $this->db_lib->prepared_execute(
            $this->DB->users,
            "DELETE FROM `users` WHERE `id`=?",
            "i",
            [$user_id],
            false
        ) && $this->db_lib->prepared_execute(
            $this->DB->users,
            "DELETE FROM `oauth_users` WHERE `user_id`=?",
            "i",
            [$user_id],
            false
        );
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

    function set_mail_verification($email, $hash, $action) {
        return $this->db_lib->prepared_execute(
            $this->DB->users,
            "INSERT INTO `mail_verify` (`email`, `hash`, `action`) VALUES (?, ?, ?)",
            "sss",
            [$email, $hash, $action],
            false
        );
    }

    function get_mail_verification($email) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "SELECT `email`, `hash`, `action` FROM `mail_verify` WHERE `email`=?",
            "s",
            [$email]
        );
        if ($stmt && $data = $stmt->get_result()->fetch_assoc()) {
            return $data;
        }
        return false;
    }

    function verify_mail($hash, $remove=true) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->users,
            "SELECT `email`, `hash`, `action` FROM `mail_verify` WHERE `hash`=?",
            "s",
            [$hash]
        );
        if ($stmt) {
            $data = $stmt->get_result()->fetch_assoc();
        }
        if ($remove) {
            $this->remove_verify_hash($hash);
        }
        return $data ?: false;
    }

    function remove_verify_hash($hash) {
        return $this->db_lib->prepared_execute(
            $this->DB->users,
            "DELETE FROM `mail_verify` WHERE `hash`=?",
            "s",
            [$hash],
            false
        );
    }

    function create_ldap_user($email, $password) {
        $this->load_library("ldap_lib", "ldap");
        $ds = $this->ldap->get_link();
        return ldap_add($ds, "uid=".addcslashes($email, '+').",ou=users,dc=felicity,dc=iiit,dc=ac,dc=in", [
            "userPassword"  => "{SHA}" . base64_encode(pack("H*", sha1($password))),
            "objectClass"   => [
                "account",
                "simpleSecurityObject",
                "extensibleObject"
            ],
            "mail"          => $email
        ]);
    }

    function reset_ldap_password($email, $password) {
        $this->load_library("ldap_lib", "ldap");
        $ds = $this->ldap->get_link();
        return ldap_mod_replace($ds, "uid=".addcslashes($email, '+').",ou=users,dc=felicity,dc=iiit,dc=ac,dc=in", [
            "userPassword" => "{SHA}" . base64_encode(pack("H*", sha1($password)))
        ]);
    }
}
