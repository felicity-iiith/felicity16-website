<?php

/**
 * Database library, common DB helpers
 */
class db_lib extends Library {

    private function bind_params($stmt, $datatypes, $data) {
        if (!empty($datatypes) && !empty($data)) {
            // Convert to references
            $data = array_merge([$datatypes], $data);
            foreach ($data as $key => $value) {
                $data[$key] = &$data[$key];
            }
            if (!call_user_func_array(array($stmt, "bind_param"), $data)) {
                return false;
            }
        }
        return true;
    }

    public function prepared_execute($conn, $sql, $datatypes, $data, $return_stmt = true) {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        if ($this->bind_params($stmt, $datatypes, $data) === false) {
            return false;
        };

        if (!$stmt->execute()) {
            return false;
        }

        if ($return_stmt) {
            return $stmt;
        }
        return true;
    }

}
