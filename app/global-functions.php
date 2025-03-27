<?php

function debug($data, $end = false)
{
    if ($end) die(print_r('<pre>' . print_r($data, true) . '</pre>'));
    return print_r('<pre>' . print_r($data, true) . '</pre>');
}

function redirect($route)
{
    header('Location: ' . HOST . $route);
    exit;
}

function response($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode($data, JSON_PRETTY_PRINT));
    exit;
}
