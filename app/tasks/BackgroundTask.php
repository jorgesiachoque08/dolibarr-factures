<?php

use Phalcon\Cli\Task;
/* use App\Models\UsersMaria;
use App\Models\Users;
use App\Models\CompaniesUsers; */

class BackgroundTask extends Task
{
    public function mainAction()
    {
       $this->PostCurl($this->params->urlLocal."background",[]);
       echo "ok";
       
        
    }

    public function PostCurl($URL, $data) {
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
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

}