<?php

// Подключение автозагрузки через composer
require 'DIR' . '/../vendor/autoload.php';

use App\Database;
use Slim\Factory\AppFactory;
use DI\Container;
use App\PlanetsApi;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);


$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'show.phtml');
});

$app->get('/planets', function ($request, $response) {
    $per = 9;
    $page = $request->getQueryParam('page', 1);
    $offset = ($page - 1) * $per;
    $bd = new Database();
    $data = $bd->select($per, $offset);
    $mapped = array_map(function ($item) {
        return $item['name'];
    }, $data);
    $params = [
        'page' => $page,
        'planets' => $mapped
    ];
    return $this->get('renderer')->render($response, 'planets.phtml', $params);
});

$app->post('/load_planets', function ($request, $response) {
    $dataClient = new Database();
    $test = new PlanetsApi();
    $planets = $test->getPlanets();
    $dataClient->insert($planets);
    return $response->withRedirect('/planets');
});

$app->run();