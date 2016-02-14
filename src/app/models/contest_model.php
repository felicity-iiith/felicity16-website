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

    /*
    |---------------------------------------------------------------------------
    | TTT-Workshop
    |---------------------------------------------------------------------------
    */

    public function is_registered_for_ttt($user_nick) {
        return $this->is_registered("ttt_registrations", $user_nick);
    }

    public function register_for_ttt($user_nick, $contact_number) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `ttt_registrations`
            (`nick`, `contact_number`) VALUES (?, ?)",
            "ss",
            [$user_nick, $contact_number]
        );
        if (!$stmt) {
            return false;
        }
        else {
            return true;
        }
    }

    public function ttt_payment_success($payment_id, $nick, $status, $payment_data) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "UPDATE `ttt_registrations`
            SET `payment_id` = ?, `payment_status` = ?, `payment_data` = ?
            WHERE `nick` = ?",
            "ssss",
            [$payment_id, $status, $payment_data, $nick]
        );
        $this->ttt_dump_data($nick, 'callback', $payment_data);
        if (!$stmt) {
            return false;
        }
        else {
            return true;
        }
    }

    public function ttt_dump_data($nick, $type, $response) {
        $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `ttt_payment_dump`
            (`nick`, `type`, `response`)
            VALUES (?, ?, ?)",
            "sss",
            [$nick, $type, $response]
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Futsal
    |---------------------------------------------------------------------------
    */

    private function get_futsal_team($team_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `futsal_teams`
            WHERE `id` = ?",
            "i",
            [$team_id]
        );
        if (!$stmt) {
            return false;
        }
        $team_info = $stmt->get_result()->fetch_assoc();
        if ($team_info) {
            return $team_info;
        }
        return false;
    }

    public function is_registered_for_futsal($user_nick) {
        $user_details = $this->is_registered('futsal_participants', $user_nick);
        if ($user_details) {
            $team_info = $this->get_futsal_team($user_details['team_id']);
            return $team_info;
        }
        return false;
    }

    public function check_futsal_team_exists($team_name) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `futsal_teams`
            WHERE `team_name` = ?",
            "s",
            [$team_name]
        );
        if (!$stmt) {
            return false;
        }
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return true;
        }
        return false;
    }

    public function check_futsal_participant_exists($user_nick) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `futsal_participants`
            WHERE `nick` = ?",
            "s",
            [$user_nick]
        );
        if (!$stmt) {
            return false;
        }
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return true;
        }
        return false;
    }

    public function register_for_futsal($team_name, $contact_number, $team) {
        $this->DB->contest->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `futsal_teams`
            (`team_name`, `contact_number`) VALUES (?, ?)",
            "ss",
            [$team_name, $contact_number]
        );
        if (!$stmt) {
            return false;
        }
        $team_id = $this->DB->contest->insert_id;
        foreach ($team as $nick) {
            $stmt = $this->db_lib->prepared_execute(
                $this->DB->contest,
                "INSERT INTO `futsal_participants`
                (`team_id`, `nick`) VALUES (?, ?)",
                "is",
                [$team_id, $nick]
            );
            if (!$stmt) {
                return false;
            }
        }
        return $this->DB->contest->commit();
    }

    /*
    |---------------------------------------------------------------------------
    | Paper Presentation
    |---------------------------------------------------------------------------
    */

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

    /*
    |---------------------------------------------------------------------------
    | Web Development Workshop
    |---------------------------------------------------------------------------
    */

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
