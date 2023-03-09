<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\PhpRenderer;

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer('./templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->run();
