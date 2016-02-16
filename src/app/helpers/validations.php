<?php
function is_valid_phone_number($number) {
    $digits = str_replace("+", "", str_replace(" ", "", $number));
    return preg_match("/^\+?[\d ]+$/", $number) && strlen($digits) >= 10;
}

function is_valid_url($url) {
    return preg_match("/^https?:\/\/.+\..+/", $url);
}

function required_post_params($fields, &$errors) {
    foreach ($fields as $name) {
        if (empty(@trim($_POST[$name]))) {
            $errors[$name] = __('↑ This is a required field');
        }
    }
}
