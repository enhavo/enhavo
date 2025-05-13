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

if ('/oauth/token.php' === $path) {
    require __DIR__.'/token.php';
} elseif ('/action/test' === $path) {
    require __DIR__.'/action.php';
} elseif ('/server/error-500' === $path) {
    require __DIR__.'/error-500.php';
} elseif ('/server/error-404' === $path) {
    require __DIR__.'/error-404.php';
} elseif ('/server/error-403' === $path) {
    require __DIR__.'/error-403.php';
} else {
    header('HTTP/1.1 404 Not Found');
}
