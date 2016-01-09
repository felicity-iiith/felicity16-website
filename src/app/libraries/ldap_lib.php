<?php

/**
 * LDAP Library
 */
class ldap_lib extends Library {

    public function get_link($value='') {
        global $ldap_cfg;

        $ds = ldap_connect($ldap_cfg["host"]);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        ldap_bind($ds, $ldap_cfg["bind_dn"], $ldap_cfg["bind_password"]);

        return $ds;
    }

}
