<?php

namespace Controllers;

use Core\Auth;
use Core\Database;
use Core\Dtos\AppointmentServicesDto;
use Core\Response;
use Exception;
use Routes\Request;

class AppointmentController {
    public function index(Request $req) {
        $query = AppointmentServicesDto::queryString();
        $dateToSearch = $req->getQueryParam('dateToSearch') ?? date('Y-m-d');

        try {
            $stmt = Database::prepare(
                $query, 
                ["s", [$dateToSearch]]
            );

            $result = Database::execute($stmt);

            $appointments = [];

            foreach ($result->fetch_all(MYSQLI_ASSOC) as $key => $value) {
                $appointments[] = new AppointmentServicesDto($value);
            }

            if(count($appointments) === 0) Response::json($appointments, 404);

            Response::json($appointments, 200);
        } catch (Exception $ex) {
            debug($ex->getMessage());
        }
    }
    public function create(){
        $user = Auth::user();
        view(
            "Appointments/create",
            [
                "user" => $user,
            ],
            "layouts/public",
        );
    }
}