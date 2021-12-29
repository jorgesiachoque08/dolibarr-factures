<?php

use Phalcon\Cli\Task;
/* use App\Models\UsersMaria;
use App\Models\Users;
use App\Models\CompaniesUsers; */

class MigrationDBMtoDBMTask extends Task
{
    public function mainAction($id_organization,$id_company,$id_country,$uuid_organization,$uuid_company)
    {
        
        $users = UsersMaria::find(array(
            'order' => 'id ASC'
        ));
        $ctrl = false;
        if(count($users) > 0){
            //$this->dbMaria->begin();
            foreach ($users as $user) {
                $usernew = new Users();
                //$usernew->id = $user->id;

                if(isset($user->created_at)){
                    $fecha = explode(".",$user->created_at);
                }else{
                    $fecha = null;
                }
                if(isset($user->last_login)){
                    $fecha_last = explode(".",$user->last_login);
                }else{
                    $fecha_last = null;
                }
                $usernew->first_name = $user->first_name;
                $usernew->last_name = $user->last_name;
                $usernew->email = $user->email;
                $usernew->password = $user->password;
                $usernew->created_at = isset($fecha)?$fecha[0]:null;
                $usernew->last_login = isset($fecha_last)?$fecha_last[0]:null;//
                $usernew->is_superuser = $user->is_superuser;///
                $usernew->is_staff = $user->is_staff;///
                $usernew->is_active = $user->is_active;
                $usernew->address = $user->address;
                $usernew->display_image = $user->display_image;
                $usernew->genre = $user->genre;///
                $usernew->document_number = $user->document_number;
                $usernew->document_type = $user->document_type;
                $usernew->city_id = $user->city_id;
                $usernew->auth_token_tinnova = $user->auth_token_tinnova;//$user->auth_token_tinnova;
                $usernew->id_tinnova = $user->id_tinnova;//$user->id_tinnova;///
                $usernew->personal_timetable = $user->personal_timetable;//$user->personal_timetable;
                $usernew->birthdate = $user->birthdate;
                $usernew->mobile_phone = $user->mobile_phone;
                $usernew->email_verified = $user->email_verified;
                $usernew->type_user = $user->type_user;
                $usernew->terms_data = $user->terms_data;
                $usernew->id_country = $id_country;
                $usernew->id_company = $id_company;
                $usernew->id_organization = $id_organization;
                $usernew->id_external = $user->id;
                $usernew->uuid_organization = $uuid_organization;
                $usernew->uuid_company = $uuid_company;
                $usernew->platform = "Web";
                $result = $usernew->save();
                
            }

            if ($ctrl === false) {
                //$this->dbMaria->commit();
                $mensaje = "ok";

            }else{
                //$this->dbMaria->rollback();
                $mensaje = "error";
            }
            echo $mensaje;
        }else{
            echo "no se encontraron mensajes";
        }
        
    }

}