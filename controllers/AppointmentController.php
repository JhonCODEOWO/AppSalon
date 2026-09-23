<?php

namespace Controllers;

use Core\Auth;
use Core\Session;
use Models\User;

class AppointmentController {
    public function create(){
        $user = Auth::user(User::class);
        view(
            "Appointments/create",
            [
                "user" => $user,
            ],
            "layouts/public",
        );
    }
}