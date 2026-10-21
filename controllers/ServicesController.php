<?php

namespace Controllers;

use Core\JustArray\JustArray;
use Core\Response;
use Core\Validator;
use Models\Appointment;
use Models\AppointmentsServices;
use Models\Services;
use Routes\Request;

class ServicesController {
    public function index(){

    }

    public function listServices(Request $req){
        $services = Services::all();

        Response::json($services, 200);
    }

    public function store(Request $req){
        $body = $req->getBody();
        $validation = new Validator($body, [
            "estimated_date" => "required",
            "hour" => "required",
            "reservedBy" => "required|int|exists:users,id",
            "services" => "required|array|inArrayType:int"
        ]);

        $errorBag = $validation->validate();

        if($errorBag->hasErrors()) Response::json($errorBag->all(), 400);

        //Prepare the instance before create a appointment
        $appointment = new Appointment([
            "estimated_date" => JustArray::find($body, 'estimated_date'),
            "hour" => JustArray::find($body, 'hour'),
            "reservedBy" => JustArray::find($body, 'reservedBy')
        ]);

        //Create appointment and relate it with every service requested.
        $idAppointment = $appointment->save()->id;


        foreach (JustArray::find($body, 'services') as $serviceID) {
            $appserv = new AppointmentsServices([
                "appointmentId" => $idAppointment,
                "serviceId" => $serviceID
            ]);

            $appserv->save();
        }

        Response::json(["success" => true], 200);
    }
}