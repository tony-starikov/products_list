<?php
// FRONT CONTROLLER

// GLOBAL SETTINGS
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ADD FILES
define('ROOT', dirname(dirname(__FILE__)));
require_once(ROOT . "/components/Autoload.php");

// ROUTING
$router = new Router();
$router->run();