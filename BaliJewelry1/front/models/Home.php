<?php
namespace Models;

class Home extends Database {
    
    // get 3 featured categories to show in homepage    
    public function getSelectedCategories() : array {
        $sql = "SELECT * FROM category LIMIT 3";
        return $this->findAll($sql);
    }
    
    // get all categories
    public function getAllCategories() : array {
        $sql = "SELECT * FROM category";
        return $this->findAll($sql);
    }
    
    // get all items in collection
    public function getAllCollections() : array {
        $sql = "SELECT * FROM collection";
        return $this->findAll($sql);
    }
    
    
    // get all collection items belong to one category based on Id
    public function getCategoryItems(string $title) : array {
        $sql = "SELECT * FROM collection WHERE category_name=?";
        return $this->findAll($sql, [$title]);
    }
    
    
    public function getSearch($keyword) : array {
        $sql = "SELECT * FROM collection WHERE title LIKE :title";
        $query = $this->bdd->prepare($sql);
        $query->bindParam(':title', $keyword, \PDO::PARAM_STR);
        $query->execute(); 
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data;
    }
}
