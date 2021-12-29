<?php

/**
 * Endpoints de autenticación
 */
$app->post('/oauth/token', function () use ($app)  {
    try {
        $authController = new OauthController();
        $authController->getTokenActionNew();
    } catch (\Exception $ex) {
        
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
        return $app->response;
    }
    
});

$app->post('/user', function () use ($app)  {
    //recibo parametros
    try {
        if($app->request->getPost()){
            $_POST = (object)$app->request->getPost();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        
        if(isset($_POST->terms_data) && $_POST->terms_data != true){$_POST->terms_data = "error"; }
       
        if(isset($_POST->birthdate)){
            $_POST->birthdate = substr($_POST->birthdate,0,10);
        }
        //validacion request
        //validacion request
        if(isset($_POST->document_type_id) && ($_POST->document_type_id == 1 || $_POST->document_type_id == 2) ){
            if($_POST->platform != 'Movil'){
                $app->Validation->setRequired(["dv","terms_data","phone","name","email","birthdate","document_number","document_type_id","lastname","platform"]);
                $app->Validation->setIntegers(["document_type_id","id_country","dv"]);
            }else{
                $app->Validation->setRequired(["terms_data","phone","password","name","email","birthdate","document_number","document_type_id","lastname","platform"]);
                $app->Validation->setIntegers(["document_type_id","id_country"]);
            }
        }else {
            $app->Validation->setRequired(["terms_data","phone","name","email","birthdate","document_number","document_type_id","lastname","platform"]);
            $app->Validation->setIntegers(["document_type_id","id_country"]);
        }

        //validacion request
        $app->Validation->setEmails(["email"]);
        $app->Validation->setDates(["birthdate"]);
        $app->Validation->setIdentical(["terms_data"]);
        $app->Validation->setEnum(["platform"]);
        
    
        if($app->Validation->validate($_POST)){
                $requestHeaders = $app->request->getHeaders();
                $_POST->client_id = $requestHeaders["X-Bodytech-Client-Id"]?$requestHeaders["X-Bodytech-Client-Id"]:$requestHeaders["x-bodytech-client-id"];
                $UsersClientController = new UsersClientController();
                return json_encode($UsersClientController->adduserActionNew($_POST));
            
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});

$app->post('/validateToken', function () {
    $authController = new OauthController();
    return json_encode($authController->validateAccessNewAction());
});

/**
 * Endpoints de autenticación
 */
$app->post('/oauth/tokenMyBodyTech', function () use ($app)  {
    try {
        $authController = new OauthController();
        $authController->getTokenMyBodytechActionNew();
    } catch (\Exception $ex) {
        
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
        return $app->response;
    }
    
});

$app->post('/userDw', function () use ($app)  {
    //recibo parametros
    try {
        if($app->request->getPost()){
            $_POST = (object)$app->request->getPost();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        
        //validacion request
        $app->Validation->setRequired(["email","password"]);
        $app->Validation->setEmails(["email"]);
        if($app->Validation->validate($_POST)){
            //return json_encode($_POST);
            //$authController = new OauthController();
            //$validateAccessActionClient = $authController->validateAccessActionClient();
            //if($validateAccessActionClient["status"] === "success"){
                //$_POST->client_id = $validateAccessActionClient["data"];
                $requestHeaders = $app->request->getHeaders();
                $_POST->client_id = $requestHeaders["X-Bodytech-Client-Id"]?$requestHeaders["X-Bodytech-Client-Id"]:$requestHeaders["x-bodytech-client-id"];
                $UsersCollaboratorsController = new UsersCollaboratorsController();
                return json_encode($UsersCollaboratorsController->adduserMBAction($_POST));
            /* }else{
                return json_encode($validateAccessActionClient);
            } */
            
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});

$app->get('/user/profile/{id}', function ($id) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessNewAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersClientController();
            return json_encode($user->myProfile($id));
        }else{
            return json_encode($validateAccessAction);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});

$app->get('/user/profile/id_tinnova/{id_tinnova}/{brand_id}', function ($id_tinnova,$brand_id) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessNewAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersClientController();
            return json_encode($user->myProfileIdTinnova($id_tinnova,$brand_id));
        }else{
            return json_encode($validateAccessAction);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});

$app->get('/user/recovery-password/{email}', function ($email) use ($app)  {
    //recibo parametros
    try {
        $requestHeaders = apache_request_headers();
        $code = 200;
        if (isset($requestHeaders['x-bodytech-client-id']) || isset($requestHeaders['X-Bodytech-Client-Id'])) {
            $authController = new OauthController();
            if (isset($requestHeaders['x-bodytech-client-id'])) {
                $client_id = trim($requestHeaders['x-bodytech-client-id']);
            } else {
                $client_id = trim($requestHeaders['X-Bodytech-Client-Id']);
            }

            if($client_id == 'grupodtg_bodytech_movil'){
                //validacion request
                $app->Validation->setRequired(["email"]);
                $app->Validation->setEmails(["email"]);
                $campos = ["email"=>$email]; 
            
                if($app->Validation->validate($campos)){
                    $UsersClientController = new UsersClientController();
                    return json_encode($UsersClientController->recoverypasswordAction($email,$client_id));
                }
            }else{
                $authController = new OauthController();
                $validateAccessActionClient = $authController->validateAccessActionClient();
                if($validateAccessActionClient["status"] === "success"){
                    //validacion request
                    $app->Validation->setRequired(["email"]);
                    $app->Validation->setEmails(["email"]);
                    $campos = ["email"=>$email]; 
                
                    if($app->Validation->validate($campos)){
                        $UsersClientController = new UsersClientController();
                        return json_encode($UsersClientController->recoverypasswordAction($email,$validateAccessActionClient["data"]));
                    }
                }else{
                    return json_encode($validateAccessActionClient);
                }
            }
        }else{
            $authController = new OauthController();
            $validateAccessActionClient = $authController->validateAccessActionClient();
            if($validateAccessActionClient["status"] === "success"){
                //validacion request
                $app->Validation->setRequired(["email"]);
                $app->Validation->setEmails(["email"]);
                $campos = ["email"=>$email]; 
            
                if($app->Validation->validate($campos)){
                    $UsersClientController = new UsersClientController();
                    return json_encode($UsersClientController->recoverypasswordAction($email,$validateAccessActionClient["data"]));
                }
            }else{
                return json_encode($validateAccessActionClient);
            }
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});

$app->post('/user/password-reset', function () use ($app){
    try {
        if($app->request->getPost()){
            $_POST = (object)$app->request->getPost();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        $app->Validation->setRequired(["token","password"]);
        if($app->Validation->validate($_POST)){
            $UsersClientController = new UsersClientController();
            return json_encode($UsersClientController->passwordresetAction($_POST));
        }
    } catch (\Exception $ex) {
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>"requiere parametro"]);
        return $app->response;
    }
});

$app->put('/userMB/update/{id}', function ($id) use ($app)  {
    //recibo parametros
    try {
        if($app->request->getPut()){
            $_POST = (object)$app->request->getPut();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        
        //validacion request
        $app->Validation->setRequired(["email"]);
        $app->Validation->setEmails(["email"]);
        
        
        if($app->Validation->validate($_POST)){
            //return json_encode($_POST);
            $authController = new OauthController();
            $validateAccessAction = $authController->validateAccessNewAction(true);
            if($validateAccessAction["status"] === "success"){
                //$requestHeaders = $app->request->getHeaders();
                $UsersCollaboratorsController = new UsersCollaboratorsController();
                return json_encode($UsersCollaboratorsController->updateUserMBAction($_POST,$id,$validateAccessAction["data"]));
            }else{
                return json_encode($validateAccessAction);
            }
            
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }
    

});

$app->put('/oauth/blockUser/{id}', function ($id) use ($app)  {
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessNewAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersCollaboratorsController();
            return json_encode($user->blockUser($id,$validateAccessAction["data"]));
        }else{
            return json_encode($validateAccessAction);
        }

    } catch (\Exception $ex) {
        
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
        return $app->response;
    }
    
});

$app->get('/getUserDw/{document_number}/{document_type}/{brand_id}', function ($document_number,$document_type,$brand_id) use ($app)  {
    //recibo parametros
    try {
        
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessNewAction(true);
        if($validateAccessAction["status"] === "success"){
            
            if($validateAccessAction["data"]->client_id == "biz_app_movil"){
                $brand_id = isset($validateAccessAction["data"]->UsersCollaborators->UsersCollaboratorsBrands[0]->brand_id)?$validateAccessAction["data"]->UsersCollaborators->UsersCollaboratorsBrands[0]->brand_id:1;
            }
            $user = new UsersClientController();
            return json_encode($user->getUserDw($document_number,$document_type,$brand_id));
        }else{
            return json_encode(['status'=>'error','message'=>$validateAccessAction]);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});

$app->get('/getBrandUser/{user_id}', function ($user_id) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessNewAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $UsersCollaboratorsController = new UsersCollaboratorsController();
            return json_encode($UsersCollaboratorsController->getBrandUser($user_id));
        }else{
            return json_encode(['status'=>'error','message'=>$validateAccessAction]);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});


$app->get('/verificationCodeDniAndRut', function () use ($app)  {
    //recibo parametros
    try {
        if($app->request->getPost()){
            $_POST = (object)$app->request->getPost();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        $UsersClientController = new UsersClientController();
        return json_encode($UsersClientController->verificationCodeDniAndRut($_POST->document,$_POST->document_type));
        
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});