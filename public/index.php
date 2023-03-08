<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/', function ($request, $response) {
    return $response->write('Hello, world');
});

$app->run();