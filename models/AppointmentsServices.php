<?php

namespace Models;

class AppointmentsServices extends ActiveRecord{
    protected static string $table = 'appointmentsservices';
    protected static array $columns = ['appointmentId', 'serviceId'];
    protected static string $idName = 'id';

    public int $id;
    public int $appointmentId;
    public int $serviceId;

    public function __construct(array $args = [])
    {
        foreach ($args as $prop => $value) {
            if(!property_exists($this,$prop)) continue;

            $this->$prop = $value;
        }
    }
}