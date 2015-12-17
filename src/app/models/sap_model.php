<?php

class sap_model extends Model {
    public function __construct() {
        $this->load_library('db_lib');
    }

    public function registerEntry($data) {
        // Optional form field
        $organisedEvent = isset($data['organised-event']) ? $data['organised-event'] : NULL;

        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            'INSERT INTO `sap_ambassadors` (
                `name`, `email`, `phone_number`, `college`, `program_of_study`, `year_of_study`,
                `facebook_profile_link`, `why_apply`, `about_you`, `organised_event`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            'ssssssssss', [
                $data['name'], $data['email'], $data['phone-number'], $data['college'], $data['program-of-study'],
                $data['year-of-study'], $data['facebook-profile-link'], $data['why-apply'],
                $data['about-you'], $organisedEvent
            ],
            false
        );
    }

    public function check_credentials($username, $password) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `password_hash` FROM `sap_users` WHERE `username`=?',
            's',
            [$username]
        );
        $row = $stmt->get_result()->fetch_row();
        if (! $row) {
            return false;
        }
        return password_verify($password, $row[0]);
    }

    public function is_admin($username) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `is_admin` FROM `sap_users` WHERE `username`=?',
            's',
            [$username]
        );
        $row = $stmt->get_result()->fetch_row();
        if (! $row) {
            return false;
        }
        return boolval($row[0]);
    }

    public function get_mission($id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `level`, `title`, `description` FROM `sap_missions` WHERE `id`=?',
            'i',
            [$id]
        );
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $row['id'] = $id;
            return $row;
        }
        return false;
    }

    public function get_tasks($mission_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `id`, `description` FROM `sap_tasks` WHERE `mission_id`=? ORDER BY id ASC',
            'i',
            [$mission_id]
        );
        $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $tasks;
    }
}
