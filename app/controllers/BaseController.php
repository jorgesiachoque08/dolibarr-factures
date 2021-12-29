<?php
declare(strict_types=1);
use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;

require APP_PATH.'/config/sentry.php';

class BaseController extends Controller
{
    public function validatePassword($password, $hash) {
        $nhash = explode("$", $hash);
        if(empty($nhash[2]) || empty($nhash[1]) || empty($nhash[3]) ){
            return false;
        }
        $hash_algo = "sha256";
        $salt = $nhash[2];
        $iterations = (int)$nhash[1];
        $length = 0;
        $raw_output = true;
        $hashs = hash_pbkdf2($hash_algo, (string)$password, $salt, $iterations, $length, $raw_output);
        if (base64_encode($hashs) == $nhash[3]) {
            return true;
        }
        return false;
    }

    public function registerLastLogin($user) {
        try {
            $users = Users::findFirstById($user['id']);
            $users->last_login = date('Y-m-d H:i:s');
            return $users->save();

        } catch (\Exception $ex) {
            return false;
        }
    }

    public function registerLastLoginMyBodytech($user) {
        try {
            $users = UsersInternal::findFirstById($user['id']);
            $users->last_login = date('Y-m-d H:i:s');
            return $users->save();

        } catch (\Exception $ex) {
            return false;
        }
    }

    public function validatetUserOrganizate($username,$id_organization) {
        try {
            $user = Users::findFirst(
                [
                'conditions' => 'id_organization=:id_organization: and email=:email:',
                'bind'=>['id_organization'=>$id_organization,
                        'email'=>$username]]);
            return $user;

        } catch (\Exception $ex) {
            return false;
        }
    }

    public function response($status,$message,$data = null){
        if(isset($data)){
            $response = array("status"=>$status,"message"=>$message,"data"=>$data); 
        }else{
            $response = array("status"=>$status,"message"=>$message); 
        }

        return $response;
        
    }

