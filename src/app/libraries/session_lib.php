<?php

class session_lib extends Library {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set flash data
     * @param string $name
     * @param mixed  $value
     */
    public function flash_set($name, $value) {
        if (!isset($_SESSION["_flashdata"])) {
            $_SESSION["_flashdata"] = [];
        }
        $_SESSION["_flashdata"][$name] = $value;
    }

    /**
     * Append to an array in flash data
     * If the array does not exist, create it and then append
     * @param string $name
     * @param mixed  $value
     */
    public function flash_append($name, $value) {
        if (!isset($_SESSION["_flashdata"])) {
            $_SESSION["_flashdata"] = [];
        }
        if (!isset($_SESSION["_flashdata"][$name])) {
            $_SESSION["_flashdata"][$name] = [];
        }
        $_SESSION["_flashdata"][$name][] = $value;
    }

    /**
     * Get and unset flash data
     * @param  string     $name
     * @return mixed|null       Value of the set flashed data
     */
    public function flash_get($name) {
        if (!isset($_SESSION["_flashdata"])
            || !isset($_SESSION["_flashdata"][$name])
        ) {
            return null;
        }
        $value = $_SESSION["_flashdata"][$name];
        unset($_SESSION["_flashdata"][$name]);
        return $value;
    }
}
