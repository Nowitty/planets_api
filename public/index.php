<?php

// Подключение автозагрузки через composer
require 'DIR' . '/../vendor/autoload.php';

use App\Database;
use Slim\Factory\AppFactory;
use DI\Container;
use App\PlanetsApi;

$test = new PlanetsApi();
$dataClient = new Database();


$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);


$app->get('/', function ($request, $response) use ($test) {
    return $this->get('renderer')->render($response, 'show.phtml');
});

$app->get('/planets', function ($request, $response) use ($test) {
    $bd = new Database();
    $data = $bd->select();
    $planets = array_map(function ($item) {
        return $item['name'];
    }, $data);
    print_r($planets);
    return $this->get('renderer')->render($response, 'planets.phtml');
});

$app->post('/load_planets', function ($request, $response) use ($dataClient) {
    $test = new PlanetsApi();
    $planets = $test->getPlanets();
    $dataClient->insert($planets);
    return $response->withRedirect('/planets');
});

$app->run();