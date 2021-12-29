<?php
class UsersClientController extends BaseController
{

    public function list_brand_client($client_id,$params = null,$ctrl = true)
    {
        try {
            if(empty($params)){
                $params = ["columns"=>'brand_id',
                'conditions' => 'client_id=:client_id:',
                'bind'=>['client_id'=>$client_id]];
            }
            $OauthClientsBrands = OauthClientsBrands::find($params)->toArray();
                
            if(count($OauthClientsBrands) > 0){
                if($ctrl){
                    $brandArray = [];
                    foreach ($OauthClientsBrands as $key => $value) {
                        $brandArray[] = $value['brand_id'];
                    }
                    return $brandArray;
                }
                
                return $OauthClientsBrands;
            }
            return false;

        } catch (\Exception $ex) {
            return false;
        }
    }

    public function adduserActionNew($params) {
        $code = 200;
        try {
            if($params->document_type_id == 2 || $params->document_type_id == 1){
                $verificationCodeDniAndRut = $this->verificationCodeDniAndRut($params->document_number,$params->document_type_id);
                if($verificationCodeDniAndRut["status"] == "success"){
                    $params->dv = $verificationCodeDniAndRut["dv"];
                }else{
                    $arrayRetorno = $this->response('error',$verificationCodeDniAndRut["message"]);
                    $this->response->setStatusCode($code);
                    return $arrayRetorno;
                }
            }
                $OauthClients = $this->getRedis($params->client_id."-Oauth");
                
                if(empty($OauthClients)){
                    
                    $PHQL = 'SELECT 
                            ocb.uuid_company,ocb.uuid_organization,ocb.company_id,ocb.id_country_company as id_country,ocb.organization_id,oc.client_id,oc.client_secret,ocb.brand_id,ocb.uuid_brand
                        FROM OauthClients AS oc 
                        LEFT JOIN OauthClientsBrands AS ocb on ocb.client_id = oc.client_id and ocb.brand_id = oc.brand_id
                        WHERE oc.client_id = :client_id:';
                    $OauthClients = $this->modelsManager
                    ->executeQuery(
                        $PHQL,
                        ['client_id'=>$params->client_id]
                    )->getFirst();
                    // se guarda en cache
                    $this->setRedis($params->client_id."-Oauth",$OauthClients ,18000);
                }
            if($OauthClients){
                
                if($OauthClients->brand_id != null){
                    //valido si este aplicativo cliente tiene una brand_id por default o es un aplicativo tipo movil
                    $params->brand_id = $OauthClients->brand_id;
                    $params->uuid_brand = $OauthClients->uuid_brand;
                    $params->company_id = $OauthClients->company_id;
                    $params->uuid_company = $OauthClients->uuid_company;
                    $params->organization_id = $OauthClients->organization_id;
                    $params->uuid_organization = $OauthClients->uuid_organization;
                    // si no viene pais se le asigna el de la compañia
                    if (!isset($params->id_country)) {
                        $params->id_country = $OauthClients->id_country;
                    }
                }else{
                    // movil o platafora que no tenga asginada compañia tiene que mandar pais
                    if (!isset($params->id_country)) {
                        $code = 400;
                        $arrayRetorno = $this->response('error','El pais es requerido si tu aplicacion no tiene una marca asignada');
                        $this->response->setStatusCode($code);
                        return $arrayRetorno;
                    }else{
                        $bind = ['conditions' => 'client_id=:client_id: and id_country_company=:id_country_company:',
                                    'bind'=>['client_id'=>$params->client_id,'id_country_company'=>$params->id_country]];
                        $brands = $this->list_brand_client($params->client_id,$bind,false);
                        $params->brand_id = isset($brands[0]['brand_id'])?$brands[0]['brand_id']:4;
                        $params->uuid_brand = isset($brands[0]['uuid_brand'])?$brands[0]['uuid_brand']:'55d4eb8e-3761-11ec-a258-0e56c583c695';
                        $params->company_id = isset($brands[0]['company_id'])?$brands[0]['company_id']:7;
                        $params->uuid_company = isset($brands[0]['uuid_company'])?$brands[0]['uuid_company']:'2066212fa-6aae-4c02-b28f-5263aerffeww';
                        $params->organization_id = isset($brands[0]['organization_id'])?$brands[0]['organization_id']:1;
                        $params->uuid_organization = isset($brands[0]['uuid_organization'])?$brands[0]['uuid_organization']:'7134d120-bfc9-11eb-b063-062b475b052b';
                        
                    }
                }
                //temporal mientras se cabia el oAuth
                $params->password = isset($params->password) ? $params->password:"BENEFICIARIO";
                $params->id_organization = $params->organization_id;
                $params->id_company = $params->company_id;
                $params->username = $params->email;
                $params->client_secret = $OauthClients->client_secret;
                $params->grant_type = "password";
                $documenDW = $this->getTypeDocument($params->document_type_id);
                $bransArray = $this->list_brand_client($params->client_id);
                if(isset($documenDW)){
                    $user = UsersClient::findFirst(
                        ["column"=>'email,brand_id',
                        'conditions' => 'email=:email: and  brand_id IN ({bransArray:array})',
                        'bind'=>['bransArray'=>$bransArray,
                                'email'=>$params->email]]);
                    if(empty($user)){
                        $userDocument = UsersProfile::findFirst(
                            ["column"=>'document_number,document_type',
                            'conditions' => 'document_number = :document_number: and document_type =:document_type: and  brand_id IN ({bransArray:array})',
                            'bind'=>['bransArray'=>$bransArray,
                                    'document_number'=>$params->document_number,
                                    'document_type'=>$params->document_type_id]]);
                        if(empty($userDocument)){
                            $this->dbMaria->begin();
                            $UsersController = new UsersController();
                            $newUserOlder = $UsersController->AddUserSql($params);
                            $newUser = $this->AddUserSqlNew($params,$newUserOlder->id);
                            //332072
                            //var_dump($newUser);
                            if($newUser && $newUserOlder){
                                $this->dbMaria->commit();
                                //hago login
                                $this->registerUserElasticSearch($newUser->id,$newUser->Brand->name);
                                $_POST = (array)$params;
                                $urls = $this->params->urlLocal . "oauth/token";
                                $output = rtrim($this->PostCurlInterno($urls,$_POST));
                                $data = explode("\n",$output);
                                $arrayRetorno =json_decode($data[count($data)-1]);
                                array_shift($data);
                                $headers= [];
                                foreach($data as $part){

                                    //some headers will contain ":" character (Location for example), and the part after ":" will be lost, Thanks to @Emanuele
                                    $middle = explode(":",$part,2);
                                
                                    //Supress warning message if $middle[1] does not exist, Thanks to @crayons
                                    if ( !isset($middle[1]) ) { $middle[1] = null; }
                                
                                    $headers[trim($middle[0])] = trim($middle[1]);
                                }
                                $this->response->setHeader('x-bodytech-company', $headers["x-bodytech-company"]);
                                $this->response->setHeader('x-bodytech-organization', $headers["x-bodytech-organization"]);
                                $this->response->setHeader('x-bodytech-brand', $headers["x-bodytech-brand"]);
                            
                                
                            }else{
                                $code = 400;
                                $this->dbMaria->rollback();
                                $arrayRetorno = $this->response('error','Problemas al regstrar el usuario');
                            } 
                            
                        }else{
                            $arrayRetorno = $this->response('error','Este documento ya se encuentra asignado a un usuario');
                        }
            
                    }else{
                        $arrayRetorno = $this->response('error','Este correo ya se encuentra asignado a un usuario');
                        
                    }
                }else{
                    $code = 400;
                    $arrayRetorno = $this->response('error','El tipo de documento es invalido');
                }             
            }else{
                $code = 400;
                $arrayRetorno = $this->response('error','Cliente invalido');
            } 
        } catch (\Exception $ex) {
            $arrayRetorno = $this->response('error',$ex->getMessage());
        }
        $this->response->setStatusCode($code);
        return $arrayRetorno;
        
    }

