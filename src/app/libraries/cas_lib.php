<?php

/**
 * CAS Library
 */
class cas_lib {
    private static $initialized = false;
    function __construct() {
        if (! self::$initialized) {
            global $cas_cfg;
            phpCAS::client(
                CAS_VERSION_2_0,
                $cas_cfg['host'],
                $cas_cfg['port'],
                $cas_cfg['context']
            );
            // Perform SSL validation only if server_ca_cert path is provided.
            if (isset($cas_cfg['server_ca_cert'])) {
                phpCAS::setCasServerCACert($cas_cfg['server_ca_cert']);
            }
            else {
                phpCAS::setNoCasServerValidation();
            }
            self::$initialized = true;
        }
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
    /*
     * Returns username
     */
    function getUser() {
        if (phpCAS::isAuthenticated()) {
            return phpCAS::getUser();
        }
        return null;
    }
    function getNick() {
        return phpCAS::getAttribute('displayName') ?: explode('@', phpCAS::getUser())[0];
    }
}
