<?php

class breakin_model extends Model {

    function __construct() {
        $this->load_library("db_lib");
    }

    public function get_team_info($user_nick) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `breakin_teams`
            WHERE `nick1` = ? OR `nick2` = ?",
            "ss",
            [$user_nick, $user_nick]
        );
        if (!$stmt) {
            return false;
        }
        if ($team_info = $stmt->get_result()->fetch_assoc()) {
            return $team_info;
        }
        return false;
    }

    public function does_team_exists($team_name) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->contest,
            "SELECT *
            FROM `breakin_teams`
            WHERE `team_name` = ?",
            "s",
            [$team_name]
        );
        if (!$stmt) {
            return false;
        }
        if ($stmt->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function register($info) {
        return $this->db_lib->prepared_execute(
            $this->DB->contest,
            "INSERT INTO `breakin_teams`
            (`nick1`, `nick2`, `team_name`) VALUES  (?, ?, ?)",
            "sss",
            [ $info['nick1'], $info['nick2'], $info['team_name'] ?: $info['nick1'] ],
            false
        );
    }
}
