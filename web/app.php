<?php


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/bootstrap.php.cache';

$environment = new Environment();

if($environment->isDebug()) {
    Debug::enable();
}

Request::enableHttpMethodParameterOverride();

$kernel = new AppKernel($environment->getEnvironment(), $environment->isDebug());
if($environment->getEnvironment() === Environment::PROD) {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
