<?php

use Phalcon\Cli\Task;
/* use App\Models\UsersMaria;
use App\Models\Users;
use App\Models\CompaniesUsers; */

class MigrationDBMtoElasticTask extends Task
{
    public function mainAction()
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
            order by u.id ASC';
            $users = $this->modelsManager
            ->executeQuery(
                $PHQL
            );
            
        $ctrl = false;
        if(count($users) > 0){
            $users_all = $users;
            $delete_result = $this->_deleteElasticSearch("users_all");
            $delete_result = json_decode($delete_result);
            if(isset($delete_result->acknowledged) && $delete_result->acknowledged == 1){
                $this->_bulkElasticSearch("users_all", $users_all);
                //var_dump($users);
                echo count($users_all);
            }else{
                echo "error al eliminar el index";
            }
        }else{
            echo "no se encontraron registro";
        }
        
    }   

    /* _bulk ElasticSearch
     *
     * @param  mixed $index
     * @param  mixed $array
     * @param  mixed $limit
     * @return void
     */
    public function _bulkElasticSearch($index, $array){
        $limit = 1000;
        $con = 0;
        $data = '';
        $bundles = array();
        foreach($array as $info){
            $result= json_encode($info);
            $data = $data.'{"index":{"_index":"'.$index.'"}}'."\n".$result."\n";
            if(count($array) < $limit || $con == $limit){
                $bundles[] = $data;
                $data='';
                $con=0;
            }else{
                $con++;
            }
        }
        if($data!=''){
            $bundles[] = $data;
        }
        $this->bulkFunction($bundles);
    }


    /* bulkFunction curl
     *
     * @param  mixed $array
     * @return void
     */
    public function bulkFunction($array){
        foreach ($array as $a) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->params->ElasticSearch->UrlBase . '/_bulk?pretty',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$a,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            ));
            $response = json_decode(curl_exec($curl));
            $curl_errno = curl_getinfo($curl);
            curl_close($curl);
            if ($curl_errno["http_code"] != 200) {
                var_dump($curl_errno);
                echo 'error';
                die;
            }
        }
    }

    public function _deleteElasticSearch($index){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->params->ElasticSearch->UrlBase . $index,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS =>'',
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

}