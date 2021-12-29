<?php
/**
 * Endpoints de ciudad
 */
$app->post('/validarUserDw', function () use ($app)  {
    //recibo parametros
    try {
        $BaseController = new BaseController();
        $validateAccessActionClient = $BaseController->readcvs();
        return json_encode($validateAccessActionClient);
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});