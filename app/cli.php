<?php
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

// Using the CLI factory default services container
$di = new CliDI();
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
date_default_timezone_set("America/Bogota");
/**
 * Register the autoloader and tell it to register the tasks directory
 */
require __DIR__ . '/../vendor/autoload.php';
/**
     * Read services
     */
include __DIR__ . '/config/services.php';
$loader = new Loader();
$loader->registerDirs(
    [
        __DIR__ . '/tasks/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/'
    ]
);

/* $loader->registerNamespaces(
    [
        'App\Models' =>  __DIR__ . '/models/'
    ]
); */

$loader->register();

// Load the configuration file (if any)
if (is_readable(__DIR__ . '/config/config.php')) {
    $config = include __DIR__ . '/config/config.php';
    $di->set('config', $config);
}





$di->setShared('params', function () {
    $config = $this->getConfig();
    return $config->params;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
//Base de datos Principal
$di->setShared('dbMaria', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});

//Base de datos a que se migrara
$di['databaseAmigrar'] = function (){
    $config = $this->get('config');
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->databaseAmigrar->adapter;
        $params = [
            'host'     => $config->databaseAmigrar->host,
            'username' => $config->databaseAmigrar->username,
            'password' => $config->databaseAmigrar->password,
            'dbname'   => $config->databaseAmigrar->dbname,
            'charset'  => $config->databaseAmigrar->charset,
            "port" => $config->databaseAmigrar->port
        ];

    if ($config->databaseAmigrar->adapter == 'Postgresql') {
        unset($params['charset']);
    }
    return new $class($params);
};


// Create a console application
$console = new ConsoleApp();

$console->setDI($di);


/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // handle incoming arguments
    $console->handle($arguments);
} catch (\Exception $e) {
    echo $e->getMessage();
    exit(255);
}