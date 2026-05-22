<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'config/config.php';
require_once 'autoload.php';
require_once 'helpers/validation.php';
require_once 'helpers/csrf.php';

$entity = $_GET['entity'] ?? 'client';
$action = $_GET['action'] ?? 'list';

$controllerName =
    ucfirst($entity) . 'Controller';

if (!class_exists($controllerName)) {
    die('Controller not found');
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die('Action not found');
}

$controller->$action();