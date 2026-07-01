#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Dagger\Command\EntrypointCommand;

if (file_exists(__DIR__.'/../../autoload.php')) {
    require __DIR__.'/../../autoload.php';
} else {
    require __DIR__.'/vendor/autoload.php';
}

$console = new Application();

$console->add(new EntrypointCommand());
$console->setDefaultCommand('dagger:entrypoint');

$console->run();