    public function AddUserSqlNew($user,$id = null) {

        $usernew = new UsersClient();
        if(isset($id)){
            $usernew->id = $id;
        }
        $user_name = explode("@",$user->email);
        $usernew->email = $user->email;
        $usernew->user_name = isset($user->user_name)?$user->user_name:$user_name[0];
        $usernew->password = $this->CreatePassword((string)$user->password);
        $usernew->created_at = date("Y-m-d H:i:s");
        $usernew->status = 1;
        $usernew->mobile_phone = $user->phone;
        $usernew->company_id = $user->company_id;
        $usernew->organization_id = $user->organization_id;
        $usernew->uuid_organization = $user->uuid_organization;
        $usernew->brand_id = $user->brand_id;
        $usernew->uuid_brand = $user->uuid_brand;
        $usernew->uuid_company = $user->uuid_company;

        $UsersProfile = new UsersProfile();
        $UsersProfile->city_id = isset($user->city_id) ? $user->city_id : $this->getCity($user->id_country);
        $UsersProfile->first_name = $user->name;
        $UsersProfile->last_name = $user->lastname;
        $UsersProfile->terms_data = $user->terms_data;
        $UsersProfile->dv = isset($user->dv)?$user->dv:null;
        $UsersProfile->is_superuser = 0;///
        $UsersProfile->is_staff = 0;///
        $UsersProfile->type_user = !empty($user->type_user)?  $user->type_user: 0;
        $UsersProfile->address = '';
        $UsersProfile->display_image = null;
        $UsersProfile->genre = 3;
        $UsersProfile->platform = $user->platform;
        $UsersProfile->brand_id = $user->brand_id;
        $UsersProfile->id_country = $user->id_country;
        $UsersProfile->document_number = $user->document_number;
        $UsersProfile->document_type = $user->document_type_id;
        $UsersProfile->id_tinnova = isset($user->id_tinnova) ? $user->id_tinnova : null;//$user->id_tinnova;///
        $UsersProfile->birthdate = $user->birthdate;
        $UsersProfile->validate_cron = isset($user->validate_cron)?$user->validate_cron:0;
        $UsersProfile->email = $user->email;
        $UsersProfile->user_name = $usernew->user_name;
        $UsersProfile->mobile_phone = $user->phone;
        $usernew->UsersProfile = $UsersProfile;
        

        $result = $usernew->save();
        if($result){
            return $usernew;
        }

        //var_dump($usernew->getMessages());
        return false;

        
        
    }

