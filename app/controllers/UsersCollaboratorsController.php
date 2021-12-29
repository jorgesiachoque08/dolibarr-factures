<?php
class UsersCollaboratorsController extends BaseController
{
    
    public function adduserMBAction($params) {
        $code = 200;
        try {
            $dominio = explode("@",$params->email);
            if($dominio[1] == "bodytechcorp.com" || $dominio[1] == "athleticgym.com.co"){
                $params->user_name = $dominio[0];
                $PHQL = 'SELECT 
                    oc.uuid_company,oc.uuid_organization,oc.id_company,oc.id_organization,oc.client_id,oc.client_secret
                FROM OauthClients AS oc 
                WHERE oc.client_id = :client_id:';
                $OauthClients = $this->modelsManager
                ->executeQuery(
                    $PHQL,
                    ['client_id'=>$params->client_id]
                )->getFirst();
                if($OauthClients){
                    $ctrl = false;
                    $params->platform = "Web";
                    $params->id_organization = $OauthClients->id_organization;
                    $params->uuid_organization = $OauthClients->uuid_organization;

                    $user = UsersCollaborators::findFirst(
                        ["column"=>'email,organization_id',
                        'conditions' => 'organization_id=:organization_id: and email=:email:',
                        'bind'=>['organization_id'=>$params->id_organization,
                                'email'=>$params->email]]);
                        
                    
                    if(empty($user)){
                            $newUser = $this->AddUserMyBodytechSql($params);
                            if($newUser['status']){
                                $newUser = $newUser['data'];
                                    $arryaUser = array(
                                                "id" =>$newUser->id,
                                                "email" =>$newUser->email,
                                                "organization_id" =>$newUser->organization_id,
                                                "uuid_organization" =>$newUser->uuid_organization,
                                                "brands"=>$newUser->UsersCollaboratorsBrands);
                                    $code = 200;
                                    $arrayRetorno = $this->response('success','registro exitoso',$arryaUser);
                                
            
                            }else{
                                $code = 400;
                                $arrayRetorno = $this->response('error',$newUser['message']);
                            }     
                    }else{
                        $arrayRetorno = $this->response('error','Este correo ya se encuentra asignado a un usuario');
                        
                    }
                
                    
                }else{
                    $code = 400;
                    $arrayRetorno = $this->response('error','Cliente invalido');
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

    public function AddUserMyBodytechSql($user) {
        try {
            //code...
        
            $usernew = new UsersCollaborators();
            $usernew->email = $user->email;
            $usernew->password = $this->CreatePassword((string)$user->password);
            $usernew->created_at = date("Y-m-d H:i:s");
            $usernew->status = 1;
            $usernew->organization_id = $user->id_organization;
            $usernew->uuid_organization = $user->uuid_organization;
            $usernew->user_name = $user->user_name;
            $UsersCollaboratorsBrandsArray = [];
            if (isset($user->brands)) {
                if(is_array($user->brands) && count($user->brands) > 0){
                    foreach($user->brands as $key=>$brandArray){
                        //valido que el campo min_sessions del array sea un numero y que venga en el array agenda_personalized_activities
                        if(empty($brandArray->brand_id) && empty($brandArray->company_id) && empty($brandArray->organization_id) && empty($brandArray->uuid_brand) && empty($brandArray->uuid_company) && empty($brandArray->uuid_organization)){
                            return array('status'=>false,'message'=>'los campos brand_id,company_id,organization_id,uuid_brand,uuid_company,uuid_organization son requeridos en el array','data'=>false);
                            break;
                        }
                        $UsersCollaboratorsBrands = new UsersCollaboratorsBrands();
                        $UsersCollaboratorsBrands->brand_id = $brandArray->brand_id;
                        $UsersCollaboratorsBrands->company_id = $brandArray->company_id;
                        $UsersCollaboratorsBrands->organization_id = $brandArray->organization_id;
                        $UsersCollaboratorsBrands->uuid_brand = $brandArray->uuid_brand;
                        $UsersCollaboratorsBrands->uuid_company = $brandArray->uuid_company;
                        $UsersCollaboratorsBrands->uuid_organization = $brandArray->uuid_organization;
                        $UsersCollaboratorsBrandsArray[] = $UsersCollaboratorsBrands;
                    }
                    $usernew->UsersCollaboratorsBrands = $UsersCollaboratorsBrandsArray;
                }else{
                    return array('status'=>false,'message'=>'El campo brands debe ser un array y no puede estar vacio','data'=>false);
                    
                }
            }
            $result = $usernew->save();
            if($result){
                return array('status'=>true,'message'=>'success','data'=>$usernew);
            }
            //var_dump($usernew->getMessages());
            return array('status'=>false,'message'=>$usernew->getMessages(),'data'=>false);
        } catch (\Exception $ex) {
            return array('status'=>false,'message'=>'error en el servidor al crear el collaborador','data'=>false);
        }
        
        
    }

    public function updateUserMBAction($params,$id_user,$userData) {
        $code = 200;
        try {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['X-Bodytech-Organization'])) {
                $uuid_organization = trim($requestHeaders['X-Bodytech-Organization']);
            } else {
                $uuid_organization = trim($requestHeaders['x-bodytech-organization']);
            }
            
            if(!empty($uuid_organization)){
                $dominio = explode("@",$params->email);
                if($dominio[1] == "bodytechcorp.com" || $dominio[1] == "athleticgym.com.co"){
                    $params->user_name = $dominio[0];
                    $ctrl = false;
                        $userUpdate = UsersCollaborators::findFirstById($id_user);
                        if(!empty($userUpdate)){
                            $user = UsersCollaborators::findFirst(
                                ["column"=>'email,uuid_organization',
                                'conditions' => 'uuid_organization=:uuid_organization: and email=:email: and id<>:id:',
                                'bind'=>['uuid_organization'=>$uuid_organization,
                                        'email'=>$params->email,
                                        'id'=>$id_user]]);
                                
                            
                            if(empty($user)){
                                $user = $this->updateUserMyBodytechSql($params,$userUpdate);
                                if($user['status']){
                                    $user = $user['data'];
                                        $arryaUser = array(
                                                    "id" =>$user->id,
                                                    "email" =>$user->email,
                                                    "brands"=>$user->UsersCollaboratorsBrands
                                                    );
                                        $code = 200;
                                        $arrayRetorno = $this->response('success','registro exitoso',$arryaUser);
                                    
                
                                }else{
                                    $code = 400;
                                    $arrayRetorno = $this->response('error',$user['message']);
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
                    $arrayRetorno = $this->response('error','El email a registrar no es coorporativo');
                }
            }else{
                $code = 400;
                $arrayRetorno = $this->response('error','header X-Bodytech-Organization es requerido');
            }
            
        } catch (\Exception $ex) {
            $arrayRetorno = $this->response('error',$ex->getMessage());
        }
        $this->response->setStatusCode($code);
        return $arrayRetorno;
        
    }

     //typeRegiste 1 mybodytech 2 otras plataformas
     public function updateUserMyBodytechSql($user,$userUpdate) {
         try{
            $userUpdate->email = $user->email;
            if (!empty($user->password)) {
                $userUpdate->password = $this->CreatePassword((string)$user->password);
            }
            $userUpdate->user_name = $user->user_name;
            $this->dbMaria->begin();
            if (isset($user->brands)) {
                
                if(is_array($user->brands)){
                    $UsersCollaboratorsBrands_delete = UsersCollaboratorsBrands::find(
                        [ 'conditions'=>'user_id = :user_id:', 'bind'=>['user_id'=>$userUpdate->id] ]
                    );
                    $UsersCollaboratorsBrands_delete->delete();
                    $UsersCollaboratorsBrandsArray = [];

                    foreach($user->brands as $key=>$brandArray){
                        //valido que el campo min_sessions del array sea un numero y que venga en el array agenda_personalized_activities
                        if(empty($brandArray['brand_id']) && empty($brandArray['company_id']) && empty($brandArray['organization_id']) && empty($brandArray['uuid_brand']) && empty($brandArray['uuid_company']) && empty($brandArray['uuid_organization'])){
                            return array('status'=>false,'message'=>'los campos brand_id,company_id,organization_id,uuid_brand,uuid_company,uuid_organization son requeridos en el array','data'=>false);
                            break;
                        }
                        $UsersCollaboratorsBrands = new UsersCollaboratorsBrands();
                        $UsersCollaboratorsBrands->brand_id = $brandArray['brand_id'];
                        $UsersCollaboratorsBrands->company_id = $brandArray['company_id'];
                        $UsersCollaboratorsBrands->organization_id = $brandArray['organization_id'];
                        $UsersCollaboratorsBrands->uuid_brand = $brandArray['uuid_brand'];
                        $UsersCollaboratorsBrands->uuid_company = $brandArray['uuid_company'];
                        $UsersCollaboratorsBrands->uuid_organization = $brandArray['uuid_organization'];
                        $UsersCollaboratorsBrandsArray[] = $UsersCollaboratorsBrands;
                    }
                    
                        $userUpdate->UsersCollaboratorsBrands = $UsersCollaboratorsBrandsArray;
                }else{
                    return array('status'=>false,'message'=>'El campo brands debe ser un array y no puede estar vacio','data'=>false);
                    
                }
            }

            $result = $userUpdate->save();
            if($result){
                $this->dbMaria->commit();
                return array('status'=>true,'message'=>'success','data'=>$userUpdate);

            }
            $this->dbMaria->rollback();
            return array('status'=>false,'message'=>'error al actualizar los datos','data'=>false);
        } catch (\Exception $ex) {
            //$this->dbMaria->rollback();
            return array('status'=>false,'message'=>'error en el servidor al actualizar el collaborador','data'=>false);
        }
        
        
    }

    public function blockUser($id_user,$userData)
    {
        $code = 200;
        if($id_user != $userData->user_id){
            $user = UsersCollaborators::findFirstById($id_user);
            if (isset($user) && $user->status != 0) {
                $this->dbMaria->begin();
                $user->status = 0;
                $user->update_at = date("Y-m-d H:i:s");;
                $result = $user->update();
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

    //typeRegiste 1 mybodytech 2 otras plataformas
    public function getBrandUser($user_id) {
        try{
            $code = 200;
            $UsersCollaboratorsBrands = UsersCollaboratorsBrands::find(
                [   'column'=>'brand_id,company_id,uuid_brand,uuid_company',
                    'conditions'=>'user_id = :user_id: and status = 1', 'bind'=>['user_id'=>$user_id]]
            );

            if(count($UsersCollaboratorsBrands) > 0){
                $brands = [];
                $companies = [];
                $validateCompany = [];
                foreach ($UsersCollaboratorsBrands as $key => $value) {
                    $brands[] = ["brand_id"=>(int)$value->brand_id];
                    if(!isset($validateCompany[$value->uuid_company])){
                        $validateCompany[$value->uuid_company] = 1;
                        $companies[] = ["company_id"=>(int)$value->company_id];
                    }
                }
                $arrayRetorno = $this->response('success','success',array("brands"=>$brands,"companies"=>$companies));
            }else{
                $arrayRetorno = $this->response('success','no se encontraron resultados',[]);
            }
       } catch (\Exception $ex) {
                $code = 400; 
                $arrayRetorno = $this->response('error','error en el sistema',[]);
       }

        $this->response->setStatusCode($code);
        return $arrayRetorno;
       
       
   }
    
}
