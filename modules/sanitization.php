<?php
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
function normalize_number($number) {
    $number = preg_replace('/^(\+98|0098|098|98)/', '0', trim($number));
    return preg_replace('/[^0-9]/', '', $number);
}

?>