    public function registerUserElasticSearch($id_user,$brand_name = null)
    {
        $PHQL = 'SELECT 
                    u.id,
                    u.id as user_id,
                    u.id as user_id_test,
                    up.first_name,
                    up.last_name,
                    u.email,
                    u.status as is_active,
                    u.status,
                    up.address,
                    up.display_image,
                    up.genre,
                    up.document_number,
                    up.document_type,
                    up.city_id,
                    up.id_tinnova,
                    up.birthdate,
                    u.mobile_phone,
                    d.name as document_type_name,
                    d.external_code,
                    up.id_country,
                    up.id_country as country_id,
                    u.organization_id,
                    u.company_id,
                    u.organization_id as id_organization,
                    u.company_id as id_company,
                    u.uuid_organization,
                    u.uuid_company,
                    u.uuid_brand,
                    u.brand_id,
                    null as city_name,
                    null as country_name,
                    up.dv
                FROM UsersClient AS u
                INNER JOIN UsersProfile AS up on up.user_id = u.id
                LEFT JOIN DocumentType AS d on d.id = up.document_type
                WHERE u.id = :id:';
        $users = $this->modelsManager
        ->executeQuery(
            $PHQL,
            ['id'=>$id_user]
        )->getFirst();

            if(!empty($brand_name)){
                $users->brand_name = $brand_name;
            }
            $arryaUser = [$users];
            return $this->_bulkElasticSearch($this->params->ElasticSearch->indexOauth,$arryaUser,1);
            
    }

