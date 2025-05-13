<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$path = $_SERVER['REQUEST_URI'];

if ('/ready' === $path) {
    return;
}

if ('/file-not-found' === $path) {
    echo 'File could not be found.';
} elseif ('/content' === $path) {
    echo 'UrlContentTest';
} elseif ('/http-cache-test' === $path) {
} elseif ('/create/from/uri' === $path) {
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="test.txt"');
} else {
    header('HTTP/1.1 404 Not Found');
}
