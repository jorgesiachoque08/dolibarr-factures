<?php

/**
 * Endpoints de autenticación
 */
$app->post('/oauth/token', function () use ($app)  {
    try {
        $authController = new OauthController();
        $authController->getTokenAction();
    } catch (\Exception $ex) {
        
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
        return $app->response;
    }
    
});

/**
 * Endpoints de autenticación
 */
$app->post('/oauth/tokenMyBodyTech', function () use ($app)  {
    try {
        $authController = new OauthController();
        $authController->getTokenMyBodytechAction();
    } catch (\Exception $ex) {
        
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
        return $app->response;
    }
    
});


$app->post('/validateToken', function () {
    $authController = new OauthController();
    return json_encode($authController->validateAccessAction());
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
            //return json_encode($_POST);
            $authController = new OauthController();
            //$validateAccessActionClient = $authController->validateAccessActionClient();
            //if($validateAccessActionClient["status"] === "success"){
                //$_POST->client_id = $validateAccessActionClient["data"];
                $requestHeaders = $app->request->getHeaders();
                $_POST->client_id = $requestHeaders["X-Bodytech-Client-Id"]?$requestHeaders["X-Bodytech-Client-Id"]:$requestHeaders["x-bodytech-client-id"];
                $UsersController = new UsersController();
                return json_encode($UsersController->adduserAction($_POST));
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

$app->post('/userDw', function () use ($app)  {
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
        $app->Validation->setRequired(["address","id_country","city_id","terms_data","phone","name","email","birthdate","document_number","document_type_id","lastname","genre","company_id"]);
        $app->Validation->setEmails(["email"]);
        $app->Validation->setIntegers(["document_type_id","id_country","city_id","company_id"]);
        $app->Validation->setDates(["birthdate"]);
        $app->Validation->setIdentical(["terms_data"]);
        $app->Validation->setEnum(["genre"]);
        
    
        if($app->Validation->validate($_POST)){
            //return json_encode($_POST);
            $authController = new OauthController();
            //$validateAccessActionClient = $authController->validateAccessActionClient();
            //if($validateAccessActionClient["status"] === "success"){
                //$_POST->client_id = $validateAccessActionClient["data"];
                $requestHeaders = $app->request->getHeaders();
                $_POST->client_id = $requestHeaders["X-Bodytech-Client-Id"]?$requestHeaders["X-Bodytech-Client-Id"]:$requestHeaders["x-bodytech-client-id"];
                $UsersController = new UsersController();
                return json_encode($UsersController->adduserDWAction($_POST));
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


$app->get('/dw-update/{id_user}', function ($id_user) {
    $user = new UsersController();
    $user->ValidateUserDw($id_user);
});

$app->get('/welcomeEmail/{id_user}', function ($id_user) {
    $user = new UsersController();
    $user->welcomeEmail($id_user);
});

$app->post('/background', function () use ($app) {
    $user = new UsersController();
    $_POST = $app->request->getJsonRawBody();
    $user->background($_POST);
    //return json_encode($user->background($_POST));
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
                    return json_encode($authController->recoverypasswordAction($email,$client_id));
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
                        return json_encode($authController->recoverypasswordAction($email,$validateAccessActionClient["data"]));
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
                    return json_encode($authController->recoverypasswordAction($email,$validateAccessActionClient["data"]));
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
            $authController = new OauthController();
            return json_encode($authController->passwordresetAction($_POST));
        }
    } catch (\Exception $ex) {
        $app->response->setStatusCode(400);
        $app->response->setJsonContent(['status'=>'error', 'message'=>"requiere parametro"]);
        return $app->response;
}
});

$app->post('/oauth/token-my-boydtech', function () use ($app)  {
    try {
        if($app->request->getPost()){
            $_POST = $app->request->getPost();
        }

        $app->Validation->setRequired(["grant_type","client_id","client_secret","username","password"]);
        if($app->Validation->validate($_POST)){
            $authController = new OauthController();
            $authController->loginMyBodytech($_POST);
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
        $validateAccessAction = $authController->validateAccessAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersController();
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

$app->get('/directoryActive', function () use ($app){
    $authController = new OauthController();
    return json_encode($authController->loginActiveDirectory("jorge.siachoque","Bodytech2021*"));
        
});

$app->put('/oauth/blockUser/{id}', function ($id) use ($app)  {
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersController();
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

$app->put('/userDw/update/{id}', function ($id) use ($app)  {
    //recibo parametros
    try {
        if($app->request->getPut()){
            $_POST = (object)$app->request->getPut();
        }else{
            $_POST = $app->request->getJsonRawBody();
        }
        
        if(isset($_POST->terms_data) && $_POST->terms_data != true){$_POST->terms_data = "error"; }
       
        if(isset($_POST->birthdate)){
            $_POST->birthdate = substr($_POST->birthdate,0,10);
        }
        //validacion request
        $app->Validation->setRequired(["address","id_country","city_id","phone","name","email","birthdate","document_number","document_type_id","lastname","genre"]);
        $app->Validation->setEmails(["email"]);
        $app->Validation->setIntegers(["document_type_id","id_country","city_id"]);
        $app->Validation->setDates(["birthdate"]);
        $app->Validation->setEnum(["genre"]);
        
        
        if($app->Validation->validate($_POST)){
            //return json_encode($_POST);
            $authController = new OauthController();
            $validateAccessAction = $authController->validateAccessAction(true);
            if($validateAccessAction["status"] === "success"){
                //$requestHeaders = $app->request->getHeaders();
                $UsersController = new UsersController();
                return json_encode($UsersController->updateUserDWAction($_POST,$id,$validateAccessAction["data"]));
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

$app->get('/user/profile/id_tinnova/{id_tinnova}/{uuid_company}', function ($id_tinnova,$uuid_company) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersController();
            return json_encode($user->myProfileIdTinnova($id_tinnova,$uuid_company));
        }else{
            return json_encode($validateAccessAction);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});

$app->get('/getUserDw/{document_number}/{document_type}', function ($document_number,$document_type) use ($app)  {
    //recibo parametros
    try {
        $authController = new OauthController();
        $validateAccessAction = $authController->validateAccessAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersController();
            return json_encode($user->getUserDw($document_number,$document_type,$validateAccessAction["data"]->client_id,$validateAccessAction["data"]->user_id));
        }else{
            return json_encode(['status'=>'error','message'=>$validateAccessAction]);
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
        $validateAccessAction = $authController->validateAccessAction(true);
        if($validateAccessAction["status"] === "success"){
            
            $user = new UsersController();
            return json_encode($user->getUserDw($document_number,$document_type,$validateAccessAction["data"]->client_id,$validateAccessAction["data"]->user_id));
        }else{
            return json_encode(['status'=>'error','message'=>$validateAccessAction]);
        }
    } catch (\Exception $ex) {
            $app->response->setStatusCode(400);
            $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
            return $app->response;
    }

});

// $app->get('/user/adjustmentFirstNamen/{limit}', function ($limit) use ($app)  {
//     //recibo parametros
//     try {
       
//             $user = new UsersController();
//             return json_encode($user->adjustmentFirstNamen($limit));
        
//     } catch (\Exception $ex) {
//             $app->response->setStatusCode(400);
//             $app->response->setJsonContent(['status'=>'error', 'message'=>$ex->getMessage()]);
//             return $app->response;
//     }

// });