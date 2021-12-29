<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Http\Client\Request as RequestCli;
use App\Libraries\Custompdo;
use App\Libraries\Custompdoprime;
use App\Libraries\CustompdoMyBodytech;
use App\Libraries\CustompdoNew;
use App\Libraries\CustompdoMyBodytechNew;

class OauthController extends BaseController {

    /**
     * Función encargada de instanciar los tipos de autenticación de OAuth y 
     * generar el servidor para cada una de las peticiones
     *
     * @return OAuth2\Server
     */
    public function getServer() {
        $conection = $this->dbMaria->getDescriptor();
        $dsn = 'mysql:dbname=' . $conection['dbname'] . ';host=' . $conection['host'];
        $username = $conection['username'];
        $password = $conection['password'];
        OAuth2\Autoloader::register();

        $storage = new Custompdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
            'id_lifetime' => 10,
            'access_lifetime' => 21600,
            'refresh_token_lifetime' => 21600 * 1000*20,
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        // Add the "User Credentials" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
        //Add the "Refresh Token" grant type
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
        //print_r($server);die;
        return $server;
    }

    /**
     * Función encargada de instanciar los tipos de autenticación de OAuth y 
     * generar el servidor para cada una de las peticiones
     *
     * @return OAuth2\Server
     */
    public function getServerNew() {
        $conection = $this->dbMaria->getDescriptor();
        $dsn = 'mysql:dbname=' . $conection['dbname'] . ';host=' . $conection['host'];
        $username = $conection['username'];
        $password = $conection['password'];
        OAuth2\Autoloader::register();

        $storage = new CustompdoNew(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
            'id_lifetime' => 10,
            'access_lifetime' => 21600,
            'refresh_token_lifetime' => 21600 * 1000*20,
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        // Add the "User Credentials" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
        //Add the "Refresh Token" grant type
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
        //print_r($server);die;
        return $server;
    }
    /**
     * Función encargada de instanciar los tipos de autenticación de OAuth y 
     * generar el servidor para cada una de las peticiones
     *
     * @return OAuth2\Server
     */
    public function getServerPrime() {
        $conection = $this->dbMaria->getDescriptor();
        $dsn = 'mysql:dbname=' . $conection['dbname'] . ';host=' . $conection['host'];
        $username = $conection['username'];
        $password = $conection['password'];
        OAuth2\Autoloader::register();

        $storage = new Custompdoprime(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
            'id_lifetime' => 10,
            'access_lifetime' => 21600,
            'refresh_token_lifetime' => 21600 * 1000 * 20,
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        // Add the "User Credentials" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
        //Add the "Refresh Token" grant type
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
        //print_r($server);die;
        return $server;
    }

    /**
     * Función encargada de generar el token de acceso para futuras peticiones de usuario
     * con cualquiera de los 4 tipos de autenticación
     *
     * @return void
     */


    /**
     * Función encargada de instanciar los tipos de autenticación de OAuth en mybodytech y 
     * generar el servidor para cada una de las peticiones
     *
     * @return OAuth2\Server
     */
    public function getServerMyBodytech() {
        $conection = $this->dbMaria->getDescriptor();
        $dsn = 'mysql:dbname=' . $conection['dbname'] . ';host=' . $conection['host'];
        $username = $conection['username'];
        $password = $conection['password'];
        OAuth2\Autoloader::register();

        $storage = new CustompdoMyBodytech(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
            'id_lifetime' => 10,
            'access_lifetime' => 21600,
            'refresh_token_lifetime' => 21600 * 1000*20,
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        // Add the "User Credentials" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
        //Add the "Refresh Token" grant type
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
        //print_r($server);die;
        return $server;
    }

    /**
     * Función encargada de instanciar los tipos de autenticación de OAuth en mybodytech y 
     * generar el servidor para cada una de las peticiones
     *
     * @return OAuth2\Server
     */
    public function getServerMyBodytechNew() {
        $conection = $this->dbMaria->getDescriptor();
        $dsn = 'mysql:dbname=' . $conection['dbname'] . ';host=' . $conection['host'];
        $username = $conection['username'];
        $password = $conection['password'];
        OAuth2\Autoloader::register();

        $storage = new CustompdoMyBodytechNew(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
            'id_lifetime' => 10,
            'access_lifetime' => 21600,
            'refresh_token_lifetime' => 21600 * 1000*20,
        ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        // Add the "User Credentials" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
        //Add the "Refresh Token" grant type
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
        //print_r($server);die;
        return $server;
    }
    
    /**
     * Función encargada de generar el token de acceso para futuras peticiones de usuario
     * con cualquiera de los 4 tipos de autenticación
     *
     * @return void
     */

    public function getTokenMyBodytechActionNew() {
        //$this->getResponseBody();getStatusCode/getParameters/addParameters
       
        $request = OAuth2\Request::createFromGlobals();
        $_POST["client_id"] = $request->request("client_id");
        $_POST["username"] = $request->request("username");
        $_POST["password"] = $request->request("password");
        $user_name = explode("@",$_POST["username"]);
        //var_dump($user_name[0],$_POST["password"]);die;
        //$loginDirectoryActive = $this->loginActiveDirectory($user_name[0],$_POST["password"]);
        // validamos que el usuario este activo y registrado en el directorio activo
        if(true){
           $server = $this->getServerMyBodytechNew();
           $server = $server->handleTokenRequest($request);
           if($server->getStatusCode() == 200 && $request->request("grant_type") == "password"){
               $parameters = $server->getParameters();
               $OauthAccessTokens = OauthAccessTokens::findFirst(
                ['conditions' => 'access_token=:access_token:',
                'bind'=>['access_token'=>$parameters['access_token']]]);
            
                $OauthAccessTokens->UsersCollaborators->last_login = date('Y-m-d H:i:s');
                $OauthAccessTokens->save();
                $UsersCollaborators = $OauthAccessTokens->UsersCollaborators;
                // data a guardar en cache
                $data = array(
                                "data"=>["user_id"=> empty($OauthAccessTokens->user_id)?null:$OauthAccessTokens->user_id,
                                "client_id"=>$OauthAccessTokens->client_id,
                                "uuid_company"=>null,
                                "company_id"=>null,
                                "uuid_brand"=>null,
                                "brand_id"=>null,
                                "brands"=>[],
                                "organization_id"=>empty($UsersCollaborators->organization_id)?null:$UsersCollaborators->organization_id,
                                "uuid_organization"=>empty($UsersCollaborators->uuid_organization)?null:$UsersCollaborators->uuid_organization,
                                "type_user" =>'collaborator',
                                "company_id_country"=> null,
                                "company_country"=> null,
                                "company"=> null]);
                                foreach ($UsersCollaborators->UsersCollaboratorsBrands as $key => $value) {
                                    $data['data']['brands'][] = array('uuid_brand'=>$value->uuid_brand,
                                                                        'brand_id'=>$value->brand_id,
                                                                        'company_id'=>$value->company_id,
                                                                        'uuid_company'=>$value->uuid_company,
                                                                        'organization_id'=>$value->organization_id,
                                                                        'uuid_organization'=>$value->uuid_organization);
                                }
                // guardo en cache
                $this->setRedis($parameters['access_token'],$data ,18000);

                // envio organizacion y comania por header
                if($request->request("client_id") == "biz_app_movil"){
                    $server->addHttpHeaders(array(
                        'x-bodytech-company' => isset($data['data']['brands'][0]['uuid_company'])?$data['data']['brands'][0]['uuid_company']:"006629fa-6aae-4c02-b28f-5264387199bb",
                        'x-bodytech-organization' => $data['data']['uuid_organization'],
                        'x-bodytech-brand' => json_encode($data['data']['brands'])
                    ));
                }else{
                    $server->addHttpHeaders(array(
                        'x-bodytech-company' => null,
                        'x-bodytech-organization' => $data['data']['uuid_organization'],
                        'x-bodytech-brand' => json_encode($data['data']['brands'])
                    ));
                }
   
           }else{
               $parameters = $server->getParameters();
               if(isset($parameters["error_description"]) && $parameters["error_description"] == "Invalid username and password combination"){
                   $server->addParameters(array("error_description" =>"Combinación de nombre de usuario y contraseña no válida"));
               }
           };
           $server->send();
        }else{
           $this->response->setJsonContent(["error"=>"invalid_grant","error_description"=>"Combinación de nombre de usuario y contraseña no válida"]);
           $this->response->setStatusCode(401);
           $this->response->send();
        }
        exit();
   }


    public function getTokenAction() {
        //$this->getResponseBody();getStatusCode/getParameters/addParameters
        $server = $this->getServer();
        $request = OAuth2\Request::createFromGlobals();
        $_POST["client_id"] = $request->request("client_id");
        $_POST["username"] = $request->request("username");
        $server = $server->handleTokenRequest($request);
        if($server->getStatusCode() == 200 && $request->request("grant_type") == "password"){
            // se validad si el cliente esta guardado en cache
            $client = $this->getRedis($_POST["client_id"]);
            
            if(empty($client)){
                $client = OauthClients::findFirstByclient_id($_POST["client_id"]);
                $this->setRedis($_POST["client_id"],$client ,18000);
            }

            $parameters = $server->getParameters();
            if($client){
               
                $user = Users::findFirst(
                    ['conditions' => 'id_organization=:id_organization: and email=:email:',
                    'bind'=>['id_organization'=>$client->id_organization,
                            'email'=>$_POST["username"]]]);
                $user->last_login = date('Y-m-d H:i:s');

                $user->save();

                // data a guardar en cache

                if($request->request("password") == "BENEFICIARIO"){
                    $server->addParameters(array("data" =>["id" => $user->id,'first_name'=>$user->first_name]));
                }
                
                $data = array("data"=>["user_id"=> empty($user->id)?null:$user->id,
                                "client_id"=>$_POST["client_id"],
                                "company_id_country"=> empty($user->Companies->id_country)?null:$user->Companies->id_country,
                                "uuid_company"=>empty($user->Companies->uuid)?null:$user->Companies->uuid,
                                "company_country"=> empty($user->Companies->Countries->name)?null:$user->Companies->Countries->name,
                                "company"=> empty($user->Companies->name)?null:$user->Companies->name]);
                // guardo en cache
                $this->setRedis($_POST["client_id"]."-".$parameters['access_token'],$data ,18000);

                // envio organizacion y comania por header
                $server->addHttpHeaders(array(
                    'x-bodytech-company' => $user->uuid_company,
                    'x-bodytech-organization' => $client->uuid_organization
                )); 
            }

        }else{
            $parameters = $server->getParameters();
            if(isset($parameters["error_description"]) && $parameters["error_description"] == "Invalid username and password combination" ){
                $server->addParameters(array("error_description" =>"Combinación de nombre de usuario y contraseña no válida"));
            }
        };
        $server->send();
        exit();
    }


    public function getTokenActionNew() {
        //$this->getResponseBody();getStatusCode/getParameters/addParameters
        $server = $this->getServerNew();
        $request = OAuth2\Request::createFromGlobals();
        $_POST["client_id"] = $request->request("client_id");
        $_POST["username"] = $request->request("username");
        $server = $server->handleTokenRequest($request);
        if($server->getStatusCode() == 200 && $request->request("grant_type") == "password"){
            $parameters = $server->getParameters();
                $OauthAccessTokens = OauthAccessTokens::findFirst(
                    ['conditions' => 'access_token=:access_token:',
                    'bind'=>['access_token'=>$parameters['access_token']]]);
                
                $OauthAccessTokens->UsersClient->last_login = date('Y-m-d H:i:s');
                $OauthAccessTokens->save();
                $UsersClient = $OauthAccessTokens->UsersClient;
                // data a guardar en cache
                if($request->request("password") == "BENEFICIARIO"){
                    $server->addParameters(array("data" =>["id" => $UsersClient->id,'first_name'=>$UsersClient->UsersProfile[0]->first_name]));
                }

                $data = array("data"=>["user_id"=> empty($UsersClient->id)?null:$UsersClient->id,
                                "client_id"=>$_POST["client_id"],
                                "uuid_company"=>empty($UsersClient->uuid_company)?null:$UsersClient->uuid_company,
                                "company_id"=>empty($UsersClient->company_id)?null:$UsersClient->company_id,
                                "uuid_brand"=>empty($UsersClient->uuid_brand)?null:$UsersClient->uuid_brand,
                                "brand_id"=>empty($UsersClient->brand_id)?null:$UsersClient->brand_id,
                                "organization_id"=>empty($UsersClient->organization_id)?null:$UsersClient->organization_id,
                                "uuid_organization"=>empty($UsersClient->uuid_organization)?null:$UsersClient->uuid_organization,
                                "type_user" =>'client',
                                "company_id_country"=> null,
                                "company_country"=> null,
                                "company"=> null]);
                // guardo en cache
                $this->setRedis($parameters['access_token'],$data ,18000);

                // envio organizacion y comania por header
                $server->addHttpHeaders(array(
                    'x-bodytech-company' => $UsersClient->uuid_company,
                    'x-bodytech-organization' => $UsersClient->uuid_organization,
                    'x-bodytech-brand' => $UsersClient->brand_id
                ));

        }else{
            $parameters = $server->getParameters();
            if(isset($parameters["error_description"]) && $parameters["error_description"] == "Invalid username and password combination" ){
                $server->addParameters(array("error_description" =>"Combinación de nombre de usuario y contraseña no válida"));
            }
        };
        $server->send();
        exit();
    }


    /**
     * Función encargada de generar el token de acceso para futuras peticiones de usuario
     * con cualquiera de los 4 tipos de autenticación
     *
     * @return void
     */

    public function getTokenMyBodytechAction() {
         //$this->getResponseBody();getStatusCode/getParameters/addParameters
        
         $request = OAuth2\Request::createFromGlobals();
         $_POST["client_id"] = $request->request("client_id");
         $_POST["username"] = $request->request("username");
         $_POST["password"] = $request->request("password");
         $user_name = explode("@",$_POST["username"]);
         //var_dump($user_name[0],$_POST["password"]);die;
         //$loginDirectoryActive = $this->loginActiveDirectory($user_name[0],$_POST["password"]);
         // validamos que el usuario este activo y registrado en el directorio activo
         if(true){
            $server = $this->getServerMyBodytech();
            $server = $server->handleTokenRequest($request);
            if($server->getStatusCode() == 200 && $request->request("grant_type") == "password"){
                $parameters = $server->getParameters();
                
                    $user = UsersInternal::findFirst(
                        ['conditions' => 'email=:email:',
                        'bind'=>['email'=>$_POST["username"]]]);
                    if (isset($user->uuid_company)) {
                        $data = array("data"=>["user_id"=> $user->id,
                                        "client_id"=>$_POST["client_id"],
                                        "company_id_country"=>$user->Companies->id_country,
                                        "uuid_company"=>$user->Companies->uuid,
                                        "company_country"=>$user->Companies->Countries->name,
                                        "company"=>$user->Companies->name]);
                        $this->setRedis($_POST["client_id"]."-".$parameters['access_token'],$data ,18000);
                        $server->addHttpHeaders(array(
                            'x-bodytech-company' => $user->uuid_company,
                            'x-bodytech-organization' => $user->uuid_organization
                        )); 
                    }
                
    
            }else{
                $parameters = $server->getParameters();
                if(isset($parameters["error_description"]) && $parameters["error_description"] == "Invalid username and password combination"){
                    $server->addParameters(array("error_description" =>"Combinación de nombre de usuario y contraseña no válida"));
                }
            };
            $server->send();
         }else{
            $this->response->setJsonContent(["error"=>"invalid_grant","error_description"=>"Combinación de nombre de usuario y contraseña no válida"]);
            $this->response->setStatusCode(401);
            $this->response->send();
         }
         exit();
    }

    /**
     * Función encargada de generar el token de acceso para futuras peticiones de usuario en my bodytech
     *
     * @return void
     */
    public function loginMyBodytech($params)
    {
        //$this->getTokenMyBodytechAction();
        if ($params["grant_type"] == "password") {
            $ad = $this->loginActiveDirectory($params["username"],$params["password"]);
            if($ad["status"] == "success"){
                $data = explode(",",$ad["data"][0]["dn"]);
                $client = OauthClients::findFirstByclient_id($params["client_id"]);
                if(isset($client)){
                    $user = Users::findFirst(
                        ["columns"=>'id_country,email,id_company,uuid_company',
                        'conditions' => 'id_organization=:id_organization: and email=:username:',
                        'bind'=>['id_organization'=>$client->id_organization,
                                'username'=>$params["username"]]]);
                    if(isset($user)){
                        $this->getTokenMyBodytechAction($params);
                    }else{
                        $full_name = explode("=",$data[0]);
                        $full_name_array =  explode(" ",$full_name[1]);
                        if(count($full_name_array) > 3){
                            $name = $full_name_array[0]." " .$full_name_array[1];
                            $last_name = $full_name_array[2]." " .$full_name_array[3];
                        }else{
                            $name = $full_name_array[0];
                            if(isset($full_name_array[1])){
                                $last_name = isset($full_name_array[2])? $full_name_array[1]." " .$full_name_array[2]:$full_name_array[1];
                            }else{
                                $last_name = " ";
                            }
                            
                        }
                        $newUser = array("name"=>$name,
                                        "lastname"=>$last_name,
                                        "email"=>$params["username"],
                                        "password"=>$params["password"],
                                        "document_number"=>1047487884,
                                        "document_type_id"=>10,
                                        "birthdate"=>null,
                                        "id_country" =>1,
                                        "phone"=>null,
                                        "id_company"=>$client->id_company,
                                        "id_organization"=>$client->id_organization,
                                        "uuid_organization"=>$client->uuid_organization,
                                        "uuid_company"=>$client->uuid_company,
                                        "platform"=>"Web"

                                    );
                        $UsersController = new UsersController();
                        if($UsersController->AddUserSql((object)$newUser)){
                            $this->getTokenMyBodytechAction($params);
                        }else{
                            $this->response->setJsonContent(["error"=>"invalid_grant","error_description"=>"Error al registrar el usuario"]);
                            $this->response->setStatusCode(500);
                            $this->response->send();
                        }
                    }
                }else{
                    $this->response->setJsonContent(["error"=>"invalid_grant","error_description"=>"El client es invalido"]);
                    $this->response->setStatusCode(401);
                    $this->response->send(); 
                }
    
            }else{
                $this->response->setJsonContent(["error"=>"invalid_grant","error_description"=>"No tienes acceso a my bodytech"]);
                $this->response->setStatusCode(401);
                $this->response->send();
            }
        }else{
            $this->getTokenMyBodytechAction();
        }

    }

    public function getTokenPrimeAction() {
        $server = $this->getServerPrime();
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
        exit();
    }
    /**
     * Función encargada de validar el token acceso de un usario 
     *
     * @return void
     */
    public function validateAccessAction($ctrlfuntion = null) {

        //Obtengo los encabezados enviados en la petici'on
        $requestHeaders = apache_request_headers();
        $code = 200;
        if((isset($requestHeaders['x-bodytech-client-id']) || isset($requestHeaders['X-Bodytech-Client-Id']))){
            if ((isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization']))) {
                if (isset($requestHeaders['Authorization'])) {
                    $authorization = trim($requestHeaders['Authorization']);
                } else {
                    $authorization = trim($requestHeaders['authorization']);
                }
                
                //Obtengo el token en caso de que venga
                if (preg_match('/^Bearer\\s+(.*?)$/', $authorization, $token)) {
                    $token = isset($token[1]) ? $token[1] : "";
                    $tokenData = OauthAccessTokens::findFirst([
                                "conditions" => "access_token = :token:",
                                "bind" => ['token' => $token]
                    ]);
                    if (empty($tokenData)) {
                        //No existe el token enviado 401
                        $code = 401;
                        $arrayRetorno = $this->response("invalid_token","Invalid token");
                    } elseif ($tokenData->expires < date("Y-m-d H:i:s")) {
                        //token vencido 401
                        $code = 401;
                        $arrayRetorno = $this->response("expired_token","Expired token");
                    }else{
                        //valido que el token pertenesca a la Organization enviada
                        // $tokenOrganization = OauthClients::findFirst([
                        //     "conditions" => "client_id = :client_id: and uuid_organization =:uuid_organization:",
                        //     "bind" => ['client_id' => $tokenData->client_id,'uuid_organization'=>$uuid_organization]]);
                        // if($tokenOrganization){
                            if(isset($ctrlfuntion)){
                                $arrayRetorno = $this->response("success","Token valido",$tokenData);
                            }else{
                                if($tokenData->client_id == 'grupodtg_mybodytech' || $tokenData->client_id == 'biz_app_movil'){
                                    $PHQL = 'SELECT 
                                            C.name as company,U.uuid_company,CT.name as country,CT.id as id_country
                                        FROM UsersInternal AS U 
                                        LEFT JOIN Companies AS C on C.id = U.id_company
                                        LEFT JOIN Countries AS CT on CT.id = C.id_country
                                        WHERE U.id = :user_id:';
                                
                                }else{
                                    $PHQL = 'SELECT 
                                            C.name as company,U.uuid_company,CT.name as country,CT.id as id_country
                                        FROM Users AS U 
                                        LEFT JOIN Companies AS C on C.id = U.id_company
                                        LEFT JOIN Countries AS CT on CT.id = C.id_country
                                        WHERE U.id = :user_id:';
                                }
                                $dataCompany = $this->modelsManager
                                ->executeQuery(
                                    $PHQL,
                                    ['user_id'=>$tokenData->user_id]
                                )->getFirst();
                                $arrayRetorno = array("status"=>"success","message"=>"Token valido",
                                                    "data"=>["user_id"=>empty($tokenData->user_id)?null:$tokenData->user_id,
                                                    "client_id"=>empty($tokenData->client_id)?null:$tokenData->client_id,
                                                    "company_id_country"=> empty($dataCompany->id_country)?null:$dataCompany->id_country,
                                                    "uuid_company"=>empty($dataCompany->uuid_company)?null:$dataCompany->uuid_company,
                                                    "company_country"=>empty($dataCompany->country)?null:$dataCompany->country,
                                                    "company"=>empty($dataCompany->company)?null:$dataCompany->company]);
                            }
                        // }else{
                        //     $code = 401;
                        //     $arrayRetorno = $this->response("error","Company no valida");
                        // }
                    }
                } else {
                    $code = 401;
                    $arrayRetorno = $this->response("invalid_request","Authorization is malformed");
                }
            } else {
                $code = 401;
                if (!(isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization']))){
                    $arrayRetorno = $this->response("invalid_request","Authorization is required");
                }else{
                    $arrayRetorno = $this->response("invalid_request","X-Bodytech-Organization is required");
                }
                
            }
        }else{
            if ((isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization'])) && (isset($requestHeaders['X-Bodytech-Organization']) || isset($requestHeaders['x-bodytech-organization']))) {
                if (isset($requestHeaders['Authorization'])) {
                    $authorization = trim($requestHeaders['Authorization']);
                } else {
                    $authorization = trim($requestHeaders['authorization']);
                }
    
                if (isset($requestHeaders['X-Bodytech-Organization'])) {
                    $uuid_organization = trim($requestHeaders['X-Bodytech-Organization']);
                } else {
                    $uuid_organization = trim($requestHeaders['x-bodytech-organization']);
                }
    
                //Obtengo el token en caso de que venga
                if (preg_match('/^Bearer\\s+(.*?)$/', $authorization, $token)) {
                    $token = isset($token[1]) ? $token[1] : "";
                    $tokenData = OauthAccessTokens::findFirst([
                                "conditions" => "access_token = :token:",
                                "bind" => ['token' => $token]
                    ]);
                    if (empty($tokenData)) {
                        //No existe el token enviado 401
                        $code = 401;
                        $arrayRetorno = $this->response("invalid_token","Invalid token");
                    } elseif ($tokenData->expires < date("Y-m-d H:i:s")) {
                        //token vencido 401
                        $code = 401;
                        $arrayRetorno = $this->response("expired_token","Expired token");
                    }else{
                        //valido que el token pertenesca a la Organization enviada
                        // $tokenOrganization = OauthClients::findFirst([
                        //     "conditions" => "client_id = :client_id: and uuid_organization =:uuid_organization:",
                        //     "bind" => ['client_id' => $tokenData->client_id,'uuid_organization'=>$uuid_organization]]);
                        // if($tokenOrganization){
                            if(isset($ctrlfuntion)){
                                $arrayRetorno = $this->response("success","Token valido",$tokenData);
                            }else{
                                if($tokenData->client_id == 'grupodtg_mybodytech' || $tokenData->client_id == 'biz_app_movil'){
                                    $PHQL = 'SELECT 
                                            C.name as company,U.uuid_company,CT.name as country,CT.id as id_country
                                        FROM UsersInternal AS U 
                                        LEFT JOIN Companies AS C on C.id = U.id_company
                                        LEFT JOIN Countries AS CT on CT.id = C.id_country
                                        WHERE U.id = :user_id:';
                                
                                }else{
                                    $PHQL = 'SELECT 
                                            C.name as company,U.uuid_company,CT.name as country,CT.id as id_country
                                        FROM Users AS U 
                                        LEFT JOIN Companies AS C on C.id = U.id_company
                                        LEFT JOIN Countries AS CT on CT.id = C.id_country
                                        WHERE U.id = :user_id:';
                                }
                                $dataCompany = $this->modelsManager
                                ->executeQuery(
                                    $PHQL,
                                    ['user_id'=>$tokenData->user_id]
                                )->getFirst();
                                $arrayRetorno = array("status"=>"success","message"=>"Token valido",
                                                    "data"=>["user_id"=>empty($tokenData->user_id)?null:$tokenData->user_id,
                                                    "client_id"=>empty($tokenData->client_id)?null:$tokenData->client_id,
                                                    "company_id_country"=> empty($dataCompany->id_country)?null:$dataCompany->id_country,
                                                    "uuid_company"=>empty($dataCompany->uuid_company)?null:$dataCompany->uuid_company,
                                                    "company_country"=>empty($dataCompany->country)?null:$dataCompany->country,
                                                    "company"=>empty($dataCompany->company)?null:$dataCompany->company]);
                            }
                        // }else{
                        //     $code = 401;
                        //     $arrayRetorno = $this->response("error","Company no valida");
                        // }
                    }
                } else {
                    $code = 401;
                    $arrayRetorno = $this->response("invalid_request","Authorization is malformed");
                }
            } else {
                $code = 401;
                if (!(isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization']))){
                    $arrayRetorno = $this->response("invalid_request","Authorization is required");
                }else{
                    $arrayRetorno = $this->response("invalid_request","X-Bodytech-Organization is required");
                }
                
            }
        }
        
       

        $this->response->setStatusCode($code);
        return $arrayRetorno;
    }

    /**
     * Función encargada de validar el token acceso de un cliente
     *
     * @return void
     */
    public function validateAccessActionClient() {

        //Obtengo los encabezados enviados en la petici'on
        $requestHeaders = apache_request_headers();
        $code = 200;
        if (isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization'])) {
            if (isset($requestHeaders['Authorization'])) {
                $authorization = trim($requestHeaders['Authorization']);
            } else {
                $authorization = trim($requestHeaders['authorization']);
            }
            //Obtengo el token en caso de que venga
            if (preg_match('/^Bearer\\s+(.*?)$/', $authorization, $token)) {
                $token = isset($token[1]) ? $token[1] : "";

                if ( $token !== "03147a73cc1784ca53e2498e08b2342a187097a6wwqd3") {
                    # code...
                    
                    $tokenData = OauthAccessTokens::findFirst([
                                "conditions" => "access_token = :token:",
                                "bind" => ['token' => $token]
                    ]);
                    if (empty($tokenData)) {
                        //No existe el token enviado 401
                        $code = 401;
                        $arrayRetorno = $this->response("invalid_request","Invalid token client");
                    } elseif ($tokenData->expires < date("Y-m-d H:i:s")) {
                        //token vencido 401
                        $code = 401;
                        $arrayRetorno = $this->response("expired_token","Expired_token client");
                    }else{
                        $arrayRetorno = $this->response("success","Token valido",$tokenData->client_id);
                    }
                }else{
                    $arrayRetorno = $this->response("success","Token valido");
                }
            } else {
                $code = 401;
                $arrayRetorno = $this->response("invalid_request","Authorization is malformed");
            }
        } else {
            $code = 401;
            $arrayRetorno = $this->response("invalid_request","Authorization is required");
            
            
        }
       

        $this->response->setStatusCode($code);
        return $arrayRetorno;
    }
    /**
     * Función encargada de enviar correo, con un enlace que cauduca cada 24h
     * el cual te permitira cambiar tu contraseña
     *
     * @return void
     */
    public function recoverypasswordAction($email,$client_id) {
        try {
            $code = 200;

            $client = OauthClients::findFirstByclient_id($client_id);
            
            $user= Users::findFirst(
                ["columns"=>'id,email,id_organization,id_company,id_country,first_name,last_name,msn',
                'conditions' => 'id_organization=:id_organization: and email=:email: and is_active = 1',
                'bind'=>['id_organization'=>$client->id_organization,
                        'email'=>$email]]);
    
            if(!empty($user)){
                $id_company = $user->id_company;
                
                if(isset($id_company)){
                    $template_company_email = TemplateCompanyEmail::findFirst(
                        ['conditions' => 'id_company=:id_company: and type="change password"',
                        'bind'=>['id_company'=>$id_company]]);
                }else{
                    $template_company_email = TemplateCompanyEmail::findFirst(
                        ['conditions' => 'id_company= is null and id_organization = :id_organization:  and type="change password"',
                        'bind'=>['id_organization'=>$client->id_organization]]);
                }
    
                if(isset($template_company_email)){
                    $today = date('Y-m-d H:i:s');
                    $password_reset = PasswordResets::findFirst(
                        ['conditions' => 'id_user=:id_user: and status = 1 and expire > :today:',
                        'bind'=>['id_user'=>$user->id,
                                'today'=>$today]]);
                    $ctrl = false;
                    if(isset($password_reset)){
                        $data = array(
                            "email" => $email,
                            "token_reset" => $password_reset->token_reset,
                            "data_template"=>json_decode($template_company_email->data_template)
                        );
                        $ctrl = true;
                    }else{
                        //generate random token 
                        $token = bin2hex(random_bytes(20));
                        $data = array(
                            "email" => $email,
                            "token_reset" => $token,
                            "data_template"=>json_decode($template_company_email->data_template)
                        );
    
                        //create new password reset request
                        $new_password_reset = new PasswordResets();
                        $new_password_reset->id_user = $user->id;
                        $new_password_reset->token_reset = $token;
                        $new_password_reset->created_at = $today;
                        $new_password_reset->expire = date("Y-m-d H:i:s",strtotime($today."+ 1 days"));
                        $ctrl = $new_password_reset->save();
    
                    }
    
                    if($ctrl){
                        if(isset($client->id_organization) && $client->id_organization == 2){
                            $company_name = "athletic";
                        }else{
                            $company_name = "bodytech";
                        }
                        if($user->msn == 1){
                            $template_html = $this->view->render('emails/password_recovery'.$template_company_email->name_file, $data); //html template
                            $subject = 'Recupera tu contraseña'; //subject
                            $send_to = array("email" => $user->email, "name" => $user->first_name . ' ' . $user->last_name); //send to 
                            $this->send_email($template_html, $subject, $send_to,null,$company_name);
                            $arrayRetorno = $this->response("success","Se ha enviado un correo electrónico con las instrucciones para cambiar la contraseña, por favor verífique.");
                        }else{
                            $arrayRetorno = $this->response("error","Este usuario tiene desabilitado el envio de correo, favor cominicarse con soporte");
                        }
                        
                    }else{
                        $code = 500;
                        $arrayRetorno = $this->response("error","Problemas al guardar el token de reset");
                    }
                    
                }else{
                    $arrayRetorno = $this->response("error","La compañia no tiene definido un template para envio de correo");
                }
    
            }else{
                $arrayRetorno = $this->response("error","Usuario no existente");
            }
        } catch (\Exception $ex) {
            $code = 500;
            $arrayRetorno = $this->response("error","Error en el servidor",$ex->getMessage());
        }

        $this->response->setStatusCode($code);
        return $arrayRetorno;
    }

    /**
     * Función encargada de cambiar contraseña si el token del enlace es valido
     *
     * @return void
     */
    public function passwordresetAction($params) {
        try {
            $password_reset = PasswordResets::findFirst(
                ['conditions' => 'token_reset=:token: and status = 1',
                'bind'=>['token'=>$params->token]]);

            if (isset($password_reset)) {
               if (date('Y-m-d H:i:s') > $password_reset->expire) {
                    $code = 400;
                    $arrayRetorno = $this->response("error","El enlace ya caduco");
               }else{
                $user = Users::findFirstById($password_reset->id_user);
                if (!empty($user)) {
                    //Update password
                    $user->password = $this->CreatePassword($params->password);
                    $user->save();
                    //change status password reset requests 
                    $password_reset->status = 0;
                    $password_reset->save();
                    
                    $code = 200;
                    $arrayRetorno = $this->response("success","La contraseña se cambio exitosamente");
                } else {
                    $code = 400;
                    $arrayRetorno = $this->response("error","El usuario no existe");
                }
               }
            }else{
                $code = 400;
                $arrayRetorno = $this->response("error","token invalido");
            }
        } catch (\Exception $ex) {
            $code = 500;
            $arrayRetorno = $this->response("error","Error en el servidor");
        }

        $this->response->setStatusCode($code);
        return $arrayRetorno;
    }

    /**
     * Función encargada de logiarse en el directorio activo de bodytech
     *
     */
    public function loginActiveDirectory($user,$pass){ 
        $dominio = 'bodytech.loc';
        $dn = 'dc=Bodytech,dc=loc';
        $ldaprdn = trim($user).'@'.$dominio;  
        $ldappass = trim($pass);  
        $ds = $dominio; 
        $puertoldap = 389;  
        $ldapconn = ldap_connect($ds,$puertoldap); 
          ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);  
          ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0);  
          $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);  
          if ($ldapbind){ 
            $filter="(|(SAMAccountName=".trim($user)."))"; 
            $fields = array("sn", "givenname","name","l","SAMAccountName","mobile","streetAddress",
            "title","mail","targetAddress","c","company","homephone","mobile","postalCode","streetAddress","telephoneNumber"); 
            $sr = @ldap_search($ldapconn, $dn, $filter, $fields);  
            $info = @ldap_get_entries($ldapconn, $sr);  
            //$array = $info[0]["samaccountname"][0]; 
            $result = true;
          }else{ 
            $result = false;
          }  
        ldap_close($ldapconn);  
        return $result; 
   }  

   /**
     * Función encargada de validar el token acceso de un usario 
     *
     * @return void
     */
    public function validateAccessNewAction($ctrlfuntion = null) {

        //Obtengo los encabezados enviados en la petici'on
        $requestHeaders = apache_request_headers();
        $code = 200;

        //if ((isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization'])) && (isset($requestHeaders['X-Bodytech-Client-Id']) || isset($requestHeaders['x-bodytech-client-id']))) {
            if ((isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization']))) {
            if (isset($requestHeaders['Authorization'])) {
                $authorization = trim($requestHeaders['Authorization']);
            } else {
                $authorization = trim($requestHeaders['authorization']);
            }

            // if (isset($requestHeaders['X-Bodytech-Client-Id'])) {
            //     $client = trim($requestHeaders['X-Bodytech-Client-Id']);
            // } else {
            //     $client = trim($requestHeaders['x-bodytech-client-id']);
            // }

            //Obtengo el token en caso de que venga
            if (preg_match('/^Bearer\\s+(.*?)$/', $authorization, $token)) {
                $token = isset($token[1]) ? $token[1] : "";
                // $tokenData = OauthAccessTokens::findFirst([
                //             "conditions" => "access_token = :token: and client_id=:client_id:",
                //             "bind" => ['token' => $token,'client_id'=>$client]
                // ]);
                $tokenData = OauthAccessTokens::findFirst([
                    "conditions" => "access_token = :token:",
                    "bind" => ['token' => $token]
                ]);
                if (empty($tokenData)) {
                    //No existe el token enviado 401
                    $code = 401;
                    $arrayRetorno = $this->response("invalid_token","Invalid token");
                } elseif ($tokenData->expires < date("Y-m-d H:i:s")) {
                    //token vencido 401
                    $code = 401;
                    $arrayRetorno = $this->response("expired_token","Expired token");
                }else{
                    //valido que el token pertenesca a la marca enviada
                   
                    if(isset($ctrlfuntion)){
                        $arrayRetorno = $this->response("success","Token valido",$tokenData);
                    }else{
                        if($tokenData->client_id == 'grupodtg_mybodytech' || $tokenData->client_id == 'biz_app_movil'){
                            $UsersCollaborators = $tokenData->UsersCollaborators;
                            //if($UsersClient->uuid_brand == $uuid_brand){
                                $arrayRetorno = array("status"=>"success","message"=>"Token valido",
                                "data"=>["user_id"=> empty($tokenData->user_id)?null:$tokenData->user_id,
                                "client_id"=>$tokenData->client_id,
                                "uuid_company"=>null,
                                "company_id"=>null,
                                "uuid_brand"=>null,
                                "brand_id"=>null,
                                "brands"=>[],
                                "organization_id"=>empty($UsersCollaborators->organization_id)?null:$UsersCollaborators->organization_id,
                                "uuid_organization"=>empty($UsersCollaborators->uuid_organization)?null:$UsersCollaborators->uuid_organization,
                                "type_user" =>'collaborator',
                                "company_id_country"=> null,
                                "company_country"=> null,
                                "company"=> null]);
                                if(isset($UsersCollaborators->UsersCollaboratorsBrands)){
                                    foreach ($UsersCollaborators->UsersCollaboratorsBrands as $key => $value) {
                                        $arrayRetorno['data']['brands'][] = array('uuid_brand'=>$value->uuid_brand,
                                                                                    'brand_id'=>$value->brand_id,
                                                                                    'company_id'=>$value->company_id,
                                                                                    'uuid_company'=>$value->uuid_company,
                                                                                    'organization_id'=>$value->organization_id,
                                                                                    'uuid_organization'=>$value->uuid_organization);
                                    }
                                }
                            // }else{
                            //     $code = 401;
                            //     $arrayRetorno = $this->response("error","token no valido en esta plataforma");
                            // }
                        
                        }else{
                            $UsersClient = $tokenData->UsersClient;
                            //if($UsersClient->uuid_brand == $uuid_brand){
                                $arrayRetorno = array("status"=>"success","message"=>"Token valido",
                                "data"=>["user_id"=> empty($tokenData->user_id)?null:$tokenData->user_id,
                                "client_id"=>$tokenData->client_id,
                                "uuid_company"=>empty($UsersClient->uuid_company)?null:$UsersClient->uuid_company,
                                "company_id"=>empty($UsersClient->company_id)?null:$UsersClient->company_id,
                                "uuid_brand"=>empty($UsersClient->uuid_brand)?null:$UsersClient->uuid_brand,
                                "brand_id"=>empty($UsersClient->brand_id)?null:$UsersClient->brand_id,
                                "organization_id"=>empty($UsersClient->organization_id)?null:$UsersClient->organization_id,
                                "uuid_organization"=>empty($UsersClient->uuid_organization)?null:$UsersClient->uuid_organization,
                                "type_user" =>'client',
                                "company_id_country"=> null,
                                "company_country"=> null,
                                "company"=> null]);
                            // }else{
                            //     $code = 401;
                            //     $arrayRetorno = $this->response("error","token no valido en esta plataforma");
                            // }
                        }
                    }
            }
            } else {
                $code = 401;
                $arrayRetorno = $this->response("invalid_request","Authorization is malformed");
            }
        } else {
            $code = 401;
            if (!(isset($requestHeaders['Authorization']) || isset($requestHeaders['authorization']))){
                $arrayRetorno = $this->response("invalid_request","Authorization is required");
            }else{
                $arrayRetorno = $this->response("invalid_request","X-Bodytech-Organization is required");
            }
            
        }
       

        $this->response->setStatusCode($code);
        return $arrayRetorno;
    }
    
}
