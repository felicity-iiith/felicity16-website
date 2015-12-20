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

    public function check_credentials($email, $password) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `id`, `password_hash` FROM `sap_users` WHERE `email`=?',
            's',
            [$email]
        );
        $row = $stmt->get_result()->fetch_row();
        if (! $row) {
            return false;
        }
        if (password_verify($password, $row[1])) {
            return $row[0];
        }
        return false;
    }

    public function is_admin($email) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `is_admin` FROM `sap_users` WHERE `email`=?',
            's',
            [$email]
        );
        $row = $stmt->get_result()->fetch_row();
        if (! $row) {
            return false;
        }
        return boolval($row[0]);
    }

    public function get_missions() {
        $result = $this->DB->sap->query(
            'SELECT `id`, `level`, `title`, `description` FROM `sap_missions` ' .
            'ORDER BY level ASC, id ASC'
        );
        $missions = $result->fetch_all(MYSQLI_ASSOC);
        return $missions;
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

    public function create_mission($title, $level, $points, $description) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'INSERT INTO `sap_missions` (`title`, `level`, `points`, `description`) ' .
            'VALUES (?, ?, ?, ?)',
            'ssss',
            [$title, $level, $points, $description]
        );
        if (! $stmt) {
            return false;
        }
        return $stmt->insert_id;
    }

    public function get_tasks($mission_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `id`, `description`, `has_text_answer` FROM `sap_tasks` WHERE `mission_id`=? ORDER BY id ASC',
            'i',
            [$mission_id]
        );
        $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $tasks;
    }

    public function create_task($mission_id, $description, $has_text_answer) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'INSERT INTO `sap_tasks` (`mission_id`, `description`, `has_text_answer`) ' .
            'VALUES (?, ?, ?)',
            'isi',
            [$mission_id, $description, $has_text_answer]
        );
        return boolval($stmt);
    }

    public function submit_task($task_id, $user_id, $text_answer) {
        if ($text_answer) {
            $stmt = $this->db_lib->prepared_execute(
                $this->DB->sap,
                'INSERT INTO `sap_task_submissions` (`task_id`, `user_id`, `answer`) ' .
                'VALUES (?, ?, ?)',
                'iis',
                [$task_id, $user_id, $text_answer]
            );
        } else {
            $stmt = $this->db_lib->prepared_execute(
                $this->DB->sap,
                'INSERT INTO `sap_task_submissions` (`task_id`, `user_id`) ' .
                'VALUES (?, ?)',
                'ii',
                [$task_id, $user_id]
            );
        }
        return boolval($stmt);
    }

    public function get_tasks_with_submissions($user_id, $mission_id, $delete_rejected = false) {
        $tasks = $this->get_tasks($mission_id);
        $submissions = $this->get_task_submissions(
            $user_id,
            $mission_id
        );
        // TODO: Refactor this to make it faster than O(n^2)
        foreach ($submissions as $submission) {
            foreach ($tasks as &$task) {
                if ($submission['task_id'] == $task['id']) {
                    $task['submission'] = $submission;
                    break;
                }
            }
        }

        if ($delete_rejected) {
            // TODO: Do something with return value :/
            $this->delete_rejected_submissions($user_id, $mission_id);
        }

        return $tasks;
    }

    public function delete_rejected_submissions($user_id, $mission_id) {
        $query = <<<SQL
DELETE `sap_task_submissions`
FROM `sap_task_submissions`
INNER JOIN `sap_tasks`
ON `sap_tasks`.`id` = `sap_task_submissions`.`task_id`
WHERE `sap_task_submissions`.`done` = 2 AND `sap_task_submissions`.`user_id`=? AND `sap_tasks`.`mission_id`=?
SQL;
        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            $query,
            'ii',
            [$user_id, $mission_id],
            false
        );
    }

    public function get_task_submissions_for_review($mission_id) {
        // TODO: Figure out a way to make this enormous query cleaner.
        // Changing the schema to use duplicates and foreign keys and ON UPDATE CASCADE is an idea
        $query = <<<SQL
SELECT `ambassadors`.`name` AS `users_name`
    , `tasks`.`description` AS `task_description`
    , `task_submissions`.`answer`
    , `tasks`.`mission_id`
    , `task_submissions`.`id`
    , `missions`.`title` AS `mission_title`
FROM `sap_task_submissions` AS `task_submissions`
INNER JOIN `sap_tasks` AS `tasks`
ON `tasks`.`id` = `task_submissions`.`task_id`
INNER JOIN `sap_missions` as `missions`
ON `missions`.`id` = `tasks`.`mission_id`
INNER JOIN `sap_users` AS `users`
ON `users`.`id` = `task_submissions`.`user_id`
INNER JOIN `sap_ambassadors` AS `ambassadors`
ON `ambassadors`.`id` = `users`.`registration_id`
WHERE `tasks`.`mission_id`=? and `task_submissions`.`done` = 0
SQL;
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            $query,
            'i',
            [$mission_id]
        );
        $submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $submissions;
    }

    public function get_task_submissions($user_id, $mission_id) {
        $query = <<<SQL
SELECT `task_id`, `done`, `answer`
FROM `sap_task_submissions`
INNER JOIN `sap_tasks`
ON `sap_tasks`.`id` = `sap_task_submissions`.`task_id`
WHERE `user_id`=? AND `sap_tasks`.`mission_id`=?
SQL;
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            $query,
            'ii',
            [$user_id, $mission_id]
        );
        $submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $submissions;
    }

    public function submit_review($submission_id, $approved) {
        if ($approved) {
            $status = 1;
        } else {
            $status = 2;
        }
        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            'UPDATE `sap_task_submissions` SET `done`=? WHERE `id`=?',
            'ii',
            [$status, $submission_id],
            false
        );
    }
}
