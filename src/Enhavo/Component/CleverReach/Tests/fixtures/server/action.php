<?php

$body = file_get_contents('php://input');
$content = json_decode($body, true);

// check auth
if ($_SERVER["HTTP_AUTHORIZATION"] != "Bearer s3cr3t_acc3ss_token") {
    header("HTTP/1.1 403 Access Denied");
    exit();
}

echo json_encode([
    'test' => 'test'
]);
