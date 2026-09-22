<?php


namespace Controllers;
use Routes\Request;

class AdminController {
    public function index(){
        view("Admin/index", [], "");
    }
    public function createAppointment(Request $req){
        view(
            "Admin/createAppointment",
            [

            ],
            ""
        );
    }
}