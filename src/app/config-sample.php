<?php

$cfg = array(
    'default_controller'    => 'hello',
    'default_method'        => 'index',

    'base_url'  => '',

    'databases' => array(
        'jugaad' => array(
            'db_host'   => 'localhost',
	    'db_user'   => 'user',
	    'db_pass'   => 'password',
            'db_name'   => 'dbname',
        )
    ),
);

$admins = [
    // List of user ids of admins
];

$cas_cfg = array(
    'host'           => 'login.iiit.ac.in',
    'context'        => '/cas',
    'port'           => 443,
    'server_ca_cert' => APPPATH . 'iiit.ac.in.pem', // Optional, Recommended.
);
