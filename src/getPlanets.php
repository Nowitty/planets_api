<?php

namespace Planets_Api\getPlanets;

require 'DIR' . '/../vendor/autoload.php';

use GuzzleHttp\Client;
function getAPI()
{
    $url = "https://swapi.co/api/planets/";
    $client = new Client();
    while (true) {
        $resp = $client->get($url);
        $resp = $resp->getBody();
        $resp = json_decode($resp, true);
        $data[] = array_map(function ($item) {
            return $item['name'];
        }, $resp['results']);
    if ($resp['next'] == null) {
        return $data;
    }
    $url = $resp['next'];
    }
}

function getPlanets()
{
    $opt = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
    $pdo = new \PDO('pgsql:host=localhost;dbname=ruslankuga', null, null, $opt);
    $data = getAPI();
    print_r($data);
    foreach ($data as $planet) {
        $pdo->exec("insert into planets values ('$planet')");
    }
    $result = $pdo->query("SELECT name FROM planets")->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
}
