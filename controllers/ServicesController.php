<?php

namespace Controllers;

use Models\Services;
use Routes\Request;

class ServicesController {
    public function index(){

    }

    public function listServices(Request $req){
        $services = Services::all();

        echo json_encode($services);
    }
}