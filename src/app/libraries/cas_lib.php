<?php

/**
 * CAS Library
 */
class cas_lib extends Library {

    private static $initialized = false;

    function __construct() {
        if (!self::$initialized) {
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
            } else {
                phpCAS::setNoCasServerValidation();
            }

            setcookie('org.springframework.web.servlet.i18n.CookieLocaleResolver.LOCALE', explode('_', setlocale(LC_ALL, '0'))[0], 0, '/');
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

    function getUser() {
        if (phpCAS::isAuthenticated()) {
            return phpCAS::getUser();
        }
        return null;
    }

    function getAttributes() {
        if (phpCAS::isAuthenticated()) {
            return phpCAS::getAttributes();
        }
        return null;
    }
}
