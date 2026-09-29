<?php

namespace Models;

class Appointment extends ActiveRecord {
    protected static string $table = "appointments";
    protected static array $columns = ["name", "price"];
    protected static string $idName = 'id';

    public int $id;
    public string $estimated_date;
    public string $hour;
    public int $reservedBy;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->estimated_date = $args['estimated_date'] ?? "";
        $this->hour = $args['hour'] ?? '';
        $this->reservedBy = $args['price'] ?? 0;
    }
}