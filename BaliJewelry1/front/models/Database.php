<?php
// General methods used in other models like find, findall, as well as connection to BD via contructor

namespace Models;

class Database {

    protected $bdd;

    // connection to DB
    public function __construct() {
        $this->bdd = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    }
    
    
    protected function getOneById(string $req, $params = []) : array {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }
    
    // get from $table where $columnn is equal to $id
    protected function getOne(string $table, string $columns, string $values, $params=[]) : array{
        $query = $this->bdd->prepare("SELECT * FROM" . $table . "WHERE". '(' . $columns . ') values (' . $values . ')');
        $query->execute([$params]);
        $data = $query->fecth();
        $query->closeCursor();
        return $data;
    }
    
    
    protected function findAll(string $req, $params = []) : array {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data;
    }
    

}