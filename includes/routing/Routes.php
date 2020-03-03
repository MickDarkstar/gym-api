<?php
require_once('./includes/routing/Router.php');
// Todo: proper Router with less redundancy of code. Get rid of switch-cases and get following method once: $httpRequest = $_SERVER['REQUEST_METHOD'];
// Would rather pass request_method from index then setting a global variable..
Router::set('index.php', function () {
    IndexController::Home();
});

Router::set('users', function () {
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
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('login', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();

    switch ($httpRequest) {
        case 'POST':
            $controller->Login();
            break;
        default:
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('muscles', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new MuscleController();

    switch ($httpRequest) {
        case 'GET':
            $controller->All();
            break;

        default:
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('exercises', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new ExerciseController();

    switch ($httpRequest) {
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
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('entries', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();
    switch ($httpRequest) {
        case 'GET':
            $controller->AllEntries();
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
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('entrydetails', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();

    switch ($httpRequest) {
        case 'GET':
            $controller->AllEntries();
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
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});

Router::set('currententry', function () {
    $httpRequest = $_SERVER['REQUEST_METHOD'];
    $controller = new EntryController();
    switch ($httpRequest) {
        case 'GET':
            $controller->Current();
            break;
        default:
            echo ApiResponse::MethodNotAllowed();
            break;
    }
});
