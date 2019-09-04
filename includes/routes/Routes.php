<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

Route::set('index.php', function () {
    IndexController::Home();
});

Route::set('users', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();

    switch ($httpRequest) {
        case 'GET':
            $controller->AllUsers();
            break;

        case 'POST':
            $controller->NewUser();
            break;

        case 'PUT':
            $controller->UpdateUserinfo();
            break;

        default:
            echo Response::MethodNotAllowed();
            break;
    }
});

Route::set('login', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();

    switch ($httpRequest) {
        case 'POST':
            $controller->Login();
            break;
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        default:
            echo Response::MethodNotAllowed();
            break;
    }
});
