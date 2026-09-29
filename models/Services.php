<?php

namespace Models;

class Services extends ActiveRecord{
    protected static string $table = 'services';
    protected static array $columns = [];
    protected static string $idName = 'id';

    public int | null $id;
    public string | null $name;
    public float | null $price;
    public int | null $createdBy;

    public function __construct(array $args = [])
    {
        foreach ($args as $propertyArg => $propertyValue) {
            if(!property_exists($this, $propertyArg)) continue;

            $this->$propertyArg = $propertyValue ?? null;
        }
    }
}