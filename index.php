<?php
// TODO: show error reporting (for debugging)
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Europe/Stockholm');

include_once './includes/fire-base-lib.php';

/*
 * By including ./includes/autoloader.php we load all needed
 * files for our app.
*/
include('./includes/autoloader.php');

/*
 * By including routes/Routes.php we get access to the $Routes
 * array containing all of the valid routes for our app.
*/
require_once( './includes/routes/Routes.php' );
