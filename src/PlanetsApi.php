<?php

namespace App;

require 'DIR' . '/../vendor/autoload.php';

use GuzzleHttp\Client;

class PlanetsApi
{
    private $url = "https://swapi.co/api/planets/";
    private $dataPlanets;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function getPlanets()
    {
        $resp = $this->client
            ->get($this->url)
            ->getBody();
        $resp = json_decode($resp, true);
        $this->url = $resp['next'];
        $data = array_map(function ($item) {
            return $item['name'];
        }, $resp['results']);
        return $data;
    }
}
