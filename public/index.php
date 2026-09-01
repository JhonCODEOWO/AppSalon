<?php

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
    [AuthController::class, 'sendMail']
);
$router->get(
    "/reset-password/:token",
    [AuthController::class, 'reset']
);
$router->post(
    "/reset-password/:token",
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

//Handling the incoming request.
$router->checkRoutes();