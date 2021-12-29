<?php

// incluir rutas
//include APP_PATH.'/routes/oauth.php';
include APP_PATH.'/routes/cities.php';
include APP_PATH.'/routes/countries.php';
include APP_PATH.'/routes/documentType.php';
include APP_PATH.'/routes/testerDw.php';
include APP_PATH.'/routes/testerOauth.php';

/**
 * Sets a handler that will be called when the router does not match any of the defined routes
 */
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404);
    $app->response->setJsonContent(['status'=>'error', 'message'=>"route does not exist"]);
    return $app->response;
});