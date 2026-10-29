<?php

namespace Core\Dtos;

use Models\Services;

class AppointmentServicesDto {
    public int $appointmentId;
    public string $hour;
    public string $clientName;
    public string $email;
    public int $phoneNumber;
    public array $services;

    public function __construct(array $args)
    {
        foreach ($args as $prop => $value) {
            if(!property_exists($this,$prop)) continue;

            $this->$prop = $value;
        }
    }

    public static function queryString() {
        $query = "
            SELECT 
            appointments.id as appointmentId, 
            appointments.hour, 
            reservedBy.name as clientName, 
            reservedBy.email,
            reservedBy.phone_number as phoneNumber,
            services.name as serviceName,
            services.price as servicePrice,
            services.id as serviceId
            FROM appointments
            INNER JOIN users as reservedBy
            ON appointments.reservedBy = reservedBy.id
            inner join appointmentsservices 
            on appointmentsservices.appointmentId  = appointments.id 
            inner join services
            on services.id = appointmentsservices.serviceId 
            WHERE appointments.estimated_date = ?;
            ";
            return $query;
    }
    
    public function setService(array $args){
        $service = new Services($args);

        $this->services[] = $service;
    }
}