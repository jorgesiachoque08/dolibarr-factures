<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Expose-Headers: *');

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    // cargar dependencias
    require __DIR__ . '/../vendor/autoload.php';
    // cargar variables de entorno
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->load();


    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

     /**
     * Handle routes
     */

    include APP_PATH . '/index.php';

    /**
     * Handle the request
     */
    $app->handle($_SERVER["REQUEST_URI"]);

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    //echo $application->handle($_SERVER['REQUEST_URI'])->getContent();
} catch (\Exception $e) {
    return json_encode(
        [
            'status'    => '500',
            'message'   => $e->getMessage(),
            'data'      => false
        ]
    );
}
