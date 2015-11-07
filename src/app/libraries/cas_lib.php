<?php
class cas_lib extends Library {

    function __construct() {
        $olddir = getcwd();
        chdir(dirname(__FILE__));
        require_once('CAS/CAS.php');
        chdir($olddir);
    }

    function forceAuthentication() {
        phpCAS::forceAuthentication();
    }

    function isAuthenticated() {
        return phpCAS::isAuthenticated();
    }

    function logout() {
        phpCAS::logout();
    }

    function getUser() {
        return phpCAS::getUser();
    }

    function getNick() {
        return phpCAS::getAttribute('displayName') ?: explode('@', phpCAS::getUser())[0];
    }
}
