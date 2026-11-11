<?php

namespace Controllers;

use Core\Auth;
use Core\JustArray\JustArray;
use Core\Response;
use Core\Session;
use Core\Validator;
use Error;
use Exception;
use Models\Appointment;
use Models\AppointmentsServices;
use Models\Service;
use Routes\Request;

class ServicesController {
    public function index(){
        view("admin/services", [], "layouts/public");
    }

    public function listServices(Request $req){
        $services = Service::all();

        Response::json($services, 200);
    }

    public function create(Request $req){
        $errorMessage = Session::getPrevFlashData("error") ?? null;
        view(
            "admin/createService", 
            [
                "errorMessage" => $errorMessage
            ], 
            "layouts/public"
        );
    }

    public function storeService(Request $req){
        $validator = new Validator($req->getBody(), [
            "name" => "required",
            "price" => "required|float:2",
        ]);

        $errorBag = $validator->validate();

        if($errorBag->hasErrors()) redirectTo("/services/create");

        $service = new Service([
            "name" => JustArray::find($req->getBody(), "name"),
            "price" => JustArray::find($req->getBody(), "price"),
            "createdBy" => Auth::user()->id
        ]);
        
        try {
            $result = $service->save();
            if(!$result) {
                Session::flash("error", "The resource can't be created, try again or later.");
                redirectTo("/services/create");
            }
            redirectTo("/services");
        } catch (Exception $ex) {
            throw new Error($ex->getMessage());
        }
    }

    public function edit(Request $req){
        view("admin/editService", [], "layouts/public");
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