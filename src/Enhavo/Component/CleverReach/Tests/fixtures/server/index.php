<?php

$path = $_SERVER['REQUEST_URI'];

if ($path === '/ready') {
    return;
}

if ($path === '/oauth/token.php') {
    require __DIR__ . '/token.php';
} elseif ($path === '/action/test') {
    require __DIR__ . '/action.php';
} elseif ($path === '/server/error-500') {
    require __DIR__ . '/error-500.php';
} elseif ($path === '/server/error-404') {
    require __DIR__ . '/error-404.php';
} elseif ($path === '/server/error-403') {
    require __DIR__ . '/error-403.php';
} else {
    header("HTTP/1.1 404 Not Found");
}
