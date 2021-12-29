<?php
class UsersController extends BaseController
{

    public function adduserAction($params) {
        $code = 200;
        try {
                $OauthClients = $this->getRedis($params->client_id."-Oauth");
                
                if(empty($OauthClients)){
                    
                    $PHQL = 'SELECT 
                            oc.uuid_company,oc.uuid_organization,oc.id_company,c.id_country,oc.id_organization,oc.client_id,oc.client_secret,oc.client_secret
                        FROM OauthClients AS oc 
                        LEFT JOIN Companies AS c on c.id = oc.id_company
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
                if($OauthClients->id_company != null){
                    $params->id_company = $OauthClients->id_company;
                    $params->uuid_company = $OauthClients->uuid_company;
                    // si no viene pais se le asigna el de la compañia
                    if (!isset($params->id_country)) {
                        $params->id_country = $OauthClients->id_country;
                    }
                }else{
                    // movil o platafora que no tenga asginada compañia tiene que mandar pais
                    if (!isset($params->id_country)) {
                        $code = 400;
                        $arrayRetorno = $this->response('error','El pais es requerido si tu aplicacion no tienes compañia asignada');
                        $this->response->setStatusCode($code);
                        return $arrayRetorno;
                    }else{
                        $PHQL = 'SELECT 
                                uuid as uuid_company,id as id_company
                            FROM Companies
                            WHERE id_country = :id_country: and id_organization = :id_organization:';
                        $company = $this->modelsManager
                        ->executeQuery(
                            $PHQL,
                            ['id_country'=>$params->id_country,'id_organization'=>$OauthClients->id_organization]
                        )->getFirst();

                        if($company){
                            $params->id_company = $company->id_company;
                            $params->uuid_company = $company->uuid_company;
                        }else{
                            $params->id_company = 7;
                            $params->uuid_company = "2066212fa-6aae-4c02-b28f-5263aerffeww";
                        }

                    }
                }
                $params->password = isset($params->password) ? $params->password:"BENEFICIARIO";
                $params->id_organization = $OauthClients->id_organization;
                $params->uuid_organization = $OauthClients->uuid_organization;
                $params->username = $params->email;
                $params->client_secret = $OauthClients->client_secret;
                $params->grant_type = "password";
                $documenDW = $this->getTypeDocument($params->document_type_id);
                if(isset($documenDW)){
                    $user = Users::findFirst(
                        ["columns"=>'email,id_organization',
                        'conditions' => 'id_organization=:id_organization: and email=:email:',
                        'bind'=>['id_organization'=>$params->id_organization,
                                'email'=>$params->email]]);
            
                    if(empty($user)){
                        $userDocument = Users::findFirst(
                            ["columns"=>'document_number,document_type',
                            'conditions' => 'id_organization=:id_organization: and document_number = :document_number: and document_type =:document_type:',
                            'bind'=>['id_organization'=>$params->id_organization,
                                    'document_number'=>$params->document_number,
                                    'document_type'=>$params->document_type_id]]);
                        if(empty($userDocument)){
                            $this->dbMaria->begin();
                            $newUser = $this->AddUserSql($params);
                            if($newUser){
                                $ctrl = true;
                                //validar datos registraduria
                                if($this->params->Diquality->status){
                                    if($params->document_type_id == 10 || $params->document_type_id == 20 || $params->document_type_id == 50){
                                        $date_time = date('Ymdhis');
                                        $dataDiquality = ["id_proyecto"=> "1",
                                        "ideorg"=> "bodytech",
                                        "fte"=> "web_bodytech",
                                        "tokenUser"=> "jooh738z2bok2hrpk60x9sfzw335mhfamih0jd98dzib",
                                        "identificador_aleatorio"=> $date_time.uniqid(),
                                        "contador_identificador"=> "1",
                                        "confPost"=> "1",
                                        "ideuser"=> "Pagina web",
                                        "tiptra"=> "V",
                                        "tipdoc"=> $params->document_type_id,
                                        "numdoc"=> $params->document_number,
                                        "nom1"=> $params->name,
                                        "ape1"=> $params->lastname];
                                        $validation_register = json_decode($this->PostCurlDiquality($this->params->Diquality->urlDiquality,$dataDiquality));
                                        if(strtolower($validation_register->codigo) == "ok"){
                                            if(isset($validation_register->qide) && $validation_register->qide >= 65){
                                                $this->dbMaria->commit();
                                            }else{
                                                $this->dbMaria->rollback();
                                                $ctrl = false;
                                                $arrayRetorno = $this->response('error','Datos del usuario no validos');
                                            }
                                        }else{
                                            $this->dbMaria->commit();
                                        }
                                        $arraylog = array(
                                            "url" => $this->params->Diquality->urlDiquality,
                                            "data" => $dataDiquality,
                                            "metodo" => 'POST',
                                        );
                                        $logs = new LogErrors();
                                        $logs->log = json_encode($arraylog);
                                        $logs->result = json_encode($validation_register);
                                        $logs->percentage = empty($validation_register->qide)?0:$validation_register->qide;
                                        $logs->save();
                                    }else{
                                        $this->dbMaria->commit();
                                    }
                                    
                                }else{
                                    $this->dbMaria->commit();
                                }
                                //hago login
                                if ($ctrl) {
                                    $this->registerUserElasticSearch($newUser->id);
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
                                }
                                
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

    public function adduserDWAction($params) {
        $code = 200;
        try {
            $dominio = explode("@",$params->email);
            if($dominio[1] == "bodytechcorp.com" || $dominio[1] == "athleticgym.com.co"){
                $params->user_name = $dominio[0];
                    if (!isset($params->id_country)) {
                        $code = 400;
                        $arrayRetorno = $this->response('error','El pais es requerido si tu aplicacion no tienes compañia asignada');
                        $this->response->setStatusCode($code);
                        return $arrayRetorno;
                    }else{
                        $PHQL = 'SELECT 
                                c.uuid as uuid_company,c.id as id_company,c.id_organization,o.uuid as uuid_organization
                            FROM Companies as c
                            INNER JOIN Organizations as o on o.id = c.id_organization
                            WHERE c.id = :id_company:';
                        $company = $this->modelsManager
                        ->executeQuery(
                            $PHQL,
                            ['id_company'=>$params->company_id]
                        )->getFirst();

                        if($company){
                            $params->id_company = $company->id_company;
                            $params->uuid_company = $company->uuid_company;
                            $params->id_organization = $company->id_organization;
                            $params->uuid_organization = $company->uuid_organization;
                        }else{
                            $code = 400;
                            $arrayRetorno = $this->response('error','Compañia no validad');
                            $this->response->setStatusCode($code);
                            return $arrayRetorno;
                        }

                    }
                    $ctrl = false;
                    $params->platform = "Web";
                    /* $params->username = $params->email;
                    $params->client_secret = $OauthClients->client_secret;
                    $params->grant_type = "password"; */
                    
                    $user = UsersInternal::findFirst(
                        ["columns"=>'email,id_organization',
                        'conditions' => 'email=:email:',
                        'bind'=>['email'=>$params->email]]);
                        
                    
                    if(empty($user)){
                        $userDocument = UsersInternal::findFirst(
                            ["columns"=>'document_number,document_type',
                            'conditions' => 'document_number = :document_number: and document_type =:document_type:',
                            'bind'=>['document_number'=>$params->document_number,
                                    'document_type'=>$params->document_type_id]]);
                        if(empty($userDocument)){
                            $newUser = $this->AddUserMyBodytechSql($params);
                            if($newUser){
                                    $arryaUser = array(
                                                "id" =>$newUser->id,
                                                "first_name" =>$newUser->first_name,
                                                "last_name" =>$newUser->last_name,
                                                "email" =>$newUser->email,
                                                "address" =>$newUser->address,
                                                "display_image" =>$newUser->display_image,
                                                "genre" =>$newUser->genre,
                                                "document_number" =>$newUser->document_number,
                                                "document_type" =>$newUser->document_type,
                                                "city_id" =>$newUser->city_id,
                                                "birthdate" =>$newUser->birthdate,
                                                "mobile_phone" =>$newUser->mobile_phone,
                                                "id_country" =>$newUser->id_country,
                                                "id_organization" =>$newUser->id_organization,
                                                "id_company" =>$newUser->id_company,
                                                "id_external" =>$newUser->id,
                                                "uuid_organization" =>$newUser->id,
                                                "uuid_company"=>$newUser->id);
                                    $code = 200;
                                    $arrayRetorno = $this->response('success','registro exitoso',$arryaUser);
                                
            
                            }else{
                                $code = 400;$code = 400;
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
                $arrayRetorno = $this->response('error','El email a registrar no es coorporativo');
            }
        } catch (\Exception $ex) {
            $arrayRetorno = $this->response('error',$ex->getMessage());
        }
        $this->response->setStatusCode($code);
        return $arrayRetorno;
        
    }

    public function AddUserSql($user) {
        
        $usernew = new Users();
        $usernew->first_name = $user->name;
        $usernew->last_name = $user->lastname;
        $usernew->email = $user->email;
        $usernew->password = $this->CreatePassword((string)$user->password);
        $usernew->created_at = date("Y-m-d H:i:s");
        $usernew->last_login = date("Y-m-d H:i:s");//
        $usernew->is_superuser = 0;///
        $usernew->is_staff = 0;///
        $usernew->is_active = 1;
        $usernew->document_number = $user->document_number;
        $usernew->document_type = $user->document_type_id;
        $usernew->auth_token_tinnova = json_encode(array());//$user->auth_token_tinnova;
        $usernew->id_tinnova = isset($user->id_tinnova) ? $user->id_tinnova : null;//$user->id_tinnova;///
        $usernew->personal_timetable = json_encode(array());//$user->personal_timetable;
        $usernew->birthdate = $user->birthdate;
        $usernew->mobile_phone = $user->phone;
        $usernew->email_verified = false;
        $usernew->id_country = $user->id_country;
        $usernew->id_company = $user->id_company;
        $usernew->id_organization = $user->id_organization;
        $usernew->uuid_organization = $user->uuid_organization;
        $usernew->platform = $user->platform;
        $usernew->uuid_company = $user->uuid_company;
        $usernew->terms_data = $user->terms_data;
        $usernew->dv = isset($user->dv)?$user->dv:null;
        $usernew->type_user = !empty($user->type_user)?  $user->type_user: 0;
        $usernew->address = '';
        $usernew->display_image = null;
        $usernew->validate_cron = isset($user->validate_cron)?$user->validate_cron:0;
        $usernew->genre = !empty($user->genre)?$user->genre:3;///
        $usernew->city_id = isset($user->city_id) ? $user->city_id : $this->getCity($user->id_country);
        

        $result = $usernew->save();
        if($result){
            $usernew->id_external = $usernew->id;
            $result = $usernew->save();
            if($result){
                
                return $usernew;
            }

        }
        //var_dump($usernew->getMessages());
        return false;

        
        
    }

     //typeRegiste 1 mybodytech 2 otras plataformas
     public function AddUserMyBodytechSql($user) {

        $usernew = new UsersInternal();
        $usernew->first_name = $user->name;
        $usernew->last_name = $user->lastname;
        $usernew->email = $user->email;
        $usernew->password = $this->CreatePassword((string)$user->password);
        $usernew->created_at = date("Y-m-d H:i:s");
        $usernew->last_login = date("Y-m-d H:i:s");//
        $usernew->is_superuser = 0;///
        $usernew->is_staff = 0;///
        $usernew->is_active = 1;
        $usernew->document_number = $user->document_number;
        $usernew->document_type = $user->document_type_id;
        $usernew->auth_token_tinnova = json_encode(array());//$user->auth_token_tinnova;
        $usernew->id_tinnova = null;//$user->id_tinnova;///
        $usernew->personal_timetable = json_encode(array());//$user->personal_timetable;
        $usernew->birthdate = $user->birthdate;
        $usernew->mobile_phone = $user->phone;
        $usernew->email_verified = false;
        $usernew->id_country = $user->id_country;
        $usernew->id_company = $user->id_company;
        $usernew->id_organization = $user->id_organization;
        $usernew->uuid_organization = $user->uuid_organization;
        $usernew->platform = $user->platform;
        $usernew->uuid_company = $user->uuid_company;
        $usernew->user_name = $user->user_name;

            $usernew->address = $user->address;
            $usernew->display_image = isset($user->photo)? $user->photo:null;
            $usernew->genre = $user->genre;///
            $usernew->city_id = $user->city_id;
        

        $result = $usernew->save();
        if($result){
            $usernew->id_external = $usernew->id;
            $result = $usernew->save();
            if($result){
                
                return $usernew;
            }

        }
        //var_dump($usernew->getMessages());
        return false;

        
        
    }

    public function AddUserCompanySql($user) {
        $CompaniesUsersNew = new CompaniesUsers();
        $CompaniesUsersNew->id_company = $user->id_company;
        $CompaniesUsersNew->id_user = $user->id;
        $CompaniesUsersNew->id_tinnova = $user->id_tinnova;
        $CompaniesUsersNew->id_organization = $user->id_organization;
        $CompaniesUsersNew->id_external = $user->id;
        $result = $CompaniesUsersNew->save();
        if($result){
            return $CompaniesUsersNew;

        }
            return false;
    }

    public function RegisterUserDw($params,$document_type )
    {
        $urlnew = $this->UrlBaseDW($params->id_country) . "/personas";
        $lastname = explode(" ", $params->last_name);
        $arrayData = array(
            "Persona" => array(
                "Nombre" => $params->name,
                "Apellido1" => isset($lastname[0]) ? $lastname[0] : " ",
                "Apellido2" => isset($lastname[1]) ? $lastname[1] : " ",
                "IdTipoDocumentoIdentidad" => $document_type,
                "DocumentoIdentidad" => $params->document_number,
                "Email" => $params->email,
                "TelefonoMovil"=>$params->phone,
                "FechaNacimiento"=>$params->birthdate
            )
        );
        $ndata = json_decode($this->PostCurl($urlnew, $arrayData,$params->id_country,$company_name), false);
        return $ndata->IdPersona;
    }

    public function ValidateUserDw($id_user)
    {   
        $user_welcomepack = Users::findFirstById($id_user);
        if(isset($user_welcomepack->id_organization) && $user_welcomepack->id_organization == 2){
            $company_name = "athletic";
        }else{
            $company_name = "bodytech";
        }
        if(isset($this->params->DeporWin[$user_welcomepack->id_country][$company_name]->status) && $this->params->DeporWin[$user_welcomepack->id_country][$company_name]->status){
            if($user_welcomepack){
                //valido por numero de documento en DW
                $urls = $this->UrlBaseDW($user_welcomepack->id_country,$company_name) . "/personas?DocumentoIdentidad=" . $user_welcomepack->document_number;
                $dadas = json_decode($this->GetCurl($urls,$user_welcomepack->id_country,$company_name), false);
                $ctrl = false;
                $id_tinnova = null;
    
                if (empty($dadas->Personas)) {
                    //si no se encontro usuario en dw por numero de documento,lo valido por email
                    $urls = $this->UrlBaseDW($user_welcomepack->id_country,$company_name) . "/personas?Email=" . $user_welcomepack->email;
                    $dadas = json_decode($this->GetCurl($urls,$user_welcomepack->id_country,$company_name), false);
                    if(count($dadas->Personas) > 0){
                        $id_tinnova = $dadas->Personas[0]->Id;
                        $ctrl = true;
                    }
                } else {
                    $documenDW = $this->getTypeDocument($user_welcomepack->document_type);
                    //valido por numero de documento y tipo de documento en DW
                    $document_type = $documenDW->id;
                    foreach ($dadas->Personas as $Persona) {
                        if($Persona->TipoDocumentoIdentidad->Id == $document_type && $Persona->DocumentoIdentidad == $user_welcomepack->document_number){
                            $id_tinnova = $Persona->Id;
                            $ctrl = true;
                            break;
                        }
                    }

                    if(!$ctrl){
                        //si no se encontro usuario en dw por numero de documento,lo valido por email
                        $urls = $this->UrlBaseDW($user_welcomepack->id_country,$company_name) . "/personas?Email=" . $user_welcomepack->email;
                        $dadas = json_decode($this->GetCurl($urls,$user_welcomepack->id_country,$company_name), false);
                        if(count($dadas->Personas) > 0){
                            $id_tinnova = $dadas->Personas[0]->Id;
                            $ctrl = true;
                        }
                    }
                }
    
                // si no se encontro usuario ni por documento o email,lo registro
                /* if(!$ctrl){
                    //$id_tinnova = $this->RegisterUserDw($user_welcomepack,$documenDW["id"]);
                } */
                // se actualiza el id_tinnova del usuario
                if(isset($id_tinnova)){
                    $this->dbMaria->begin();
                    $user_welcomepack->id_tinnova = $id_tinnova;
                    $result = $user_welcomepack->save();
                    if($result){
                        $this->dbMaria->commit();
                    }else{
                        $this->dbMaria->rollback();
                    }
    
                }
            }
        }
        
    }

    public function welcomeEmail($id_user)
    {
        $user_welcomepack = Users::findFirstById($id_user);
        if ($user_welcomepack) {
            if(isset($user_welcomepack->id_organization) && $user_welcomepack->id_organization == 2){
                $company_name = "athletic";
            }else{
                $company_name = "bodytech";
            }
            if(isset($user_welcomepack->id_company)){
                $template_company_email = TemplateCompanyEmail::findFirst(
                    ['conditions' => 'id_company=:id_company: and type="welcome"',
                    'bind'=>['id_company'=>$user_welcomepack->id_company]]);
            }else{
                $template_company_email = TemplateCompanyEmail::findFirst(
                    ['conditions' => 'id_company= is null and id_organization = :id_organization:  and type="welcome"',
                    'bind'=>['id_organization'=>$user_welcomepack->id_organization]]);
            }
            
            $data_template = array("email" => $user_welcomepack->email,"data_template"=>json_decode($template_company_email->data_template)); //data email 
            $template_html = $this->view->render('emails/email_welcome'.$template_company_email->name_file, $data_template); //html template
            $subject = 'Bienvenido a tu club'; //subject
            $send_to = array("email" => $user_welcomepack->email, "name" => $user_welcomepack->first_name . ' ' . $user_welcomepack->last_name); //send to 
            $send_email = $this->send_email($template_html, $subject, $send_to,null,$company_name);
        }
        
    }

    public function background()
    {
        $dataUsers = Users::find(
            [
            'conditions' => 'validate_cron = :validate_cron:',
            'bind'=>['validate_cron'=>0]]);
 
        if (count($dataUsers) > 0) {
            // envio elasticSearch
            foreach ($dataUsers as $user_welcomepack) {
                # code...
                
                $query = array(
                    "constant_score"=>array(
                        "filter"=>array(
                            "term"=>array(
                                "id"=>$user_welcomepack->id
                            )
                        )
                    )
                );

                $search_count = array(
                    "size"=> 0,
                    "track_total_hits"=> true,
                    "query"=> $query
                );
        
                $result_elastic = json_decode( $this->_searchElasticSearch($this->params->ElasticSearch->indexOauth.'/_search', $search_count) );
                
                if($result_elastic->hits->total->value == 0){
                    $arryaUser = [["id" => $user_welcomepack->id,
                    "user_id" => $user_welcomepack->id,
                    "user_id_test" => (int)$user_welcomepack->id,
                    "first_name" => $user_welcomepack->first_name,
                    "last_name" => $user_welcomepack->last_name,
                    "email" => $user_welcomepack->email,
                    "is_superuser" => $user_welcomepack->is_superuser,
                    "is_staff" => $user_welcomepack->is_staff,
                    "is_active" => $user_welcomepack->is_active,
                    "address" => $user_welcomepack->address,
                    "display_image" => $user_welcomepack->display_image,
                    "genre" => $user_welcomepack->genre,
                    "document_number" => $user_welcomepack->document_number,
                    "document_type" => $user_welcomepack->document_type,
                    "city_id" => $user_welcomepack->city_id,
                    "id_tinnova" => $user_welcomepack->id_tinnova,
                    "birthdate" => $user_welcomepack->birthdate,
                    "mobile_phone" => $user_welcomepack->mobile_phone,
                    "type_user" => $user_welcomepack->type_user,
                    "document_type_name" => $user_welcomepack->DocumentType->name,
                    "external_code" => $user_welcomepack->DocumentType->external_code,
                    "city_name" => $user_welcomepack->Cities->name,
                    "country_name" => $user_welcomepack->Countries->name,
                    "id_country" => $user_welcomepack->id_country,
                    "id_organization" => $user_welcomepack->id_organization,
                    "id_company" => $user_welcomepack->id_company,
                    "id_external" => $user_welcomepack->id_external,
                    "uuid_organization" => $user_welcomepack->uuid_organization,
                    "uuid_company" => $user_welcomepack->uuid_company,
                    "dv"=>$user_welcomepack->dv]];

                    $this->_bulkElasticSearch($this->params->ElasticSearch->indexOauth,$arryaUser,1);
                }

                if(isset($user_welcomepack->id_organization) && $user_welcomepack->id_organization == 2){
                    $company_name = "athletic";
                }else{
                    $company_name = "bodytech";
                }

                // envio correo bienvenida
                if($user_welcomepack->msn == 1){
                    if(isset($user_welcomepack->id_company)){
                        $template_company_email = TemplateCompanyEmail::findFirst(
                            ['conditions' => 'id_company=:id_company: and type="welcome"',
                            'bind'=>['id_company'=>$user_welcomepack->id_company]]);
                    }else{
                        $template_company_email = TemplateCompanyEmail::findFirst(
                            ['conditions' => 'id_company= is null and id_organization = :id_organization:  and type="welcome"',
                            'bind'=>['id_organization'=>$user_welcomepack->id_organization]]);
                    }
                    
                    $data_template = array("email" => $user_welcomepack->email,"data_template"=>json_decode($template_company_email->data_template)); //data email 
                    $template_html = $this->view->render('emails/email_welcome'.$template_company_email->name_file, $data_template); //html template
                    $subject = 'Bienvenido a tu club'; //subject
                    $send_to = array("email" => $user_welcomepack->email, "name" => $user_welcomepack->first_name . ' ' . $user_welcomepack->last_name); //send to 
                    $send_email = $this->send_email($template_html, $subject, $send_to,null,$company_name);
                }

                // validaccion a dw
                if(empty($user_welcomepack->id_tinnova)){
                    if(isset($this->params->DeporWin[$user_welcomepack->id_country][$company_name]->status) && $this->params->DeporWin[$user_welcomepack->id_country][$company_name]->status){
                    
                        //valido por numero de documento en DW
                        $urls = $this->UrlBaseDW($user_welcomepack->id_country,$company_name) . "/personas?DocumentoIdentidad=" . $user_welcomepack->document_number;
                        $dadas = json_decode($this->GetCurl($urls,$user_welcomepack->id_country,$company_name), false);
                        $ctrl = false;
                        $id_tinnova = null;
            
                        if (!empty($dadas->Personas) && count($dadas->Personas) > 0) {
                            
                        
                            $documenDW = $this->getTypeDocument($user_welcomepack->document_type);
                            //valido por numero de documento y tipo de documento en DW
                            $document_type = $documenDW->id;
                            foreach ($dadas->Personas as $Persona) {
                                if($Persona->TipoDocumentoIdentidad->Id == $document_type && $Persona->DocumentoIdentidad == $user_welcomepack->document_number){
                                    $id_tinnova = $Persona->Id;
                                    $ctrl = true;
                                    break;
                                }
                            }
                        }
                        // si no se encontro usuario ni por documento o email,lo registro
                        /* if(!$ctrl){
                            //$id_tinnova = $this->RegisterUserDw($user_welcomepack,$documenDW["id"]);
                        } */
                        // se actualiza el id_tinnova del usuario
                        if(isset($id_tinnova)){
                            $user_welcomepack->id_tinnova = $id_tinnova;
            
                        } 
                    
                    }
                }
                
                $user_welcomepack->validate_cron = 1;
                $user_welcomepack->save(); 
            }
        }
    }

    public function myProfile($id_user)
    {
        $user_welcomepack = Users::findFirstById($id_user);
        if ($user_welcomepack) {
            $arryaUser = ["id" => $user_welcomepack->id,
            "first_name" => $user_welcomepack->first_name,
            "last_name" => $user_welcomepack->last_name,
            "email" => $user_welcomepack->email,
            "is_superuser" => $user_welcomepack->is_superuser,
            "is_staff" => $user_welcomepack->is_staff,
            "is_active" => $user_welcomepack->is_active,
            "address" => $user_welcomepack->address,
            "display_image" => $user_welcomepack->display_image,
            "genre" => $user_welcomepack->genre,
            "document_number" => $user_welcomepack->document_number,
            "document_type" => $user_welcomepack->document_type,
            "city_id" => $user_welcomepack->city_id,
            "id_tinnova" => $user_welcomepack->id_tinnova,
            "birthdate" => $user_welcomepack->birthdate,
            "mobile_phone" => $user_welcomepack->mobile_phone,
            "type_user" => $user_welcomepack->type_user,
            "document_type_name" => $user_welcomepack->DocumentType->name,
            "external_code" => $user_welcomepack->DocumentType->external_code,
            "city_name" => $user_welcomepack->Cities->name,
            "country_name" => $user_welcomepack->Countries->name,
            "id_country" => $user_welcomepack->id_country,
            "id_organization" => $user_welcomepack->id_organization,
            "id_company" => $user_welcomepack->id_company,
            "id_external" => $user_welcomepack->id_external,
            "uuid_organization" => $user_welcomepack->uuid_organization,
            "uuid_company" => $user_welcomepack->uuid_company,
            "dv"=>$user_welcomepack->dv];
            $arrayRetorno = $this->response('success','success',$arryaUser);
        }else{
            $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
        }

    
        $this->response->setStatusCode(200);
        return $arrayRetorno;
        
    }

    public function registerUserElasticSearch($id_user)
    {

        $PHQL = 'SELECT 
            u.id,
            u.id as user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.is_superuser,
            u.is_staff,
            u.is_active,
            u.address,
            u.display_image,
            u.genre,
            u.document_number,
            u.document_type,
            u.city_id,
            u.id_tinnova,
            u.birthdate,
            u.mobile_phone,
            u.type_user,
            d.name as document_type_name,
            d.external_code,
            c.name as city_name,
            cou.name as country_name,
            u.id_country,
            u.id_organization,
            u.id_company,
            u.id_external,
            u.uuid_organization,
            u.uuid_company,
            u.dv
        FROM Users AS u
        LEFT JOIN Countries AS cou on cou.id = u.id_country
        LEFT JOIN Cities AS c on c.id = u.city_id
        LEFT JOIN DocumentType AS d on d.id = u.document_type
        WHERE u.id = :id:';
        $users = $this->modelsManager
        ->executeQuery(
            $PHQL,
            ['id'=>$id_user]
        )->getFirst();
            $users->user_id_test = (int)$users->id;
            $arryaUser = [$users];
            return $this->_bulkElasticSearch($this->params->ElasticSearch->indexOauth,$arryaUser,1);
            
    }

    public function blockUser($id_user,$userData)
    {
        $code = 200;
        if($id_user != $userData->user_id){
            $user = UsersInternal::findFirstById($id_user);
            if (isset($user) && $user->is_active != 0) {
                $this->dbMaria->begin();
                $user->is_active = 0;
                $result = $user->save();
                if($result){
                    $PHQL_ACCESS = 'DELETE FROM OauthAccessTokens WHERE user_id =:user_id:';
                    $access_token = $this->modelsManager->executeQuery(
                        $PHQL_ACCESS,
                        ['user_id'=>$id_user]
                    );
                    $PHQL_REFRESH = 'DELETE FROM OauthRefreshTokens WHERE user_id =:user_id:';
                    $refresh_token = $this->modelsManager->executeQuery(
                        $PHQL_REFRESH,
                        ['user_id'=>$id_user]
                    );
                    if($access_token && $refresh_token ){
                        $this->dbMaria->commit();
                        $arrayRetorno = $this->response('success','El usuarios ha sido bloqueado');
                    }else {
                        $this->dbMaria->rollback();
                        $code = 400;
                        $arrayRetorno = $this->response('error','Error al eliminar los access token y refresh');
                    }
                }else{
                    $this->dbMaria->rollback();
                    $code = 400;
                    $arrayRetorno = $this->response('error','Error al actualizar el estado del usuario');
                }
                
            }else{
                $arrayRetorno = $this->response('success','No se encontraron resultados');
            }
        }else{
            $code = 400;
            $arrayRetorno = $this->response('error','No se puede bloquear este usuario');
        }
        

    
        $this->response->setStatusCode($code);
        return $arrayRetorno;
        
    }

    public function updateUserDWAction($params,$id_user,$userData) {
        $code = 200;
        try {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['X-Bodytech-Organization'])) {
                $uuid_organization = trim($requestHeaders['X-Bodytech-Organization']);
            } else {
                $uuid_organization = trim($requestHeaders['x-bodytech-organization']);
            }
            $dominio = explode("@",$params->email);
            if($dominio[1] == "bodytechcorp.com" || $dominio[1] == "athleticgym.com.co"){
                $params->user_name = $dominio[0];
                $ctrl = false;
                if($id_user != $userData->user_id){
                    $userUpdate = UsersInternal::findFirstById($id_user);
                    if(!empty($userUpdate)){
                        $user = UsersInternal::findFirst(
                            ["columns"=>'email,uuid_organization',
                            'conditions' => 'uuid_organization=:uuid_organization: and email=:email: and id<>:id:',
                            'bind'=>['uuid_organization'=>$uuid_organization,
                                    'email'=>$params->email,
                                    'id'=>$id_user]]);
                            
                        
                        if(empty($user)){
                            $userDocument = UsersInternal::findFirst(
                                ["columns"=>'document_number,document_type',
                                'conditions' => 'uuid_organization=:uuid_organization: and document_number = :document_number: and document_type =:document_type: and id<>:id:',
                                'bind'=>['uuid_organization'=>$uuid_organization,
                                        'document_number'=>$params->document_number,
                                        'document_type'=>$params->document_type_id,
                                        'id'=>$id_user]]);
                            if(empty($userDocument)){
                                $user = $this->updateUserMyBodytechSql($params,$userUpdate);
                                if($user){
                                        $arryaUser = array(
                                                    "id" =>$user->id,
                                                    "first_name" =>$user->first_name,
                                                    "last_name" =>$user->last_name,
                                                    "email" =>$user->email,
                                                    "address" =>$user->address,
                                                    "display_image" =>$user->display_image,
                                                    "genre" =>$user->genre,
                                                    "document_number" =>$user->document_number,
                                                    "document_type" =>$user->document_type,
                                                    "city_id" =>$user->city_id,
                                                    "birthdate" =>$user->birthdate,
                                                    "mobile_phone" =>$user->mobile_phone,
                                                    "id_country" =>$user->id_country,
                                                    "id_company" =>$user->id_company,
                                                    "id_organization" =>$user->id_organization);
                                        $code = 200;
                                        $arrayRetorno = $this->response('success','registro exitoso',$arryaUser);
                                }else{
                                    $code = 400;
                                    $arrayRetorno = $this->response('error','Problemas al actualizar el usuario');
                                } 
                                
                            }else{
                                $arrayRetorno = $this->response('error','Este documento ya se encuentra asignado a un usuario');
                            }
                
                        }else{
                            $arrayRetorno = $this->response('error','Este correo ya se encuentra asignado a un usuario');
                            
                        }
                    }else{
                        $code = 400;
                        $arrayRetorno = $this->response('error','El Usuario actualizar no existe');
                    }
                        
                }else{
                    $code = 400;
                    $arrayRetorno = $this->response('error','No se puede actualizar este usuario admin');
                }
            }else{
                $code = 400;
                $arrayRetorno = $this->response('error','El email a registrar no es coorporativo');
            }
        } catch (\Exception $ex) {
            $arrayRetorno = $this->response('error',$ex->getMessage());
        }
        $this->response->setStatusCode($code);
        return $arrayRetorno;
        
    }

     //typeRegiste 1 mybodytech 2 otras plataformas
     public function updateUserMyBodytechSql($user,$userUpdate) {
        $userUpdate->first_name = $user->name;
        $userUpdate->last_name = $user->lastname;
        $userUpdate->email = $user->email;
        if (!empty($user->password)) {
            $userUpdate->password = $this->CreatePassword((string)$user->password);
        }

        $userUpdate->document_number = $user->document_number;
        $userUpdate->document_type = $user->document_type_id;
        $userUpdate->birthdate = $user->birthdate;
        $userUpdate->mobile_phone = $user->phone;
        $userUpdate->id_country = $user->id_country;
        $userUpdate->user_name = $user->user_name;
        if(isset($user->company_id)){
            if($user->company_id != $userUpdate->id_company){
                $PHQL = 'SELECT 
                    c.uuid as uuid_company,c.id as id_company,c.id_organization,o.uuid as uuid_organization
                    FROM Companies as c
                    INNER JOIN Organizations as o on o.id = c.id_organization
                    WHERE c.id = :id_company:';
                $company = $this->modelsManager
                ->executeQuery(
                    $PHQL,
                    ['id_company'=>$user->company_id]
                )->getFirst();

                if($company){
                    $userUpdate->id_company = $company->id_company;
                    $userUpdate->uuid_company = $company->uuid_company;
                    $userUpdate->id_organization = $company->id_organization;
                    $userUpdate->uuid_organization = $company->uuid_organization;
                }
            }
            
        }
        
        $userUpdate->address = $user->address;
        $userUpdate->display_image = isset($user->photo)? $user->photo:null;
        $userUpdate->genre = $user->genre;///
        $userUpdate->city_id = $user->city_id;
        $result = $userUpdate->save();

        if($result){
            return $userUpdate;

        }
        return false;

        
        
    }

    public function myProfileIdTinnova($id_tinnova,$uuid_company)
    {
        $user_welcomepack = Users::findFirst(array(
            "conditions" => "id_tinnova=:id_tinnova: and uuid_company = :uuid_company:",
            "bind" => ['id_tinnova' => $id_tinnova,'uuid_company'=>$uuid_company]
        ));
        if ($user_welcomepack) {
            $arryaUser = ["id" => $user_welcomepack->id,
            "first_name" => $user_welcomepack->first_name,
            "last_name" => $user_welcomepack->last_name,
            "email" => $user_welcomepack->email,
            "is_superuser" => $user_welcomepack->is_superuser,
            "is_staff" => $user_welcomepack->is_staff,
            "is_active" => $user_welcomepack->is_active,
            "address" => $user_welcomepack->address,
            "display_image" => $user_welcomepack->display_image,
            "genre" => $user_welcomepack->genre,
            "document_number" => $user_welcomepack->document_number,
            "document_type" => $user_welcomepack->document_type,
            "city_id" => $user_welcomepack->city_id,
            "id_tinnova" => $user_welcomepack->id_tinnova,
            "birthdate" => $user_welcomepack->birthdate,
            "mobile_phone" => $user_welcomepack->mobile_phone,
            "type_user" => $user_welcomepack->type_user,
            "document_type_name" => $user_welcomepack->DocumentType->name,
            "external_code" => $user_welcomepack->DocumentType->external_code,
            "city_name" => $user_welcomepack->Cities->name,
            "country_name" => $user_welcomepack->Countries->name,
            "id_country" => $user_welcomepack->id_country,
            "id_organization" => $user_welcomepack->id_organization,
            "id_company" => $user_welcomepack->id_company,
            "id_external" => $user_welcomepack->id_external,
            "uuid_organization" => $user_welcomepack->uuid_organization,
            "uuid_company" => $user_welcomepack->uuid_company,
            "dv"=>$user_welcomepack->dv];
            $arrayRetorno = $this->response('success','success',$arryaUser);
        }else{
            $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
        }

    
        $this->response->setStatusCode(200);
        return $arrayRetorno;
        
    }

    public function adjustmentFirstNamen($limit)
    {
        $user_welcomepack = Users::find(array(
            "conditions" => "first_name like CONCAT('%',last_name) and first_name not like '%prueba%'",
            'limit'=>$limit
        ));


        $userModified = [];
        foreach ($user_welcomepack as $key => $value) {
            //$n = explode($value->last_name,$value->first_name);
            //$first_name = $value->first_name;
            //$value->first_name = empty($n[0])?$value->first_name:$n[0];
            //$user = $value->save();
            //$userModified[] = ['id'=>$value->id,'first_name'=>$first_name,'last_name'=>$value->last_name,'first_name_modified'=>trim($n[0]),'status_update'=>$user];
        }
        
        $arrayRetorno = $this->response('success',count($userModified).' Usuariios',$userModified);
    
        $this->response->setStatusCode(200);
        return $arrayRetorno;
        
    }

    public function createdUserOfDw($document,$document_type,$client_id,$id_user_collaborator){
        try {
            //$client = $this->getRedis($client_id);
            // if(empty($client)){
            //     $client = OauthClients::findFirstByclient_id($client_id);
            //     $this->setRedis($client_id,$client ,18000);
            // }
            $UsersInternal = UsersInternal::findFirstByid($id_user_collaborator);
            if($UsersInternal){
                $userDocument = Users::findFirst(
                    ["columns"=>'id,first_name,last_name,document_number,document_type',
                    'conditions' => 'id_organization=:id_organization: and document_number = :document_number: and document_type =:document_type:',
                    'bind'=>['id_organization'=>$UsersInternal->id_organization,
                            'document_number'=>$document,
                            'document_type'=>$document_type]]);
    
                if(empty($userDocument)){
                    if(isset($UsersInternal->id_organization) && $UsersInternal->id_organization == 2){
                        $company_name = "athletic";
                    }else{
                        $company_name = "bodytech";
                    }
    
                    $id_country = $UsersInternal->Companies->id_country;
    
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
                                if($Persona->TipoDocumentoIdentidad->Id == $documenDW){
                                    $userDW = $Persona;
                                    $ctrl = true;
                                    break;
                                }
                            }
    
                            if($ctrl){
                                $password = rand(1111111111,9999999999);
                                $email = isset($userDW->Email)?$userDW->Email:$document.'@gmail.com';
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
                                    "id_organization" => $UsersInternal->id_organization,
                                    "id_company" => $UsersInternal->id_company,
                                    "uuid_organization" => $UsersInternal->uuid_organization,
                                    "uuid_company" => $UsersInternal->uuid_company,
                                    "platform"=>"DW",
                                    "terms_data"=>true,
                                    "validate_cron"=>1
                                ];
    
                                $userEmail = Users::findFirst(
                                    ["columns"=>'email,id_organization',
                                    'conditions' => 'id_organization=:id_organization: and email=:email:',
                                    'bind'=>['id_organization'=>$UsersInternal->id_organization,
                                            'email'=>$email]]);
                
                                if(empty($userEmail)){
                                    $newUser = $this->AddUserSql($userDB);
                                    if($newUser){
                                        $this->registerUserElasticSearch($newUser->id);
                                        $arrayRetorno = array('status'=>'success','message'=>'success','data'=>['user_id'=>$newUser->id,'first_name'=>$newUser->first_name,'last_name'=>$newUser->last_name]);
                                    }else{
                                        $arrayRetorno = array('status'=>'error','message'=>["message"=> "error al registrar el usario de dw"]);
                                    }
                                }else{
                                    $arrayRetorno = array('status'=>'error','message'=>["message"=> "ya existe un usuario con este correo de dw"]);
                                }
                            }else{
                                $arrayRetorno = array('status'=>'error','message'=>["message"=> "No se encontro el usuario en dw"]);
                            }
    
                        } else {
                            $arrayRetorno = array('status'=>'error','message'=>["message"=> "No se encontro el usuario en dw"]);
                        }
                    
                    }else{
                        $arrayRetorno = array('status'=>'error','message'=>["message"=> "No existe dw para esta compañia"]);
                    }
                }else{
                    $arrayRetorno = array('status'=>'success','message'=>'success','data'=>['user_id'=>$userDocument->id,'first_name'=>$userDocument->first_name,'last_name'=>$userDocument->last_name]);
                }
                
                
            }else{
                $arrayRetorno = array('status'=>'error','message'=>["message"=> "Client id no encontrado"]);
            }
        } catch (\Exception $ex) {
            $arrayRetorno = array('status'=>'error','message'=>["message"=> "error en el servidor"]);
        }
        return  (object)$arrayRetorno;
    }


    public function getUserDw($document,$document_type,$client_id,$id_user_collaborator)
    {

        $arrayRetorno = $this->createdUserOfDw($document,$document_type,$client_id,$id_user_collaborator);
        $this->response->setStatusCode(200);
        return $arrayRetorno;

    }

}