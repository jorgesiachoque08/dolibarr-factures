<?php

use Phalcon\Cli\Task;

class MigrationMembersTask extends Task
{
    public function mainAction()
    {
        try {
            $type_users = [4,5];
            $Users = Users::find([
                'conditions' => 'type_user IN ({type_users:array})',
                'bind'=>['type_users'=>$type_users]])->toArray();
            

            echo count($Users). ' usuarios members a migrar';
            echo "\n";
            foreach ($Users as $key => $value) {
                $value = (object)$value;
                echo 'user = '.$value->id;
                echo "\n";
                $ctrl = true;
                $memberNew = new Members();
                $memberNew->id = $value->id;
                $memberNew->uuid = $this->uuid();
                $memberNew->genre = $value->genre == '' ?3:$value->genre;
                $memberNew->last_name = $value->last_name;
                $memberNew->first_name = $value->first_name;
                $memberNew->address = $value->address;
                $memberNew->country_id = $value->id_country;
                $memberNew->email = $value->email;
                $memberNew->mobile_phone = $value->mobile_phone;
                $memberNew->phone = $value->mobile_phone;
                $memberNew->birthdate = $value->birthdate;
                $memberNew->photo = $value->display_image;
                $memberNew->created_at = $value->created_at;
                $memberNew->created_at_db = $value->created_at;
                $memberNew->status = $value->is_active;
                switch ($value->id_company) {
                    case '1':
                        $memberNew->company_id = 1;
                        $memberNew->organization_id = 1;
                        $memberNew->brand_id = 1;
                        break;
                    case '2':
                        $memberNew->company_id = 1;
                        $memberNew->organization_id = 1;
                        $memberNew->brand_id = 2;
                        break;
                    case '3':
                        $memberNew->company_id = 3;
                        $memberNew->organization_id = 1;
                        $memberNew->brand_id = 3;
                        break;
                    case '7':
                        $memberNew->company_id = 7;
                        $memberNew->organization_id = 1;
                        $memberNew->brand_id = 4;
                        break;
                    default:
                        $ctrl = false;
                        break;
                }
                $memberNew->document_type = $value->document_type;
                $memberNew->document_number = $value->document_number;
                $memberNew->id_tinnova = $value->id_tinnova;
                //valida la compañia
                
                if($ctrl){
                    $result = $memberNew->save();
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

    /**
     * uuid
     *
     * @return void
     */
    function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}