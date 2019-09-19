<?php

include('./config.php');
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // TODO: show error reporting (for debugging)
    error_reporting(E_ALL);
    Config::$mode = 'dev';
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
