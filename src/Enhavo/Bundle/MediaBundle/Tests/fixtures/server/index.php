<?php

$path = $_SERVER['REQUEST_URI'];

if ($path === '/ready') {
    return;
}

if ($path === '/file-not-found') {
    echo "File could not be found.";
} else if ($path === '/content') {
    echo "UrlContentTest";
} else if ($path === '/http-cache-test') {
} else if ($path === '/create/from/uri') {
    header("Content-Type: text/plain");
    header('Content-Disposition: attachment; filename="test.txt"');
} else {
    header("HTTP/1.1 404 Not Found");
}
