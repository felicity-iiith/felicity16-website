<?php

$cfg = [
    'default_controller'    => 'hello',
    'default_method'        => 'index',

    'base_url'  => '',

    'databases' => [
        'jugaad' => [
            'db_host'   => 'localhost',
            'db_user'   => 'user',
            'db_pass'   => 'password',
            'db_name'   => 'dbname',
        ],
        'users' => [
            'db_host'   => 'localhost',
            'db_user'   => 'user',
            'db_pass'   => 'password',
            'db_name'   => 'dbname',
        ],
        'sap' => [
            'db_host'   => 'localhost',
            'db_user'   => 'user',
            'db_pass'   => 'password',
            'db_name'   => 'dbname',
        ],
    ],
    '404_view' => 'http_error',
    '404_data' => ['error_code' => 404],
];

$admins = [
    // List of user ids of admins
];

$cas_cfg = [
    'host'           => 'login.iiit.ac.in',
    'context'        => '/cas',
    'port'           => 443,
    'server_ca_cert' => APPPATH . 'iiit.ac.in.pem', // Optional, Recommended.
];

$email_cfg = [
    'server_host'   => 'email_server_host_ip',
    'server_domain' => 'email.server.domain.name',
    'server_port'   => 25,
    'accounts'      => [
        'noreply'   => [
            'username'  => 'email_user',
            'password'  => 'email_user_pass',
            'email'     => 'email_address',
            'from_name' => 'Team Felicity'
        ]
    ]
];
