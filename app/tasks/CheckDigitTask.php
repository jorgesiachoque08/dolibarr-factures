<?php

use Phalcon\Cli\Task;

class CheckDigitTask extends Task
{
    public function mainAction()
    {

        try {
            $UsersClientController = new UsersClientController();
            $usersProfile = UsersProfile::find(
                ['conditions' => 'brand_id = 3 and document_type IN ({document_type:array})',
                'bind'=>['document_type'=>[1,2]]]);
            
            foreach ($usersProfile as $key => $user) {
                $this->dbMaria->begin();
                $ctrl = true;
                $verification = $UsersClientController->verificationCodeDniAndRut($user->document_number,$user->document_type);
                $LogDv = new LogDv();
                $LogDv->user_id = $user->user_id;
                $LogDv->dv_old = $user->dv;
                $LogDv->document_number = $user->document_number;
                $LogDv->document_type = $user->document_type;
                $LogDv->id_tinnova_old = $user->id_tinnova;

                if($verification["status"] == "success"){
                    if($user->dv == null){
                        $LogDv->status_log = $verification["status"];
                        $LogDv->message = "CODIGO ASIGNADO";
                        $LogDv->dv_new = $verification["dv"];
                        $users_old = Users::findFirstById($user->user_id);
                        $users_old->dv = $verification["dv"];
                        if($users_old->save()){
                            $user->dv = $verification["dv"];
                            if(!$user->save()){
                                $ctrl = false;
                            }
                        }else{
                            $ctrl = false;
                        }
            
                        
                    }else if($user->dv != $verification["dv"]){
                        $LogDv->status_log = $verification["status"];
                        $LogDv->message = "CODIGO ERRADO";
                        $LogDv->dv_new = $verification["dv"];
                        $users_old = Users::findFirstById($user->user_id);
                        $users_old->dv = $verification["dv"];
                        if($users_old->save()){
                            $user->dv = $verification["dv"];
                            if(!$user->save()){
                                $ctrl = false;
                            }
                        }else{
                            $ctrl = false;
                        }
                    }else{
                        $LogDv->status_log = $verification["status"];
                        $LogDv->message = "CODIGO CORRECTO";
                        $LogDv->dv_new = $verification["dv"];
                    }
                }else{
                    $LogDv->status_log = $verification["status"];
                    $LogDv->message = $verification["message"];
                    $LogDv->dv_new = $verification["dv"];
                }
                if($ctrl){
                    $result = $LogDv->save();
                    if($result){
                        $this->dbMaria->commit();
                        echo $user->document_number." verificado usuario ".$user->user_id;
                        echo "\n";
                    }else{
                        $this->dbMaria->rollback();
                        echo $user->document_number." error al verificar usuario ".$user->user_id;
                        echo "\n";
                    }
                }else{
                    $this->dbMaria->rollback();
                    echo "Error al guardar en base de datos";
                    echo "\n";
                }
                
                
            }
            
        } catch (\Exception $ex) {
            $this->dbMaria->rollback();
            echo "Error en el servidor".$ex->getMessage();
            echo "\n";
        }

    }

