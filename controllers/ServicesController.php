<?php

namespace Controllers;

use Models\Services;

class ServicesController {
    public function index(){

    }

    public function listServices(){
        $services = Services::all();

        echo json_encode($services);
    }
}