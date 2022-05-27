<?php

declare(strict_types=1);

namespace App\Api;

use App\Src\Classes\CoffeeMachineApi;
use App\Src\Classes\CoffeeMachineController;
use App\Src\Classes\CoffeeMachineGateway;
use App\Src\Classes\MySQLDatabase;
use \Dotenv\Dotenv;

// Set content type to json and charset
header('Content-type: application/json; charset=UTF-8');

require_once dirname(__DIR__) . '/vendor/autoload.php';

/** .ENV initialization */
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/** Require config file */
$config = require_once dirname(__DIR__) . '/config.php';

/** Database instance */
$dbi = new MySQLDatabase($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
/** Database connection */
$dbc = $dbi->getConnection();

/** Set error and exception handler */
set_error_handler('App\Src\Exceptions\ExceptionHandler::handle_error');
set_exception_handler('App\Src\Exceptions\ExceptionHandler::handle_exception');

/** Utilities used mainly in this file */
$api = new CoffeeMachineApi($config);


/** Get URL */
$url = $api->getFormatedUrl($_SERVER['REQUEST_URI']);
/** Get URL method */
$urlMethod = $_SERVER['REQUEST_METHOD'];

/** check whether url starts with /api/coffe-machine */
$api->doesUrlStartWell($url);

/** Get validated URL resource */
$restResource = $api->getUrlResource($url);
/** TODO prócz resource dostań jeszcze to co jest po resource */

$urlAfterResource = $api->getUrlAfterResource($url);

/** Instantiate TaskGateway class */
$coffeeMachineGateway = new CoffeeMachineGateway($dbc);

/** Coffee Machine Controller instance */
$coffeeMachineController = new CoffeeMachineController($coffeeMachineGateway);

/** Handle task request */
$coffeeMachineController->processRequest($urlMethod, $restResource, $urlAfterResource);

/**
 * - Moc kawy ustawia się osobno
 * - Powinna być weryfikacja czy pojemnik z kawą jest pusty, pojemnik na fusy jest pusty i czy jest woda.
 */

/**
 * Do controllera idzie resource
* i to co jest jeszcze za resourcem jako array
* interfacey pododawaj
* i abstract classes daj
*/

/**
 * zmiany w db
 * będzie tylko jedna kawa
 * dla content bedzie
 * kawa
 * mleko
 * woda
 * 
 * moc kawy tez będzie ustawiana przez url bo musi być zpaamiętywany
 * 
 * zrób funckej która sprawdza czy coffe jest pomiedzy 0-100
 * a coffee_power pomiędzy 1 a 2 i tutaj będzie float
 * i to będziesz ustawiał na route /api/coffee-machine/coffee/power
 * 
 * no i kawę będzie robić
 */