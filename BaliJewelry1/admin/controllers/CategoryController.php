<?php

namespace Controllers;

class CategoryController {

    // get all categories for manage-categoty view
    public function getAllCategories() : void {
        $model = new \Models\Category();
        $data = $model->getAllCategories();
        $template = 'manage-category.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show add category view
    public function goAddCategory() : void {
        $template = 'add-category.phtml';
        include_once 'views/layout.phtml';
    }
    
    // add category in database
    public function addCategory() : void {
        $model = new \Models\Category();
        $model->addCategory();
    }
    
    // delete an existing category
    public function deleteCategory(string $id) : void {
        $model = new \Models\Category();
        $model->deleteCategory($id);
    }
    
    // show update category view with pre-filled info from id
    public function goUpdateCategory(string $id) : void {
        $model = new \Models\Category();
        $data = $model->getCategoryById($id);
        $template = 'update-category.phtml';
        include_once 'views/layout.phtml';
    }
    
    // update category in database
    public function updateCategory() : void {
        $model = new \Models\Category();
        $model->updateCategory();
    }
}
