<?php

/**
* Contains routes
* @var array
*/
$routes = [
    "/trash/"       => "/jugaad/trash/",
    "/jugaad/"      => "/jugaad/read/",
    "/ajax/"        => "/ajax/",
    "/auth/"        => "/auth/",

    "/login/"       => "/static_page/login/",
    "/logout/"      => "/static_page/logout/",
    "/register/"    => "/static_page/register/",

    "/sap/portal/mission/create/"       => "/sap_portal/create_mission/",
    "/sap/portal/review/mission/"       => "/sap_portal/review_mission/",
    "/sap/portal/review/submission/"    => "/sap_portal/review_submission/",
    "/sap/portal/users/approve/"        => "/sap_portal/approve_user/",
    "/sap/portal/users/remove/"         => "/sap_portal/remove_user/",
    "/sap/portal/users/resend-password-email/" => "/sap_portal/resend_password_email/",
    "/sap/portal/users/"                => "/sap_portal/confirm_users/",
    "/sap/portal/" => "/sap_portal/",
    "/sap/" => "/sap/",

    "/" => "/page/show/"
];
