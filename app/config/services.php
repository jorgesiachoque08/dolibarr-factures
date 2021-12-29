<?php
declare(strict_types=1);

use Phalcon\Cache\Adapter\Redis;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Storage\Exception;
use Phalcon\Escaper;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Url as UrlResolver;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */

$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('dbMaria', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset,
        "persistent" => true,
        "options"    => [\PDO::ATTR_PERSISTENT => 1]
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('dbMy_bodytech', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->my_bodytech->adapter;
    $params = [
        'host'     => $config->my_bodytech->host,
        'username' => $config->my_bodytech->username,
        'password' => $config->my_bodytech->password,
        'dbname'   => $config->my_bodytech->dbname,
        'charset'  => $config->my_bodytech->charset,
        "persistent" => true,
        "options"    => [\PDO::ATTR_PERSISTENT => 1]
    ];

    if ($config->my_bodytech->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});
//Base de datos a que se migrara
$di['databaseAmigrar'] = function (){
    $config = $this->get('config');
    try {
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config->databaseAmigrar->host,
            "username" => $config->databaseAmigrar->username,
            "password" => $config->databaseAmigrar->password,
            "dbname" => $config->databaseAmigrar->dbname,
            "charset" => $config->databaseAmigrar->charset,
            "port" => $config->databaseAmigrar->port,
            "persistent" => true,
            "options"    => [\PDO::ATTR_PERSISTENT => 1]
        ));

        $connection->connect();
        return $connection;
    } catch (\PDOException $e) {
        throw $e;
    }
};


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});


/**
 * The actual HTTP response from the application to the user.
 */
$di->setShared(
    'response',
    function () {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8');
        return $response;
    }
);

/**
 * component that encapsulates the actual HTTP request
 */

$di->setShared(
    'request',
    function () {
        $request = new Phalcon\Http\Request;
        return $request;
    }
);


/**
 * componente que sirve para validar parametros del request
 */

$di->setShared('Validation',function (){
    $Validation = new ValidationComponent();
    return $Validation;
});

$di->setShared('params', function () {
    $config = $this->getConfig();
    return $config->params;
});


$di->set(
    "modelsManager",
    function() {
        return new ModelsManager();
    }
);

$di->set('cache', function(){
    try {
        $config = $this->getConfig();
        // Create the Cache setting redis connection options
        $serializerFactory = new SerializerFactory();
        $cache = new Redis($serializerFactory,
            [
                "host"=> $config->redis->host,
                "port"=> $config->redis->port,
                "persistent" => $config->redis->persistent,
                "lifetime"=> $config->redis->lifetime //5 dias cada key en la cache
            ]
        );
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    return $cache;

});