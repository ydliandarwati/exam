<?php

namespace Controllers;

class UserController{

    // to to auth page: login or register
    public function goAuth() {
        $template = 'auth.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show registration form
    public function goRegister() : void {
        $template = 'register.phtml';
        include_once 'views/layout.phtml';
    }
    
    // show login from
    public function goLogin() : void {
        $template = 'login.phtml';
        include_once 'views/layout.phtml';
    }
    
    // register user using data in the form
    public function register() : void {
        $model = new \Models\User();
        $model->register();
    }
    
    // login the user
    public function login() : void {
        $model = new \Models\User();
        $model->login();
    }
    
    // logout the user
    public function logout() : void {
        $model = new \Models\User();
        $model->logout();
    }
}
