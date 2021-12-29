<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'Bogota2015*',
        'dbname'      => 'dbOAuth',
        'charset'     => 'utf8',
    ],
    'databaseAmigrar' => [
        /* 'adapter'     => 'Mysql',
        'host'        => 'dev-bodytech.cib6lsiueexk.us-west-2.rds.amazonaws.com',
        'username'    => 'bodytech',
        'password'    => 'Bdt3chS3rv3rIT2020*',
        'dbname'      => 'dbOAuth2',
        'charset'     => 'utf8', */
        'adapter' => 'Postgresql',
        'host' => 'bodytech-production.cib6lsiueexk.us-west-2.rds.amazonaws.com',
        //'host' => 'bodytech.cib6lsiueexk.us-west-2.rds.amazonaws.com',
        'port'     => '5432',
        'username' => 'postgres',
        'password' => 'B0dyt3ch_2021*',
        'dbname' => 'bodytechperu-production',
        'charset'     => 'utf8'
    ],

    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/',
    ],
    'params' => [
        "ElasticSearch" => array(
            "UrlBase" => "44.227.232.233/",
            "status" => false,
            "indexOauth" => "users_all"
        ),
        "urlLocal" => 'http://localhost:4000/',
        'sendmail' => array(
            'driver' => 'smtp',
                'host' => 'smtp.mandrillapp.com',
            'port' => '587',
            'encryption' => 'tls',
            'username' => 'Bodytech Corp',
            'password' => 'iRBfDQujZJoq5aSEo8b8Gg',
            'from' => [
                'email' => 'info@bodytechcorp.com',
                'name' => 'Bodytech Colombia'
            ]
        ),
        "DeporWin" => array(
            "1"=>array(
                "UrlBase" => "http://190.60.223.210:",
                "PortMin" => 9085,
                "PortMax" => 9085,
                "country" =>"colombia",
                "status" => true,
                "credentials"=>'{"Nombre": "Bodytech", "Contraseña": "Bodytech"}'
            ),
            "2"=>array(
                "UrlBase" => "http://200.123.14.98:",
                "PortMin" => 9086,
                "PortMax" => 9086,
                "country" =>"peru",
                "status" => true,
                "credentials"=>'{"Nombre": "Bodytech", "Contraseña": "Bodytech"}'
            ),
            "3"=>array(
                "UrlBase" => "http://190.151.107.125:",
                "PortMin" => 9085,
                "PortMax" => 9085,
                "country" =>"chile",
                "status" => true,
                "credentials"=>'{"Nombre": "Bodytech", "Contraseña": "Bodytech"}'
            )
        )
    ],
    'redis'=>[
        "host"=> "127.0.0.1",
        "port"=> 6379,
        "persistent" => true,
        "lifetime"=> 1200 //20 minutos
    
    ]
]);
//bodytechprod.ttmiuh.ng.0001.usw2.cache.amazonaws.com:6379
