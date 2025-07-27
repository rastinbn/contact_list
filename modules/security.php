<?php

function clean_input($data, $max_length = 255) {
    $data = strip_tags(trim($data));
    $data = substr($data, 0, $max_length);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
function validate_name($name) {
    return preg_match('/^[a-zA-Z]{2,50}$/', $name);
}
function clean_phone($number) {
    $number = trim($number);
    $number = str_replace(['+98', '0098'], '', $number);
    $number = preg_replace('/\D/', '', $number);
    return substr($number, 0, 15);
}
function is_valid_phone($number) {
    return preg_match('/^9\d{9}$|^09\d{9}$/', $number);
}