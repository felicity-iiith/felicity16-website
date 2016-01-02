<?php

class sap_model extends Model {
    public function __construct() {
        $this->load_library('db_lib');
    }

    public function registerEntry($data) {
        // Optional form field
        $organisedEvent = isset($data['organised-event']) ? $data['organised-event'] : null;

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

    public function get_missions($get_unpublished = false) {
        $where_clause = $get_unpublished ? "" : "WHERE `published` IS TRUE";
        $result = $this->DB->sap->query(
        "SELECT `id`, `level`, `points`, `title`, `description` FROM `sap_missions`
         $where_clause
         ORDER BY level ASC, id ASC"
        );
        $missions = $result->fetch_all(MYSQLI_ASSOC);
        return $missions;
    }

    public function get_mission($id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT `level`, `published`, `points`, `title`, `description`
             FROM `sap_missions`
             WHERE `id`=?',
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

    public function get_submission_details($submission_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            "SELECT s.`user_id`, s.`task_id`, t.`mission_id`
            FROM `sap_task_submissions` s
            JOIN `sap_tasks` t ON s.`task_id` = t.`id`
            WHERE s.`id`=?",
            "i",
            [$submission_id]
        );
        if (!$stmt) {
            return false;
        };
        $row = $stmt->get_result()->fetch_assoc();
        return $row;
    }

    public function get_user_scores() {
        $rows = $this->DB->sap->query('
                SELECT u.`email`, u.`score`, a.`name`
                FROM `sap_users` u
                JOIN `sap_ambassadors` a ON u.`email` = a.`email`
                ORDER BY u.`score` DESC, a.`name` ASC');
        return $rows->fetch_all(MYSQLI_ASSOC);
    }

    public function handle_mission_complete($user_id, $mission_id) {
        $tasks = $this->get_tasks_with_submissions($user_id, $mission_id);
        $mission_complete = true;
        foreach ($tasks as $task) {
            if (!isset($task["submission"])
                || !isset($task["submission"]["done"])
                || $task["submission"]["done"] !== 1
            ) {
                $mission_complete = false;
                break;
            }
        }
        if ($mission_complete === true) {
            $mission = $this->get_mission($mission_id);
            $points = $mission["points"];

            return $this->db_lib->prepared_execute(
                $this->DB->sap,
                'UPDATE `sap_users` SET `score`=`score`+? WHERE `id`=?',
                'ii',
                [$points, $user_id],
                false
            );
        }
        return true;
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

    public function publish_mission($mission_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'UPDATE `sap_missions` SET `published`=TRUE WHERE `id`=?',
            'i',
            [$mission_id],
            false
        );
        return $stmt;
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

    public function is_submission_allowed($user_id, $task_id) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            "SELECT `done` FROM `sap_task_submissions`
            WHERE `task_id`=? AND `user_id`=?
            ORDER BY `id` DESC LIMIT 1",
            'ii',
            [$task_id, $user_id]
        );
        if (!$stmt) {
            return false;
        }
        $row = $stmt->get_result()->fetch_row();
        if (!$row) {
            return true;
        }
        return $row[0] === 2;
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

    public function get_tasks_with_submissions($user_id, $mission_id) {
        $tasks = $this->get_tasks($mission_id);
        $submissions = $this->get_task_submissions(
            $user_id,
            $mission_id
        );
        // TODO: Refactor this to make it faster than O(n^2)
        // Get latest submission for each task
        foreach ($submissions as $submission) {
            foreach ($tasks as &$task) {
                if ($submission['task_id'] == $task['id']) {
                    $task['submission'] = $submission;
                    break;
                }
            }
        }

        return $tasks;
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

    /* Returns all the submissions for all the tasks in the mission by the user */
    public function get_task_submissions($user_id, $mission_id) {
        $query = <<<SQL
SELECT `task_id`, `done`, `answer`
FROM `sap_task_submissions` submissions
INNER JOIN `sap_tasks`
ON `sap_tasks`.`id` = submissions.`task_id`
WHERE `user_id`=? AND `sap_tasks`.`mission_id`=?
ORDER BY submissions.`id`
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

    public function get_users_list() {
        $result = $this->DB->sap->query('SELECT * FROM `sap_ambassadors` WHERE `is_removed` = 0');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_user($id, $activated = true) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT * FROM `sap_ambassadors` WHERE `id`=? AND `has_activated`=?',
            'ii',
            [$id, $activated],
            true
        );
        return $stmt->get_result()->fetch_assoc();
    }

    public function approve_user($id, $password_hash) {
        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            'UPDATE `sap_ambassadors` SET `hash_for_ceating_password`=? WHERE `id`=?',
            'si',
            [$password_hash, $id],
            false
        );
    }

    public function remove_user($id) {
        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            'UPDATE `sap_ambassadors` SET `is_removed`=1 WHERE `id`=?',
            'i',
            [$id],
            false
        );
    }

    public function verify_hash($hash) {
        $stmt = $this->db_lib->prepared_execute(
            $this->DB->sap,
            'SELECT * FROM `sap_ambassadors` WHERE `hash_for_ceating_password`=?',
            's',
            [$hash],
            true
        );
        return $stmt->get_result()->fetch_assoc();
    }

    public function create_user_password($user, $password) {
        $db_error = false;
        $this->DB->sap->autocommit(false);

        $stmt = $this->db_lib->prepared_execute(
                $this->DB->sap,
                'UPDATE `sap_ambassadors` SET `hash_for_ceating_password`=?, `has_activated`=1 WHERE `id`=?',
                'ss',
                [null, $user['id']],
                false
            );
        if (! $stmt) {
            $db_error = true;
        }

        if (! $db_error) {
            $stmt = $this->db_lib->prepared_execute(
                $this->DB->sap,
                'INSERT INTO `sap_users` (`email`, `registration_id`, `password_hash`) VALUES (?, ?, ?)',
                'sss',
                [$user['email'], $user['id'], password_hash($password, PASSWORD_DEFAULT)],
                false
            );
            if (! $stmt) {
                $db_error = true;
            }
        }

        if ($db_error) {
            $this->DB->sap->rollback();
        } else {
            $this->DB->sap->commit();
        }
        $this->DB->sap->autocommit(true);
        return !$db_error;
    }
}
