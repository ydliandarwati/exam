<?php

namespace Controllers;

class AdminController {

    // add admin
    public function addAdmin() {
        $model = new \Models\Admin();
        $model->addAdmin();
    }
    
    // update existing admin
    public function updateAdmin() {
        $model = new \Models\Admin();
        $model->updateAdmin();
    }
    
    // update password
    public function updatePassword() {
        $model = new \Models\Admin();
        $model->updatePassword();
    }
    
    // delete an existing admin
    public function deleteAdmin(string $id) {
        $model = new \Models\Admin();
        $model->deleteAdmin($id);
    }
    
    // get all admins to show for manage-admin view
    public function getAllAdmins() {
        $model = new \Models\Admin();
        $data = $model->getAllAdmins();
        $template = 'manage-admin.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show update admin view with pre-filled info from id
    public function goUpdateAdmin(string $id) {
        $model = new \Models\Admin();
        $data = $model->getAdminById($id);
        $template = 'update-admin.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show add admin view
    public function goAddAdmin() {
        $template = 'add-admin.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show update passowrd view
    public function goUpdatePassword(string $id) {
        $model = new \Models\Admin();
        $template = 'update-password.phtml';
        include_once 'views/layout.phtml';
    }
    
    // get admin info from id
    public function getAdminById(string $id) : array {
        $model = new \Models\Admin();
        return $model->getAdminById($id);
    }
    
    // go to home view: needs the count of orders/collections/categories
    public function goHome() {
        $model = new \Models\Admin();
        list($ordCount, $catCount, $colCount) = $model->countDashboard();
        $data = $model->conterOrderByCategory();
        $template = 'home.phtml';
        include_once 'views/layout.phtml';
    }
    
    // login the admin
    public function login() {
        $model = new \Models\Admin();
        $model->login();
    }
    
    // go to login view
    public function goLogin() {
        include_once 'views/login.phtml';
    }
    
    // logout user
    public function logout() {
        $model = new \Models\Admin();
        $model->logout();
    }

   
}
