<?php

$cfg = array(
    'default_controller'    => 'hello',
    'default_method'        => 'index',

    'base_url'              => '',

    'db_host'               => '',
    'db_user'               => '',
    'db_pass'               => '',
    'db_name'               => '',
);

$cas_cfg = array(
    'host'           => 'login.iiit.ac.in',
    'context'        => '/cas',
    'port'           => 443,
    'server_ca_cert' => APPPATH . 'iiit.ac.in.pem', // Optional, Recommended.
);