    public function myProfile($id_user)
    {
        $user_profile = UsersProfile::findFirstByUserId($id_user);
        if ($user_profile) {
            $arryaUser = ["id" => $user_profile->user_id,
            "user_id" => $user_profile->user_id,
            "first_name" => $user_profile->first_name,
            "last_name" => $user_profile->last_name,
            "email" => $user_profile->UsersClient->email,
            "status" => $user_profile->UsersClient->status,
            "is_active" => $user_profile->UsersClient->status,
            "address" => $user_profile->address,
            "display_image" => $user_profile->display_image,
            "genre" => $user_profile->genre,
            "document_number" => $user_profile->document_number,
            "document_type" => $user_profile->document_type,
            "city_id" => $user_profile->city_id,
            "id_tinnova" => $user_profile->id_tinnova,
            "birthdate" => $user_profile->birthdate,
            "mobile_phone" => $user_profile->UsersClient->mobile_phone,
            "document_type_name" => $user_profile->DocumentType->name,
            "external_code" => $user_profile->DocumentType->external_code,
            "id_country" => $user_profile->id_country,
            "id_organization" => $user_profile->UsersClient->organization_id,
            "id_company" => $user_profile->UsersClient->company_id,
            "organization_id" => $user_profile->UsersClient->organization_id,
            "company_id" => $user_profile->UsersClient->company_id,
            "uuid_organization" => $user_profile->UsersClient->uuid_organization,
            "uuid_company" => $user_profile->UsersClient->uuid_company,
            "dv"=>$user_profile->dv,
            "msn"=>$user_profile->msn,
            "sms"=>$user_profile->sms,
            "push"=>$user_profile->push];
            $arrayRetorno = $this->response('success','success',$arryaUser);
        }else{
            $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
        }

    
        $this->response->setStatusCode(200);
        return $arrayRetorno;
        
    }

