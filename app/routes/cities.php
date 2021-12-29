<?php
/**
 * Endpoints de ciudad
 */
$app->get('/listByCountry/{id_country}', function ($id_country) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessActionClient = $authController->validateAccessActionClient();
        if($validateAccessActionClient["status"] === "success"){
            $citiesController = new CitiesController();
            return json_encode($citiesController->listByCountry($id_country));
        }else{
            return json_encode($validateAccessActionClient);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});