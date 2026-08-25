<?php

namespace Models;

class User extends ActiveRecord {
    protected static $table = 'Users';
    protected static $columns = [
        'name', 
        'last_name', 
        'email', 
        'password', 
        'phone_number',
        'admin',
        'confirmed',
        'token',
    ];

    public int | null $id = null;
    public string | null $name = null;
    public string | null $last_name = null;
    public string | null $email = null;
    public string | null $password = null;
    public string | null $phone_number = null;
    public bool | null $admin = null;
    public bool | null $confirmed = null;
    public string | null $token = null;

    public function __construct($args = [])
    {
        foreach ($args as $key => $value) {
            if(!property_exists($this, $key)) continue;
            $this->$key = $value;
        }
    }
}