    public function myProfileIdTinnova($id_tinnova,$brand_id)
    {
        $user_profile = UsersProfile::findFirst(array(
            "conditions" => "id_tinnova=:id_tinnova: and brand_id = :brand_id:",
            "bind" => ['id_tinnova' => $id_tinnova,'brand_id'=>$brand_id]
        ));
        if ($user_profile) {
            $arryaUser = ["id" => $user_profile->user_id,
            "user_id" => $user_profile->user_id,
            "first_name" => $user_profile->first_name,
            "last_name" => $user_profile->last_name,
            "email" => $user_profile->UsersClient->email,
            "status" => $user_profile->UsersClient->status,
            "is_active" => $user_profile->UsersClient->status,
            "address" => $user_profile->address,
            "display_image" => $user_profile->display_image,
            "genre" => $user_profile->genre,
            "document_number" => $user_profile->document_number,
            "document_type" => $user_profile->document_type,
            "city_id" => $user_profile->city_id,
            "id_tinnova" => $user_profile->id_tinnova,
            "birthdate" => $user_profile->birthdate,
            "mobile_phone" => $user_profile->UsersClient->mobile_phone,
            "document_type_name" => $user_profile->DocumentType->name,
            "external_code" => $user_profile->DocumentType->external_code,
            "id_country" => $user_profile->id_country,
            "id_organization" => $user_profile->UsersClient->organization_id,
            "id_company" => $user_profile->UsersClient->company_id,
            "organization_id" => $user_profile->UsersClient->organization_id,
            "company_id" => $user_profile->UsersClient->company_id,
            "uuid_organization" => $user_profile->UsersClient->uuid_organization,
            "uuid_company" => $user_profile->UsersClient->uuid_company,
            "dv"=>$user_profile->dv];
            $arrayRetorno = $this->response('success','success',$arryaUser);
        }else{
            $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
        }

    
        $this->response->setStatusCode(200);
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
            if(empty($client->brand_id)){
                $bransArray = $this->list_brand_client($client_id);
                $user= UsersClient::findFirst(
                    ["column"=>'id,email,brand_id',
                    'conditions' => 'email=:email: and brand_id IN ({bransArray:array}) and status = 1',
                    'bind'=>['bransArray'=>$bransArray,
                            'email'=>$email]]);
            }else{
                $user= UsersClient::findFirst(
                    ["column"=>'id,email,brand_id',
                    'conditions' => 'email=:email: and brand_id=:brand_id: and status = 1',
                    'bind'=>['brand_id'=>$client->brand_id,
                            'email'=>$email]]);
            }
    
            if(!empty($user)){
                $template_company_email = TemplateCompanyEmail::findFirst(
                    ['conditions' => 'brand_id=:brand_id: and type="change password"',
                    'bind'=>['brand_id'=>$user->brand_id]]);

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
                        if(isset($user->brand_id) && $user->brand_id == 2){
                            $company_name = "athletic";
                        }else{
                            $company_name = "bodytech";
                        }
                        $template_html = $this->view->render('emails/password_recovery'.$template_company_email->name_file, $data); //html template
                        $subject = 'Recupera tu contraseña'; //subject
                        $send_to = array("email" => $user->email, "name" => $user->email); //send to 
                        $this->send_email($template_html, $subject, $send_to,null,$company_name);
                        $arrayRetorno = $this->response("success","Se ha enviado un correo electrónico con las instrucciones para cambiar la contraseña, por favor verífique.");
                    }else{
                        $code = 500;
                        $arrayRetorno = $this->response("error","Problemas al guardar el token de reset");
                    }
                    
                }else{
                    $arrayRetorno = $this->response("error","La marca no tiene definido un template para envio de correo");
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
                $user = UsersClient::findFirstById($password_reset->id_user);
                $userOld = Users::findFirstById($password_reset->id_user);
                if (!empty($user) && !empty($userOld)) {
                    //Update password
                    $user->password = $this->CreatePassword($params->password);
                    $user->save();

                    //Update password users table old
                    $userOld->password = $user->password;
                    $userOld->save();
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

    public function createdUserOfDw($document,$document_type,$brand_id){
        try {
            //$client = $this->getRedis($client_id);
            // if(empty($client)){
            //     $client = OauthClients::findFirstByclient_id($client_id);
            //     $this->setRedis($client_id,$client ,18000);
            // }
            $this->dbMaria->begin();
            $BrandsCousins = BrandsCousins::findFirstByBrandId($brand_id);
            if($BrandsCousins){
                $bransArray = explode(',',$BrandsCousins->cousins);
                $userDocument = UsersProfile::findFirst(
                    ["column"=>'user_id,first_name,last_name,document_number,document_type',
                    'conditions' => 'document_number = :document_number: and document_type =:document_type: and brand_id IN ({bransArray:array})',
                    'bind'=>['bransArray'=>$bransArray,
                            'document_number'=>$document,
                            'document_type'=>$document_type]]);
                    
                if(empty($userDocument)){
                    if($brand_id == 2){
                        $company_name = "athletic";
                    }else{
                        $company_name = "bodytech";
                    }
    
                    $id_country = $BrandsCousins->id_country_company;
    
                    if(isset($this->params->DeporWin[$id_country][$company_name]->status) && $this->params->DeporWin[$id_country][$company_name]->status){
                    
                        //valido por numero de documento en DW
                        $urls = $this->UrlBaseDW($id_country,$company_name) . "/personas?DocumentoIdentidad=" . $document;
                        $dadas = json_decode($this->GetCurl($urls,$id_country,$company_name), false);
                        $ctrl = false;
                        if (!empty($dadas->Personas) && count($dadas->Personas) > 0) {
                            $documenDW = $this->getTypeDocument($document_type);
                            $userDW = [];
                            //valido por numero de documento y tipo de documento en DW
                            $documenDW = empty($documenDW->id)?101:$documenDW->id;
                            foreach ($dadas->Personas as $Persona) {
                                if($Persona->TipoDocumentoIdentidad->Id == $documenDW && $Persona->DocumentoIdentidad == $document){
                                    $userDW = $Persona;
                                    $ctrl = true;
                                    break;
                                }
                            }
    
                            if($ctrl){
                                $password = rand(1111111111,9999999999);
                                $email = isset($userDW->Email)?$userDW->Email:$document.'@bodytechcorpdw.com';
                                $apellido1 = isset($userDW->Apellido1)?$userDW->Apellido1:'';
                                $apellido2 = isset($userDW->Apellido2)?$userDW->Apellido2:'';
                                $userDB = (object)[
                                    "name" => $userDW->Nombre,
                                    "lastname" => $apellido1." ".$apellido2,
                                    "email" => $email,
                                    "password" => $password,
                                    "document_number" => $document,
                                    "document_type_id" => $document_type,
                                    "id_tinnova" => $userDW->Id,
                                    "birthdate" => $userDW->FechaNacimiento,
                                    "phone" => isset($userDW->TelefonoMovil)?$userDW->TelefonoMovil:null,
                                    "id_country" => $id_country,
                                    "id_organization" => $BrandsCousins->organization_id,
                                    "id_company" => $BrandsCousins->company_id,
                                    "organization_id" => $BrandsCousins->organization_id,
                                    "company_id" => $BrandsCousins->company_id,
                                    "brand_id" => $BrandsCousins->brand_id,
                                    "uuid_brand" => $BrandsCousins->uuid_brand,
                                    "uuid_organization" => $BrandsCousins->uuid_organization,
                                    "uuid_company" => $BrandsCousins->uuid_company,
                                    "platform"=>"DW",
                                    "terms_data"=>true,
                                    "validate_cron"=>1
                                ];
                                $userEmail = UsersClient::findFirst(
                                    ["column"=>'email,organization_id',
                                    'conditions' => 'email=:email: and brand_id IN ({bransArray:array})',
                                    'bind'=>['bransArray'=>$bransArray,
                                            'email'=>$email]]);
                
                                if(empty($userEmail)){
                                    $UsersController = new UsersController();
                                    $newUserOlder = $UsersController->AddUserSql($userDB);
                                    $newUser = $this->AddUserSqlNew($userDB,$newUserOlder->id);
                                    if($newUser && $newUserOlder){
                                        $this->dbMaria->commit();
                                        $this->registerUserElasticSearch($newUser->id,$newUser->Brand->name);
                                        $arrayRetorno = array('status'=>'success','message'=>'success','data'=>['user_id'=>$newUser->id,'first_name'=>$newUser->UsersProfile[0]->first_name,'last_name'=>$newUser->UsersProfile[0]->last_name]);
                                    }else{
                                        $this->dbMaria->rollback();
                                        $arrayRetorno = array('status'=>'error','message'=>["message"=> "error al registrar el usario de dw"]);
                                    }
                                }else{
                                    $this->dbMaria->rollback();
                                    $arrayRetorno = array('status'=>'error','message'=>["message"=> "ya existe un usuario con este correo de dw"]);
                                }
                            }else{
                                $this->dbMaria->rollback();
                                $arrayRetorno = array('status'=>'error','message'=>["message"=> "No se encontro el usuario en dw"]);
                            }
    
                        } else {
                            $this->dbMaria->rollback();
                            $arrayRetorno = array('status'=>'error','message'=>["message"=> "No se encontro el usuario en dw"]);
                        }
                    
                    }else{
                        $this->dbMaria->rollback();
                        $arrayRetorno = array('status'=>'error','message'=>["message"=> "No existe dw para esta compañia"]);
                    }
                }else{
                    $this->dbMaria->rollback();
                    $arrayRetorno = array('status'=>'success','message'=>'success','data'=>['user_id'=>$userDocument->user_id,'first_name'=>$userDocument->first_name,'last_name'=>$userDocument->last_name]);
                }
                
                
            }else{
                $this->dbMaria->rollback();
                $arrayRetorno = array('status'=>'error','message'=>["message"=> "brand no tiene brands asociadas"]);
            }
        } catch (\Exception $ex) {
            $this->dbMaria->rollback();
            $arrayRetorno = array('status'=>'error','message'=>["message"=> $ex->getMessage()]);
        }
        return  (object)$arrayRetorno;
    }

    public function getUserDw($document,$document_type,$brand_id)
    {

        $arrayRetorno = $this->createdUserOfDw($document,$document_type,$brand_id);
        $this->response->setStatusCode(200);
        return $arrayRetorno;

    }

    // Tomaremos como ejemplo el DNI 17801146.
    // Separamos cada uno de los dígitos 1, 7, 8, 0, 1, 1, 4, 6.
    // Multiplicamos cada dígito por esta serie en el mismo orden 3, 2, 7, 6, 5, 4, 3, 2 de esta forma: 1 x 3, 7 x 2, 8 x 7, 0 x 6, 1 x 5, 1 x 4, 4 x 3, 6 x 2
    // Sumamos todos los productos dándonos el resultado de 106
    // Dividimos el resultado anterior entre 11 y tomamos el residuo: 106/11 = 9 sobrándonos 7 (9 x 11 = 99 para 106 nos faltaría 7)
    // Al valor 11 (por defecto) le restamos el resultado anterior 7 , lo que nos daría 4. *Excepción, si el resultado del punto 6 sería 11, es decir 11 – 0 = 11 (0 es el resultado del punto 5, es decir se trata de una división exacta que no tiene residuo) entonces el resultado sería 0 y no 11.
    // Al resultado anterior le sumamos 1, es decir 4 + 1 = 5 lo que significa que vamos a buscar la 5ta posición en la serie NUMERICA (por defecto) 6, 7, 8, 9, 0, 1, 1, 2, 3, 4, 5 ó la 5ta posición en la serie ALFABÉTICA (por defecto) K, A, B, C, D, E, F, G, H, I, J.
    // El dígito verificador o dígito de validación son los pintados en color azul.


    public function verificationCodeDniAndRut($document,$document_type)
    {
        $arrayRetorno = ["status"=>"success","message"=>"success","data"=>[]];
        $sum = 0;
        $deafult = 11;
        //DNI
        if($document_type == 2){
            $serial_number = [3, 2, 7, 6, 5, 4, 3, 2];
            $verification_digits = [6, 7, 8, 9, 0, 1, 1, 2, 3, 4, 5];
            if(is_numeric($document)){
                $numberArray = str_split($document);
                if(count($numberArray ) == 8){
                
                    foreach ($numberArray as $key => $value) {
                        $result_mult = $value * $serial_number[$key];
                        $sum = $result_mult+$sum;
                    }
                    $residue = (int)($sum /$deafult);
                    $value_to_subtract = $residue*$deafult;
                    $value_subtract = $sum-$value_to_subtract;
                    $number_end = $deafult - $value_subtract;
                    if($number_end == $deafult){
                        $number_end = 0;
                    }
                    $number_end = $number_end+1;
                    $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>$verification_digits[$number_end-1],"status"=>"success","message"=>"success"];
                    
                }else{
                    $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"longitud invalida del documento"];
                }
            }else{
                $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"el documento no es un numero"];
            }
        //RUC
        }else if($document_type == 1){
            $digits_initial[10] = 10;
            $digits_initial[20] = 20;
            $digits_initial[15] = 15;
            $digits_initial[16] = 16;
            $digits_initial[17] = 17;
            if(is_numeric($document)){
                $numberArray_two = str_split($document,2);
                //validamos si los dos primeorn numeros del documento son 10,20,15,16,17
                if(isset($numberArray_two[0]) && isset($digits_initial[$numberArray_two[0]])){
                    $numberArray = str_split($document);
                    if(count($numberArray ) == 10){
                        $serial_number = [5,4,3,2,7,6,5,4,3,2];
                    
                        foreach ($numberArray as $key => $value) {
                            $result_mult = $value * $serial_number[$key];
                            $sum = $result_mult+$sum;
                        }
                        $residue = (int)($sum /$deafult);
                        $value_to_subtract = $residue*$deafult;
                        $value_subtract = $sum-$value_to_subtract;
                        $number_end = $deafult - $value_subtract;
                        if($number_end == 10){
                            $number_end = 0;
                        }else if($number_end == 11){
                            $number_end = 1;
                        }
                        $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>$number_end,"status"=>"success","message"=>"success"];
                        
                    }else{
                        $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"longitud invalida del documento"];
                    }
                }else{
                    $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"Numero de documento invalido"];
                }
                
            }else{
                $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"el documento no es un numero"];
            }
        }else{
            $data = ["number"=>$document,"document_type"=>$document_type,"dv"=>null,"status"=>"error","message"=>"Tipo de documento no pertenece a RUC o DNI"];
        }
        
        return $data;

    }

}