<?php

use Controllers\AdminController;
use Controllers\AppointmentController;
use Controllers\AuthController;
use Core\JustArray\JustArray;
use Routes\Router;

require_once __DIR__ . '/../includes/app.php';

$router = new Router();

//Registering routes.
$router->get(
    '/login', 
    [AuthController::class, 'login']
);
$router->post(
    '/login', 
    [AuthController::class, 'loginUser']
);
$router->post(
    "/logout",
    [AuthController::class, 'logout']
);

$router->get(
    "/forgot-password",
    [AuthController::class, 'forgotPassword']
);
$router->post(
    "/forgot-password",
    [AuthController::class, 'sendResetMail']
);
$router->get(
    "/reset-password/{token}",
    [AuthController::class, 'reset']
);
$router->post(
    "/reset-password",
    [AuthController::class, 'resetPassword']
);


$router->get(
    "/create-account",
    [AuthController::class, 'create']
);
$router->post(
    "/create-account",
    [AuthController::class, 'store']
);
$router->get(
    "account-created",
    [AuthController::class, 'accountCreated'],
);
$router->get(
    "/confirm-account/{token}",
    [AuthController::class, 'confirm']
);
$router->post(
    "/confirm-account",
    [AuthController::class, 'confirmAccount']
);

//Basic User routes.
$router->get("/createAppointment", [AppointmentController::class, "create"]);

//Admin routes
$router->get("/panel", [AdminController::class, "index"]);

//Handling the incoming request.
$router->checkRoutes();