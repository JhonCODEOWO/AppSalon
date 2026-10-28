<?php

namespace Core\Dtos;

class AppointmentServicesDto {
    public int $serviceId;
    public string $hour;
    public string $clientName;
    public string $email;
    public int $phoneNumber;

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
            appointments.id as serviceId, 
            appointments.hour, 
            reservedBy.name as clientName, 
            reservedBy.email,
            reservedBy.phone_number as phoneNumber
            FROM appointments
            INNER JOIN users as reservedBy
            ON appointments.reservedBy = reservedBy.id
            WHERE appointments.estimated_date = ?;
        ";
        return $query;
    }
}