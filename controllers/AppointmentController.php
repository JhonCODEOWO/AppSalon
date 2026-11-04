<?php

namespace Controllers;

use Core\Auth;
use Core\Database;
use Core\Dtos\AppointmentServicesDto;
use Core\Response;
use Core\Validator;
use Exception;
use Models\Appointment;
use Routes\Request;

class AppointmentController {
    public function index(Request $req) {
        $query = AppointmentServicesDto::queryString();
        $dateToSearch = $req->getQueryParam('dateToSearch') ?? date('Y-m-d');

        $validator = new Validator([
            "dateToSearch" => $dateToSearch
        ], [
            "dateToSearch" => "date:-"
        ]);

        $errors = $validator->validate();
        
        if($errors->hasErrors()){
            Response::json($errors->all(), 400);
        };

        try {
            $stmt = Database::prepare(
                $query, 
                ["s", [$dateToSearch]]
            );

            $result = Database::execute($stmt);

            $appointments = [];
            $prev = null;
            $current = null;

            foreach ($result->fetch_all(MYSQLI_ASSOC) as $key => $value) {
                    //If prev is different than actual id value in the record
                    if ($prev !== $value['appointmentId']) {

                        //Prepare the instance to reutilize in next iterations as required.
                        $current = new AppointmentServicesDto($value);
                        
                        //Append new instance to the array of results.
                        $appointments[] = $current;

                        //Set the $prev value which means every next record could be different.
                        $prev = $value['appointmentId'];
                    }
                    
                    //Make operations reutilizing $current instance.

                    $current->setService([
                        "id" => $value['serviceId'],
                        "name" => $value['serviceName'],
                        "price" => $value['servicePrice'],
                    ]);
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

    public function cancel(Request $req){
        $success = false;
        $id = $req->getUrlParamValue("id");
        $validator = new Validator(["id" => $id], [
            "id" => "exists:appointments,id"
        ]);

        $errors = $validator->validate();


        if($errors->hasErrors()) 
            Response::json(["success" => $success, "errors" => $errors->getAllFrom("id")], 400);

        $appointment = Appointment::find($id);
        $success = $appointment->delete();

        Response::json(["success" => $success], 200);
    }
}