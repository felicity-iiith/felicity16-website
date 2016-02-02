<?php

class workshop_model extends Model {

    function __construct() {
        $this->load_library("db_lib");
    }

    public function is_registered_for_webdev($user_nick) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `webdev_registrations`
            WHERE `nick` = ?",
            "s",
            [$user_nick]
        );
        if (!$stmt) {
            return false;
        }
        $user_details = $stmt->get_result()->fetch_assoc();
        if ($user_details) {
            return $user_details;
        }
        return false;
    }

    public function register($info) {
        return $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `webdev_registrations`
            (`nick`, `contact_number`, `stream`, `year`, `experience`, `why_join`)
            VALUES  (?, ?, ?, ?, ?, ?)",
            "ssssss",
            [
                $info['nick'],
                $info['contact_number'],
                $info['stream'],
                $info['year'],
                $info['experience'],
                $info['why_join'],
            ],
            false
        );
    }
}
