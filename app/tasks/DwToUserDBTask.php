<?php

use Phalcon\Cli\Task;
/* use App\Models\UsersMaria;
use App\Models\Users;
use App\Models\CompaniesUsers; */

class DwToUserDBTask extends Task
{
    public function mainAction($limit,$num_id_persona)
    {
        try {
            $UserDw = UserDw::find([
                'conditions' => 'id_persona>=:id_persona:',
                'bind'=>['id_persona'=>$num_id_persona],
                'order'=>'id_persona asc',
                'limit'=>$limit]);
            
            $UsersController = new UsersController();

            echo count($UserDw). ' usuarios a migrar';
            echo "\n";
            foreach ($UserDw as $key => $value) {
                echo 'user = '.$value->dni;
                echo "\n";
                //valida la compañia
                switch ($value->id_company) {
                    case '1':
                        $id_organization = '1';
                        $id_country = '1';
                        $uuid_company = '006629fa-6aae-4c02-b28f-5264387199bb';
                        $uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        break;
                    case '2':
                        $id_organization = '2';
                        $id_country = '1';
                        $uuid_company = '006622fa-6aae-4c02-b28f-5263248719bb';
                        $uuid_organization = '7c022047-bfc9-11eb-b063-062b475b052b';
                        break;
                    case '3':
                        $id_organization = '1';
                        $id_country = '2';
                        $uuid_company = '006613fa-6aae-4c02-b28f-52634242719bb';
                        $uuid_organization = '7134d120-bfc9-11eb-b063-062b475b052b';
                        break;
                    
                    default:
                        $id_organization = null;
                        $id_country = null;
                        $uuid_company = null;
                        $uuid_organization = null;
                        break;
                }
                if (!empty($id_organization) || !empty($id_country)) {
                    //validar el tipo documento
                    switch ($value->tipo_documento) {
                        case '9':
                            $document_type = 1;
                            break;
                        case '8':
                            $document_type = 2;
                            break;
                        case '99':
                            if ($id_country == 2) {
                                $document_type = 3;
                            }elseif ($id_country == 1) {
                                $document_type = 30;
                            }
                            break;
                        case '105':
                            if ($id_country == 2) {
                                $document_type = 4;
                            }elseif ($id_country == 1) {
                                // para colombia el 105 es 103
                                $document_type = 20;
                            }
                            break;
                        case '101':
                            $document_type = 10;
                            break;
                        case '103':
                            $document_type = 20;
                            break;
                        case '102':
                            $document_type = 50;
                            break;
                        default:
                            $document_type = null;
                            break;
                    }

                    if(!empty($document_type)){
                        $userDocument = Users::findFirst(
                            ["columns"=>'id,first_name,last_name,document_number,document_type',
                            'conditions' => 'id_organization=:id_organization: and document_number = :document_number: and document_type =:document_type:',
                            'bind'=>['id_organization'=>$id_organization,
                                    'document_number'=>$value->dni,
                                    'document_type'=>$document_type]]);
            
                        if(empty($userDocument)){
                            $password = rand(1111111111,9999999999);
                            if($value->email == '' || empty($value->email)){
                                $email = $value->dni.'@bodytech-client.com';
                            }else{
                                $email = $value->email;
                            }

                            $apellido1 = isset($value->apellido1)?$value->apellido1:'';
                            $apellido2 = isset($value->apellido2)?$value->apellido2:'';
                            $userDB = (object)[
                                "name" => $value->nombre,
                                "lastname" => $apellido1." ".$apellido2,
                                "email" => $email,
                                "password" => $password,
                                "document_number" => $value->dni,
                                "document_type_id" => $document_type,
                                "id_tinnova" => $value->id_persona,
                                "birthdate" => date($value->nacimiento),
                                "phone" => isset($value->telefono)?(int)$value->telefono:null,
                                "id_country" => $id_country,
                                "id_organization" => $id_organization,
                                "id_company" => $value->id_company,
                                "uuid_organization" => $uuid_organization,
                                "uuid_company" => $uuid_company,
                                "platform"=>"DW",
                                "terms_data"=>true,
                                "genre"=>isset($value->genero)?$value->genero:3,
                                "validate_cron"=>1
                            ];
            
                            $userEmail = Users::findFirst(
                                ["columns"=>'email,id_organization',
                                'conditions' => 'id_organization=:id_organization: and email=:email:',
                                'bind'=>['id_organization'=>$id_organization,
                                        'email'=>$email]]);
            
                            if(empty($userEmail)){
                                $newUser = $UsersController->AddUserSql($userDB);
                                if($newUser){
                                    $UsersController->registerUserElasticSearch($newUser->id);
                                    echo "success";
                                    echo "\n";
                                }else{
                                    echo "error al registrar el usario";
                                    echo "\n";
                                }
                            }else{
                                echo "ya existe un usuario con este correo de dw";
                                echo "\n";
                            }
                            
                        }else{
                            echo "El usuario ya existe en Oauth";
                            echo "\n";
                        }
                    }else{
                        echo "tipo de documento no encontrado ".$value->tipo_documento;
                        echo "\n";
                    }
                }else{
                    echo "compañia no validad";
                    echo "\n";
                } 

            }

        } catch (\Exception $ex) {
            echo "error en el servidor";
            echo "\n";
        }
        
    }

}