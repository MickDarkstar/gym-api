<?php

include('./config.php');
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // TODO: show error reporting (for debugging)
    error_reporting(E_ALL);
    Config::$mode = 'dev';
}

/*
 *
 *
 * Move this to CORS.handler.php
 * 
*/
 
// Allow from any origin
// if (isset($_SERVER['HTTP_ORIGIN'])) {
//     // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
//     // whitelist of safe domains
//     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//     header('Access-Control-Max-Age: 86400');    // cache for 1 day
// }
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Already set in .htaccess, try this later
    // if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //     header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
     http_response_code(200);
     echo "Preflight OK!";
}

// set your default time-zone
date_default_timezone_set('Europe/Stockholm');

include_once './includes/fire-base-lib.php';

/*
 * By including ./includes/autoloader.php we require_once all the needed
 * files for our app. 
 * Files we build this app with, such as: classes, controllers, models, repositories, services
*/
include('./includes/autoloader.php');

/*
 * By including Routes we can setup an array containing 
 * all of the valid routes for our app and point each route 
 * to a specifik *Controller and method in that controller
*/
require_once('./includes/routing/Routes.php');
