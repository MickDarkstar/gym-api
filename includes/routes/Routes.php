<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Todo: proper router with less redundancy of code. Get rid of switch-cases and $httpRequest = $_SERVER['REQUEST_METHOD'];
Route::set('index.php', function () {
    IndexController::Home();
});

Route::set('users', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();

    switch ($httpRequest) {
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
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
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        case 'POST':
            $controller->Login();
            break;
        default:
            echo Response::MethodNotAllowed();
            break;
    }
});

Route::set('exercises', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new ExerciseController();

    switch ($httpRequest) {
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        case 'GET':
            $controller->All();
            break;
        case 'POST':
            $controller->Create();
            break;
        case 'PUT':
            $controller->Update();
            break;
        case 'DELETE':
            $controller->Delete();
            break;

        default:
            echo Response::MethodNotAllowed();
            break;
    }
});

Route::set('entrydetails', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();

    switch ($httpRequest) {
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        case 'GET':
            $controller->All();
            break;
        case 'POST':
            $controller->CreateEntryDetail();
            break;
        case 'PUT':
            $controller->UpdateEntryDetail();
            break;
        case 'DELETE':
            $controller->DeleteEntryDetail();
            break;

        default:
            echo Response::MethodNotAllowed();
            break;
    }
});

Route::set('entries', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();
    switch ($httpRequest) {
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        case 'GET':
            $controller->All();
            break;
        case 'POST':
            $controller->CreateEntry();
            break;
        case 'PUT':
            $controller->UpdateEntry();
            break;
        case 'DELETE':
            $controller->DeleteEntry();
            break;

        default:
            echo Response::MethodNotAllowed();
            break;
    }
});

Route::set('currententry', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();
    switch ($httpRequest) {
        case 'OPTIONS':
            echo Response::OK("Preflight OK!");
            break;
        case 'GET':
            $controller->Current();
            break;

        default:
            echo Response::MethodNotAllowed();
            break;
    }
});
