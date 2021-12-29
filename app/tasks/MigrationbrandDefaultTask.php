<?php

use Phalcon\Cli\Task;

class MigrationbrandDefaultTask extends Task
{
    public function mainAction()
    {
        try {
            $Users = UsersMybodytech::find();
            

            echo count($Users). ' usuarios a agregar brand';
            echo "\n";
            foreach ($Users as $key => $value) {
                $value = (object)$value;
                echo 'user = '.$value->id;
                echo "\n";

                $UsersCollaboratorsBrandsNew = new UsersCollaboratorsBrands();
                $UsersCollaboratorsBrandsNew->user_id = $value->id;
                $dominio = explode('@',$value->email);
                if($dominio[1] == "bodytechcorp.com"){
                    if($value->id_country == 2){
                        $UsersCollaboratorsBrandsNew->brand_id = 3;
                        $UsersCollaboratorsBrandsNew->company_id = 3;
                        $UsersCollaboratorsBrandsNew->organization_id = 1;
                        $UsersCollaboratorsBrandsNew->uuid_brand = 'e52b7d97-3760-11ec-a258-0e56c583c695';
                        $UsersCollaboratorsBrandsNew->uuid_company = '006613fa-6aae-4c02-b28f-52634242719bb';
                        $UsersCollaboratorsBrandsNew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                    }else{
                        $UsersCollaboratorsBrandsNew->brand_id = 1;
                        $UsersCollaboratorsBrandsNew->company_id = 1;
                        $UsersCollaboratorsBrandsNew->organization_id = 1;
                        $UsersCollaboratorsBrandsNew->uuid_brand = '013b01ed-3761-11ec-a258-0e56c583c695';
                        $UsersCollaboratorsBrandsNew->uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                        $UsersCollaboratorsBrandsNew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                    }
                }elseif($dominio[1] == "athleticgym.com.co"){
                    $UsersCollaboratorsBrandsNew->brand_id = 2;
                    $UsersCollaboratorsBrandsNew->company_id = 1;
                    $UsersCollaboratorsBrandsNew->organization_id = 1;
                    $UsersCollaboratorsBrandsNew->uuid_brand = '014fb5db-3761-11ec-a258-0e56c583c695';
                    $UsersCollaboratorsBrandsNew->uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                    $UsersCollaboratorsBrandsNew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                }else{
                    $UsersCollaboratorsBrandsNew->brand_id = 1;
                    $UsersCollaboratorsBrandsNew->company_id = 1;
                    $UsersCollaboratorsBrandsNew->organization_id = 1;
                    $UsersCollaboratorsBrandsNew->uuid_brand = '013b01ed-3761-11ec-a258-0e56c583c695';
                    $UsersCollaboratorsBrandsNew->uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                    $UsersCollaboratorsBrandsNew->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                }
                
                if($UsersCollaboratorsBrandsNew->save()){
                    echo 'true';
                    echo "\n";
                }else{
                    echo 'false';
                    echo "\n";
                }


            }

        } catch (\Exception $ex) {
            echo "error en el servidor";
            echo "\n";
        }
        
    }

    public function usersCollabotarAction()
    {
        try {
            $Users = UsersInternal::find();
            

            echo count($Users). ' usuarios collaborator';
            echo "\n";
            foreach ($Users as $key => $value) {
                $value = (object)$value;
                echo 'user = '.$value->id;
                echo "\n";
         
                $UsersCollaborators = new UsersCollaborators();
                $UsersCollaborators->id = $value->id;
                $UsersCollaborators->user_name = $value->user_name;
                $UsersCollaborators->email = $value->email;
                $UsersCollaborators->password = $value->password;
                $UsersCollaborators->organization_id = 1;
                $UsersCollaborators->uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                $UsersCollaborators->last_login = $value->last_login;
                $UsersCollaborators->created_at_db = $value->created_at;
                $UsersCollaborators->created_at = $value->created_at;
                $UsersCollaborators->update_at_db = null;
                $UsersCollaborators->update_at = null;
                $UsersCollaborators->status = $value->is_active;
                
                
                if($UsersCollaborators->save()){
                    echo 'true';
                    echo "\n";
                }else{
                    echo 'false';
                    echo "\n";
                }


            }

        } catch (\Exception $ex) {
            echo "error en el servidor";
            echo "\n";
        }
        
    }
}