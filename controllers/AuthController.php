<?php

namespace Controllers;

use Routes\Request;

class AuthController {
    public function login(Request $req){
        view(
            'Auth/login', 
            [],
            'layouts/main'
        );
    }

    public function loginUser(Request $req){

    }

    public function logout(Request $req){

    }

    public function forgotPassword(){

    }

    public function sentMail(Request $req){

    }

    public function reset(Request $req){

    }

    public function resetPassword(Request $req){

    }

    public function create(){
        
    }

    public function store(Request $req){

    }
}