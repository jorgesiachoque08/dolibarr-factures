<?php
/**
 * Endpoints de paises
 */
$app->get('/listCountries', function () use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessActionClient = $authController->validateAccessActionClient();
        if($validateAccessActionClient["status"] === "success"){
            $CountriesController = new CountriesController();
            return json_encode($CountriesController->listCoutries());
        }else{
            return json_encode($validateAccessActionClient);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});