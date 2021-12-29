<?php
/**
 * Endpoints de paises
 */
$app->get('/listTypeDocument/{id_country}/country', function ($id_country) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessActionClient = $authController->validateAccessActionClient();
        if($validateAccessActionClient["status"] === "success"){
            $DocumentTypeController = new DocumentTypeController();
            return json_encode($DocumentTypeController->listTypeDocument($id_country));
        }else{
            return json_encode($validateAccessActionClient);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});