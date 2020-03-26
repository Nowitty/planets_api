<?php
namespace App;

class Database
{
    public function __construct()
    {
        $opt = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        $this->pdo = new \PDO('pgsql:host=localhost;dbname=planetsDB', null, null, $opt);
    }
    public function insert($data)
    {
        $sql = '';
        foreach ($data as $value) {
            foreach ($value as $planet) {
                $sql .= "INSERT INTO planets VALUES ({$this->pdo->quote($planet)}); ";
            }
        }
        $this->pdo->exec($sql);
    }
    public function select($limit, $offset)
    {
        return $this->pdo->query("SELECT * FROM planets LIMIT $limit OFFSET $offset")->fetchAll(\PDO::FETCH_ASSOC);
    }
}