<?php
spl_autoload_register('loadControllers');
spl_autoload_register('loadClasses');
spl_autoload_register('loadServices');
spl_autoload_register('loadRepositories');

/**
 * Summary: Autoloads all needed files for app at runtime
 *
 * Description:
 * Custom autoloader for files that have custom-suffix
 * Registers given functions as __autoload() implementations
 * Add new autoload functions as it fits
 * Be sure to follow the naming convention for both folders and files
 * as it keeps the folder structure clean and files readable
 *
 * @version 2.0
 * @author Micke@tempory.org
 */

define('DEFAULT_DELIMITER', '.php');
define('CLASS_DELIMITER', '.class.php');
define('MODEL_DELIMITER', '.model.php');
define('CONTROLLER_DELIMITER', '.controller.php');
define('SERVICE_DELIMITIER', '.service.php');
define('REPOSITORY_DELIMITER', '.repository.php');

define('ROOT_DIRECTORY', dirname(__FILE__));

function determineDelimiter($folderName)
{
    switch ($folderName) {
        case 'classes':
            return CLASS_DELIMITER;
        case 'models':
            return MODEL_DELIMITER;
        case 'controllers':
            return CONTROLLER_DELIMITER;
        case 'services':
            return SERVICE_DELIMITIER;
        case 'repositories':
            return REPOSITORY_DELIMITER;
        default:
            return DEFAULT_DELIMITER;
    }
}

function load($name, $folderName)
{
    $delimiter = determineDelimiter($folderName);

    $name = $name . $delimiter;
    $dir = createPath(array(ROOT_DIRECTORY, $folderName), true);
    if (file_exists($dir . $name)) {
        require_once($dir . $name);
    }
}

function createPath($pathArray, $endingDirectorySeparator = true)
{
    $path = join(DIRECTORY_SEPARATOR, $pathArray);
    if ($endingDirectorySeparator) {
        $path = $path . DIRECTORY_SEPARATOR;
    }

    return $path;
}

function loadClasses($fileName)
{
    load($fileName, 'classes');
}

function loadControllers($fileName)
{
    $fileName = str_replace("Controller","", $fileName);
    load($fileName, 'controllers');
}

function loadModels($fileName)
{
    $fileName = str_replace("Model","", $fileName);
    load($fileName, 'models');
}

function loadRepositories($fileName)
{
    $fileName = str_replace("Repository","", $fileName);
    load($fileName, 'repositories');
}

function loadServices($fileName)
{
    $fileName = str_replace("Service","", $fileName);
    load($fileName, 'services');
}

spl_autoload_register('loadClasses');
spl_autoload_register('loadRepositories');
spl_autoload_register('loadServices');
spl_autoload_register('loadControllers');
spl_autoload_register('loadModels');

// spl_autoload_register('loadRepositories');
// spl_autoload_register('loadServices');
// spl_autoload_register('loadResources');