    public function send_email($template, $subject, $send_to, $attaches = null,$nameOrganization) {
        if ($this->params->sendmail) {
            try {
                // Create the Transport
                $transport = (new Swift_SmtpTransport($this->params->sendmail->host, $this->params->sendmail->port))
                        ->setUsername($this->params->sendmail->username)
                        ->setPassword($this->params->sendmail->password);

                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);

                // Create a message
                if ($attaches) {
                    $message = (new Swift_Message($subject))
                            ->setFrom([$this->params->sendmail->from[$nameOrganization]->email => $this->params->sendmail->from[$nameOrganization]->name])
                            ->setTo([$send_to['email'] => $send_to['name']])
                            ->setBody($template, 'text/html');
                    foreach ($attaches as $attach) {
                        $message->attach(Swift_Attachment::fromPath($attach));
                    }
                } else {
                    $message = (new Swift_Message($subject))
                            ->setFrom([$this->params->sendmail->from[$nameOrganization]->email => $this->params->sendmail->from[$nameOrganization]->name])
                            ->setTo([$send_to['email'] => $send_to['name']])
                            ->setBody($template, 'text/html');
                }

                return $mailer->send($message);
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    public function CreatePassword($password) {
        $hash_algo = "sha256";
        $salt = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 12);
        $iterations = 216000;
        $length = 0;
        $raw_output = true;
        $hashs = hash_pbkdf2($hash_algo, $password, $salt, $iterations, $length, $raw_output);
        return "pbkdf2_" . $hash_algo . "$" . $iterations . "$" . $salt . "$" . base64_encode($hashs);
    }

    public function getTypeDocument($typeDocument) {
        try {

            $documentType = $this->getRedis($typeDocument."-doumentType");
                
            if(empty($documentType)){
                $PHQL = 'SELECT 
                        d.id_dw_type_document as id, d.name as type
                    FROM DocumentType AS d
                    WHERE d.status = 1 and d.id = :id:';

                $documentType = $this->modelsManager
                ->executeQuery(
                    $PHQL,
                    ["id"=>$typeDocument]
                )->getFirst();
                // se guarda en cache
                $this->setRedis($typeDocument."-doumentType",$documentType ,18000);
            }

            if($documentType){
                return $documentType;
            }else{
                return null;
            }

        } catch (\Exception $ex) {
            return false;
        }
    }

    public function getCity($id_country) {
        $id_city = null;
        if($id_country == 1){
            $id_city = 1;
        }else if($id_country == 2){
            $id_city = 27;
        }else if($id_country == 3){
            $id_city = 26;
        }
        
        return $id_city;
    }

    public function UrlBaseDW($id_country,$company_name) {
        return $this->params->DeporWin[$id_country][$company_name]->UrlBase . rand($this->params->DeporWin[$id_country][$company_name]->PortMin, $this->params->DeporWin[$id_country][$company_name]->PortMax);
    }

    
    public function GetCurl($URL,$id_country,$company_name) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-TokenLoginDesarrollador: ' . $this->GetTokenDeporWin($id_country,$company_name)
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);
        if ($curl_errno["http_code"] != 200) {
            $arraylog = array(
                "url" => $URL,
                "data" => '',
                "metodo" => 'GET',
            );
            $logs = new LogErrors();
            $logs->log = json_encode($arraylog);
            $logs->result = json_encode($response);
            $logs->save();
            return null;
        }
        curl_close($curl);
        return $response;
    }

    public function GetTokenDeporWin($id_country,$company_name) {
        $tokenDW = $this->getRedis("tokenDW-".$id_country."-".$company_name);
        if(isset($tokenDW)){
            return $tokenDW;
        }else{
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->UrlBaseDW($id_country,$company_name) . '/autenticacion/tokenlogindesarrollador',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 1,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $this->params->DeporWin[$id_country][$company_name]->credentials,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                ),
            ));
            $response = json_decode(curl_exec($curl));
            $curl_errno = curl_getinfo($curl);
            curl_close($curl);
            if ($curl_errno["http_code"] == 200) {
                $this->setRedis("tokenDW-".$id_country."-".$company_name,$response->Token);
                return $response->Token;
            }else{
                $arraylog = array(
                    "url" => $this->UrlBaseDW($id_country,$company_name) . '/autenticacion/tokenlogindesarrollador',
                    "data" => '',
                    "metodo" => 'POST',
                );
                $logs = new LogErrors();
                $logs->log = json_encode($arraylog);
                $logs->result = json_encode($response);
                $logs->save();
                return null;
            }
        }
    }

    public function PostCurl($URL, $data,$id_country,$company_name,$log_type='') {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-TokenLoginDesarrollador: ' . $this->GetTokenDeporWin($id_country,$company_name)
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);

        if( ($log_type != '') && ( $log_type == "DPW_open_transaction" || $log_type == "DPW_contract" || $log_type == "DPW_close_transaction" )  ){
            $arraylog = array(
                "url" => $URL,
                "data" => $data,
                "metodo" => 'POST',
            );
            $logs = new LogErrors();
            $logs->log = json_encode($arraylog);
            $logs->result = $curl_errno["http_code"] . " " . ($response);
            //$logs->type = $log_type;
            $logs->save();
        }else{
            if ($curl_errno["http_code"] != 200) {
                $arraylog = array(
                    "url" => $URL,
                    "data" => $data,
                    "metodo" => 'POST',
                );
                $logs = new LogErrors();
                $logs->log = json_encode($arraylog);
                $logs->result = $curl_errno["http_code"] . " " . ($response);
                //$logs->type = $log_type;
                $logs->save();
            }
        }
        curl_close($curl);
        return $response;
    }

    public function GetCurlNoReturn($URL) { //Curl que no espera respuesta 
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT => 1,
        ));
        $response = curl_exec($curl);
        //$curl_errno = curl_getinfo($curl);
        curl_close($curl);
    }

    public function PostCurlInterno($URL, $data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_HEADER =>1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

    public function _bulkElasticSearch($index, $array,$p_limit = null){

        $limit = isset($p_limit) ? $p_limit :$this->params->limit_bulk_elastic;
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
    
        return $this->bulkFunction($bundles);
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
            $response = curl_exec($curl);
            $curl_errno = curl_getinfo($curl);
            curl_close($curl);
            if ($curl_errno["http_code"] != 200) {
                $arraylog = array(
                    "url" => $this->params->ElasticSearch->UrlBase . '/_bulk?pretty',
                    "data" => $a,
                    "metodo" => 'POST',
                );
                $logs = new LogErrors();
                $logs->log = json_encode($arraylog);
                $logs->result = json_encode($response);
                $logs->save();
                return false;
            }else{
                return true;
            }
        }
    }

    public function PostCurlDiquality($URL, $data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

     /**
     * _search ElasticSearch
     *
     * @param  mixed $index
     * @param  mixed $search
     * @return void
     */
    public function _searchElasticSearch($index, $search){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->params->ElasticSearch->UrlBase . $index,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => (int) $this->params->timeCurl,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>json_encode($search),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

     /**
     * getRedis
     *
     * @param  mixed $key
     */
    public function getRedis($key){

        if($this->config->redis->status){
            $redis = $this->cache->get($key);
        }else{
            $redis = null;
        }
        
        return $redis;

    }

    /**
     * setRedis
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $time
     */
    public function setRedis($key,$value,$time = null){
        
        if($this->config->redis->status){
            if(empty($time)){
                $this->cache->set($key,$value);
            }else{
                $this->cache->set($key,$value ,$time);
            }
            
        }

    }
    
    public function _bulkElasticSearchDos($index, $array,$p_limit = null){

        $limit = isset($p_limit) ? $p_limit :$this->params->limit_bulk_elastic;
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
    
        return $this->bulkFunctionDos($bundles);
    }

    public function bulkFunctionDos($array){
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
            $response = curl_exec($curl);
            $curl_errno = curl_getinfo($curl);
            curl_close($curl);
            if ($curl_errno["http_code"] != 200) {
                $arraylog = array(
                    "url" => $this->params->ElasticSearch->UrlBase . '/_bulk?pretty',
                    "data" => $a,
                    "metodo" => 'POST',
                );
                $logs = new LogErrors();
                $logs->log = json_encode($arraylog);
                $logs->result = json_encode($response);
                $logs->save();
            }else{

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
        
        public function readcvs()
        {
            $message = array(array("message"=> "Obejtivos, Grupo muscular, Patologias, Contraindicaciones, Elementos de entrenamiento, Niveles de entrenamiento, Lugares de entrenamiento y Etapas de entrenamientos son requeridos"));
            $request = new Request();
                $count = 1;
                $a = [];
                foreach ($request->getUploadedFiles() as $file) {
                    if (($fichero = fopen($file->getTempName(), "r")) !== FALSE) {
                        while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== FALSE) {
                            if($datos[0] != 'DOCUMENTO'){
                                $a[] = array("documento"=>$datos[0],'correo'=>$datos[1]);
                            }
                        }
                    }
                }
                $delete_result = $this->_deleteElasticSearch("hardbody");
                $this->_bulkElasticSearchDos('hardbody',$a,5000);
                return $a;
        }
}
