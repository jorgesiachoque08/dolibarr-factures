<?php

use Phalcon\Cli\Task;

class MigrationUsersToUsersClientTask extends Task
{
    public function mainAction($idMax,$idMin)
    {
        try {
            $Users = Users::find([
                'conditions' => 'id>=id_min and id<:id_max:',
                'bind'=>['id_min'=>$idMin,'id_max'=>$idMax]]);
            

            echo count($Users). ' usuarios a migrar';
            echo "\n";
            foreach ($Users as $key => $value) {
                $value = (object)$value;
                echo 'user = '.$value->id;
                echo "\n";

                $usernew = new UsersClient();
                $usernew->id = $value->id;
                $user_name = explode("@",$value->email);
                $usernew->email = $value->email;
                $usernew->user_name = isset($value->user_name)?$value->user_name:$user_name[0];
                $usernew->password = $value->password;
                $usernew->created_at = $value->created_at;
                $usernew->status = $value->is_active;
                $usernew->mobile_phone = $value->mobile_phone;
                $usernew->last_login = $value->last_login;

                $UsersProfile = new UsersProfile();
                $UsersProfile->city_id = $value->city_id;
                $UsersProfile->first_name = $value->first_name;
                $UsersProfile->last_name = $value->last_name;
                $UsersProfile->terms_data = $value->terms_data;
                $UsersProfile->dv = $value->dv;
                $UsersProfile->address = $value->address;
                $UsersProfile->display_image = $value->display_image;
                $UsersProfile->genre = $value->genre == '' ?3:$value->genre;
                $UsersProfile->platform = $value->platform;
                $UsersProfile->id_country = $value->id_country;
                $UsersProfile->document_number = $value->document_number;
                $UsersProfile->document_type = $value->document_type;
                $UsersProfile->id_tinnova = $value->id_tinnova;
                $UsersProfile->birthdate = $value->birthdate;
                $UsersProfile->is_superuser = $value->is_superuser;///
                $UsersProfile->is_staff = $value->is_staff;///
                $UsersProfile->type_user = $value->type_user;
                $UsersProfile->validate_cron = $value->validate_cron;
                $UsersProfile->email = $value->email;
                $UsersProfile->user_name = $usernew->user_name;
                $UsersProfile->mobile_phone = $value->mobile_phone;
                $UsersProfile->is_active = $value->is_active;
                $ctrl = true;
                //valida la compañia
                switch ($value->id_company) {
                    case '1':
                        $usernew->company_id = 1;
                        $usernew->organization_id = 1;
                        $usernew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        $usernew->brand_id = 1;
                        $usernew->uuid_brand = '013b01ed-3761-11ec-a258-0e56c583c695';
                        $usernew->uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                        $UsersProfile->brand_id = 1;
                        break;
                    case '2':
                        $usernew->company_id = 1;
                        $usernew->organization_id = 1;
                        $usernew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        $usernew->brand_id = 2;
                        $usernew->uuid_brand = '014fb5db-3761-11ec-a258-0e56c583c695';
                        $usernew->uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                        $UsersProfile->brand_id = 2;
                        break;
                    case '3':
                        $usernew->company_id = 3;
                        $usernew->organization_id = 1;
                        $usernew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        $usernew->brand_id = 3;
                        $usernew->uuid_brand = 'e52b7d97-3760-11ec-a258-0e56c583c695';
                        $usernew->uuid_company = '006613fa-6aae-4c02-b28f-52634242719bb';
                        $UsersProfile->brand_id = 3;
                        break;
                    case '7':
                        $usernew->company_id = 7;
                        $usernew->organization_id = 1;
                        $usernew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        $usernew->brand_id = 4;
                        $usernew->uuid_brand = '55d4eb8e-3761-11ec-a258-0e56c583c695';
                        $usernew->uuid_company = '2066212fa-6aae-4c02-b28f-5263aerffeww';
                        $UsersProfile->brand_id = 4;
                        break;
                    default:
                        $ctrl = false;
                        break;
                }
                if($ctrl){
                    $usernew->UsersProfile = $UsersProfile;
                    $result = $usernew->save();
                    if($result){
                        echo 'true';
                        echo "\n";
                    }else{
                        var_dump($usernew->getMessages());
                        echo "\n";
                    }

                }else{
                    echo 'compania no establecida';
                    echo "\n";
                }


            }

        } catch (\Exception $ex) {
            echo "error en el servidor";
            echo "\n";
        }
        
    }

}