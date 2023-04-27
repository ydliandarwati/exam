<?php
// General methods used in other models like find, findall, as well as connection to BD via contructor

namespace Models;

class Database {

    protected $bdd;

    // connection to DB
    public function __construct() {
        $this->bdd = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    }
    
    
    protected function getOneById(string $req, $params = []) : array{
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }
    
    protected function findAll(string $req, $params = []) : array {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data;
    }

    // count all entries in $tbl
    protected function countEntries(string $tbl) : int {
        $req = "SELECT * FROM $tbl";
        $query = $this->bdd->prepare($req);
        $query->execute();
        return $query->rowCount();
    }
    
    // count unique entries in $column of table $tbl
    protected function countUniqueEntries(string $tbl, string $column) : int {
        $req = "SELECT DISTINCT $column FROM $tbl";
        $query = $this->bdd->prepare($req);
        $query->execute();
        return $query->rowCount();
    }
    
}