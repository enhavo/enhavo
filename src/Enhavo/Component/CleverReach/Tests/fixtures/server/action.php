<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$body = file_get_contents('php://input');
$content = json_decode($body, true);

// check auth
if ('Bearer s3cr3t_acc3ss_token' != $_SERVER['HTTP_AUTHORIZATION']) {
    header('HTTP/1.1 403 Access Denied');
    exit;
}

echo json_encode([
    'test' => 'test',
]);
