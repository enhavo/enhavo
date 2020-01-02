<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/../vendor/autoload.php';

$_SERVER['APP_ENV'] = 'test';
$_SERVER['APP_DEBUG'] = true;
(new Dotenv())->loadEnv(dirname(__DIR__).'/../.env');
