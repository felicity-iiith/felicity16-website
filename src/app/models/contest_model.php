<?php

class contest_model extends Model {

    function __construct() {
        $this->load_library("db_lib");
    }

    private function is_registered($table_name, $user_nick) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `$table_name`
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

    public function is_registered_for_paper_presentation($user_nick) {
        return $this->is_registered('paper_presentation', $user_nick);
    }

    public function register_for_paper_presentation($info) {
        return $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `paper_presentation`
            (`nick`, `contact_number`, `paper_link`)
            VALUES  (?, ?, ?)",
            "sss",
            [
                $info['nick'],
                $info['contact_number'],
                $info['paper_link'],
            ],
            false
        );
    }

    public function is_registered_for_webdev($user_nick) {
        return $this->is_registered('webdev_registrations', $user_nick);
    }

    public function register_for_webdev($info) {
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