    public function validateDWAction()
    {

        try {
            $baseController = new BaseController();
            $LogsDv = LogDv::find(
                ['conditions' => 'status_log = :status_log:',
                'bind'=>['status_log'=>"success"]]);

            foreach ($LogsDv as $key => $LogDv) {
                $this->dbMaria->begin();
                $ctrl = true;
                //valido por numero de documento en DW
                $document = $LogDv->document_number.$LogDv->dv_new;
                $id_country = 2;
                $company_name = "bodytech";
                $urls = $baseController->UrlBaseDW($id_country,$company_name) . "/personas?DocumentoIdentidad=" . $document;
                $dadas = json_decode($baseController->GetCurl($urls,$id_country,$company_name), false);
                if($dadas){
                    if (!empty($dadas->Personas) && count($dadas->Personas) > 0) {
                        $documenDW = $baseController->getTypeDocument($LogDv->document_type);
                        $userDW = null;
                        //valido por numero de documento y tipo de documento en DW
                        $documenDW = empty($documenDW->id)?101:$documenDW->id;
                        foreach ($dadas->Personas as $Persona) {
                            if($Persona->TipoDocumentoIdentidad->Id == $documenDW){
                                $userDW = $Persona;
                                break;
                            }
                        }
                        
                        if(!empty($userDW)){
                            $id_tinnova_user_dw = $userDW->Id;
                            if($id_tinnova_user_dw == $LogDv->id_tinnova_old){
                                $LogDv->status_id_tinnova = "error";
                                $LogDv->message_id_tinnova ="EL id_tinnova pertenece al usuario";
                            }else{
                                $welcome_pack_status_url = $baseController->UrlBaseDW($id_country,$company_name)  . "/consultas/ejecutar/EstadoCliente?idPersona=" . $LogDv->id_tinnova_old;
                                $welcome_pack_status = json_decode($baseController->GetCurl($welcome_pack_status_url,$id_country,$company_name), false);
                                if($welcome_pack_status){
                                    if (isset($welcome_pack_status->Resultado[0])) {
                                        if (($welcome_pack_status->Resultado[0]->IdServicio > 0 || $welcome_pack_status->Resultado[0]->Empleado == 1)) {
                                            $LogDv->status_id_tinnova = "error";
                                            $LogDv->message_id_tinnova ="El usuario tiene plan activo pero el id_tinnova no pertenece a el";
                                        }else{
                                            $LogDv->id_tinnova_new = $id_tinnova_user_dw;
                                            $LogDv->status_id_tinnova = "success";
                                            $LogDv->message_id_tinnova ="El usuario no tiene plan activo y se actualizo el id_tinova segun su documento";
                                            $users_old = Users::findFirstById($LogDv->user_id);
                                            $UsersProfile = UsersProfile::findFirstByUserId($LogDv->user_id);
                                            $users_old->id_tinnova = $id_tinnova_user_dw;
                                            $UsersProfile->id_tinnova = $id_tinnova_user_dw;
                                            if($users_old->save() && $UsersProfile->save()){
                                                $ctrl = true;
                                            }else{
                                                $ctrl = false;
                                            }
                                        }
                                    }else{
                                        $LogDv->id_tinnova_new = $id_tinnova_user_dw;
                                        $LogDv->status_id_tinnova = "success";
                                        $LogDv->message_id_tinnova ="El usuario no tiene plan activo y se actualizo el id_tinova segun su documento";
                                        $users_old = Users::findFirstById($LogDv->user_id);
                                        $UsersProfile = UsersProfile::findFirstByUserId($LogDv->user_id);
                                        $users_old->id_tinnova = $id_tinnova_user_dw;
                                        $UsersProfile->id_tinnova = $id_tinnova_user_dw;
                                        if($users_old->save() && $UsersProfile->save()){
                                            $ctrl = true;
                                        }else{
                                            $ctrl = false;
                                        }
                                    }
                                }else{
                                    $LogDv->status_id_tinnova = "error_dw";
                                    $LogDv->message_id_tinnova ="error al validar si el id_tinnova tien un plan activo";
                                }
                                
                            }

                        }else{
                            $LogDv->status_id_tinnova = "error";
                            $LogDv->message_id_tinnova ="El ususario no se encuntra en dw, tipo de documento diferente";
                        }
                    }else{
                        $LogDv->status_id_tinnova = "error";
                        $LogDv->message_id_tinnova ="El ususario no se encuntra en dw";
                    }
                }else{
                    $LogDv->status_id_tinnova = "error_dw";
                    $LogDv->message_id_tinnova ="error al buscar el usuario en dw";
                }

                if($ctrl){
                    $result = $LogDv->save();
                    if($result){
                        $this->dbMaria->commit();
                        echo $LogDv->document_number." verificado usuario ".$LogDv->user_id." ".$LogDv->message_id_tinnova;
                        echo "\n";
                    }else{
                        $this->dbMaria->rollback();
                        echo $LogDv->document_number." error al verificar usuario ".$LogDv->user_id;
                        echo "\n";
                    }
                }else{
                    $this->dbMaria->rollback();
                    echo "Error al guardar en base de datos";
                    echo "\n";
                }
                
            }
            
        } catch (\Exception $ex) {
            $this->dbMaria->rollback();
            echo "Error en el servidor".$ex->getMessage();
            echo "\n";
        }

    }

}