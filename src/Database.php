<?php
namespace App;

class Database
{
    public function __construct()
    {
        $opt = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        $this->pdo = new \PDO('pgsql:host=localhost;dbname=ruslankuga', null, null, $opt);
    }
    public function insert($data)
    {
        //var_dump($data);
        //$sth = $this->pdo->prepare("INSERT INTO planets VALUES (?)");
        foreach ($data as $value) {
            $this->pdo->exec("INSERT INTO planets VALUES ('$value')");
        }
    }
    public function select()
    {
        return $this->pdo->query("SELECT * FROM planets")->fetchAll(\PDO::FETCH_ASSOC);
    }
}