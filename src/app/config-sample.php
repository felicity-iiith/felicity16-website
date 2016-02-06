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
        'contest' => [
            'db_host'   => 'localhost',
            'db_user'   => 'user',
            'db_pass'   => 'password',
            'db_name'   => 'dbname',
        ],
    ],

    'i18n' => [
        'locales' => [
            'en_IN',
            'hi_IN',
            'te_IN',
            'gu_IN'
        ],
        'languages' => [
            'en' => 'en_IN',
            'hi' => 'hi_IN',
            'te' => 'te_IN',
            'gu' => 'gu_IN'
        ],
        // 'gettext' => false,
        'gettext' => [
            'domain' => 'messages',
            'directory' => './locale'
        ]
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
        ],
        'noreply_threads'   => [
            'username'      => 'email_user',
            'password'      => 'email_user_pass',
            'email'         => 'email_address',
            'from_name'     => 'Team Felicity',
            'reply_to'      => 'email_reply_to',
            'reply_to_name' => 'Threads Team'
        ]
    ]
];

$ldap_cfg = [
    "host"          => "ldap://192.168.0.1",
    "bind_dn"       => "...",
    "bind_password" => "...",
];

$payment_cfg = [
    'url'   => '...',
    'salt'  => 'some-very-random-string'
];

$auth_cfg = [
    'magic_hosts' => '192.168.0.0/24',
]
