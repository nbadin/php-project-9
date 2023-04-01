<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\PhpRenderer;
use Valitron\Validator;
// use Slim\Flash\Messages;
use Hexlet\Code\UrlRepo;
use Hexlet\Code\Url;

session_start();

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer('../templates');
});
$container->set('flash', function () {
    return new Slim\Flash\Messages();
});

$dataRepo = new UrlRepo();

AppFactory::setContainer($container);
$app = AppFactory::create();
$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, './index.phtml');
})->setName('main');

$app->get('/urls', function ($request, $response) use ($dataRepo) {
    $urls = array_map(fn($urlData) => new Url($urlData), $dataRepo->getAllUrls());
    return $this->get('renderer')->render($response, './sites.phtml', ['urls' => $urls]);
})->setName('urls');

$app->get('/urls/{id}', function ($request, $response, array $args) use ($dataRepo) {
    $id = (int) $args['id'];
    $url = new Url($dataRepo->findUrlById($id));
    $message = $this->get('flash')->getMessage('success')[0] ?? false;
    $params = [
        'url' => $url,
        'message' => $message
    ];
    return $this->get('renderer')->render($response, './url.phtml', $params);
})->setName('url');

$app->post('/', function ($request, $response) use ($dataRepo, $router) {
    $url = $request->getParsedBodyParam('url');
    if ($url['name'] === '') {
        $error = 'URL не должен быть пустым ';
        return $this->get('renderer')->render($response, './index.phtml', ['error' => $error]);
    }

    $validator = new Validator(array('url' => $url['name']));
    $validator->rule('url', 'url')->rule('urlActive', 'url');
    $isValid = $validator->validate();
    if (!$isValid) {
        $error = 'Некорректный URL';
        return $this->get('renderer')->render($response, './index.phtml', ['error' => $error]);
    }

    $urlParts = parse_url($url['name']);
    $urlName = "{$urlParts['scheme']}://{$urlParts['host']}";

    $existsUrl = $dataRepo->findUrlByName($urlName);
    if ($existsUrl) {
        $id = $existsUrl['id'];
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->urlFor('url', ['id' => $id]));
    }

    $dataRepo->saveUrl($urlName);
    $id = $dataRepo->findUrlByName($urlName)['id'];
    $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
    return $response->withRedirect($router->urlFor('url', ['id' => $id]));
});

$app->run();
