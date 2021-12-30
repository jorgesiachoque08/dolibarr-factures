<?php

use Phalcon\Cli\Task;
/* use App\Models\UsersMaria;
use App\Models\Users;
use App\Models\CompaniesUsers; */

class FacturesTask extends Task
{
    
    public function PostCurlApiRest($URL, $data) {
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
            CURLOPT_POSTFIELDS => json_encode($data,JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
				'Authorization: umb3y37031iem1au7wy35s35xzhx13ba'
            ),
        ));
        $response = curl_exec($curl);
        $curl_errno = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

    public function mainAction()
    {
        // $sql_fact = 'SELECT f.*,s.*,se.fk_document_type_id,f.multicurrency_code as multicurrency_code_facture,fe.is_facture_internacional,se.fk_country_externo,se.fk_country_externo_name  FROM '.MAIN_DB_PREFIX.'facture as f
        // inner join '.MAIN_DB_PREFIX.'societe as s on s.rowid = f.fk_soc 
        // left join '.MAIN_DB_PREFIX.'societe_extrafields as se on se.fk_object = s.rowid 
        // left join '.MAIN_DB_PREFIX.'facture_extrafields as fe on fe.fk_object = f.rowid WHERE f.rowid ='.$this->id;
        try {
            $PHQL = 'SELECT f.*
                FROM BtpFacture AS f WHERE f.rowid not in (26,108) and f.rowid >= 66 and f.rowid <= 101';
            $factura = $this->modelsManager
            ->executeQuery(
                $PHQL
            );
            $pos = 1;
            // $sql_fact_detail = 'SELECT fd.*,p.label as name_product,p.ref as ref_product FROM '.MAIN_DB_PREFIX.'facturedet as fd
			// left join '.MAIN_DB_PREFIX.'product as p on p.rowid = fd.fk_product WHERE fk_facture = '.$this->id;
			foreach ( $factura as $key => $obj) {
                $date = explode(' ',$obj->tms);
			    $items = [];
                foreach ($obj->BtpFacturedet as $key => $value) {
                    if($value->remise_percent > 0){
                        $totalBase = $value->multicurrency_subprice*$value->qty;
					    $items[] = [
						"code"=> empty($value->fk_product)?'N-'.$value->rowid:$value->BtpProduct->ref,
						"name"=> empty($value->fk_product)?  $value->description: $value->BtpProduct->label,
						"quantity"=> $value->qty,
						"quantityPerBox"=> 1,
						"measureCode"=> "94",
						"priceTypeCode"=> "01",
						"unityPrice"=> $value->multicurrency_subprice,
						"total"=> $value->multicurrency_total_ht,
						"discount"=> 0.00,
						"nonCommercialValue"=> 0,
						"description"=> "",
						"mark"=> "",
						"model"=> "",
						"taxes"=> [
							[
							  "type"=> "01",
							  "percentage"=> $value->tva_tx,
							  "taxable"=> $value->multicurrency_total_ht,
							  "tax"=>$value->multicurrency_total_tva
							  ]
							],
							"discounts"=> [ 
								[ 
								"description"=> "Descuento", 
								"percentage"=> $value->remise_percent, 
								"base"=> $totalBase, 
								"total"=> $totalBase*($value->remise_percent/100)
								]
							]
						];
                    }else{
                        $items[] = [
                            "code"=> empty($value->fk_product)?'N-'.$value->rowid:$value->BtpProduct->ref,
                            "name"=> empty($value->fk_product)?  $value->description:$value->BtpProduct->label,
                            "quantity"=> $value->qty,
                            "quantityPerBox"=> 1,
                            "measureCode"=> "94",
                            "priceTypeCode"=> "01",
                            "unityPrice"=> $value->multicurrency_subprice,
                            "total"=> $value->multicurrency_total_ht,
                            "discount"=> 0.00,
                            "nonCommercialValue"=> 0,
                            "description"=> "",
                            "mark"=> "",
                            "model"=> "",
                            "taxes"=> [
                                [
                                  "type"=> "01",
                                  "percentage"=> $value->tva_tx,
                                  "taxable"=> $value->multicurrency_total_ht,
                                  "tax"=>$value->multicurrency_total_tva
                                  ]
                            ]
                                ];
                    }

                }

                if(empty($obj->BtpFactureExtrafields)){
                    $documentType = "FVN";
                    $state = "BOGOTÁ DC";
                    $cityCode = "11001";
                    $city = "BOGOTÁ";
                    $country = "CO";
                    $countryName = "COLOMBIA";
                }else{
                    $documentType = "FVE";
                    $state = "-";
                    $cityCode = "-";
                    $city = "-";
                    $country = $obj->fk_country_externo;
                    $countryName = $obj->fk_country_externo_name;
                }

                $body =[
                    "documents"=> [
                      [
                        "documentHead"=> [
                          "documentType"=> $documentType,
                          "documentNumber"=> $obj->ref,
                          "dateCreated"=> "2021-12-29",
                          "timeCreated"=> date("H:i:s"),
                          "opetarionType"=> "10",
                          "refererOrder"=> "",
                          "dateRefererOrder"=> "",
                          "dueDateDocument"=> "2021-12-29"
                        ],
                        "documentEmisor"=> [
                          "identificationType"=> "31",
                          "identificationNumber"=> "901361991",
                          "emisorName"=> "INCITEDIGITAL SAS",
                          "state"=> "BOGOTÁ DC",
                          "city"=> "BOGOTÁ",
                          "address"=> "Calle 75 22-10",
                          "country"=> "CO",
                          "countryName"=> "COLOMBIA",
                          "cityCode"=> "11001",
                          "legalOrganization"=> 1,
                          "regime"=> "48",
                          "postalCode"=> "110111",
                          "businessActivity"=> "6201",
                          "rutObligations"=> "R-99-PN",
                          "taxesDetails"=> "01",
                          "merchantNumber"=> ""
                        ],
                        "documentReceptor"=> [
                          "identificationType"=> isset($obj->BtpSociete->BtpSocieteExtrafields->fk_document_type_id)?$obj->BtpSociete->BtpSocieteExtrafields->fk_document_type_id:13,
                          "identificationNumber"=> empty($obj->BtpSociete->siren)?$obj->BtpSociete->ape:$obj->BtpSociete->siren,
                          "receptorName"=> $obj->BtpSociete->nom,
                          "legalOrganization"=> 2,
                          "state"=> $state,
                          "cityCode"=>$cityCode,
                          "city"=> $city,
                          "address"=> $obj->BtpSociete->address,
                          "countryName"=> $countryName,
                          "country"=> $country,
                          "regime"=> "48",
                          "email"=> !empty($obj->BtpSociete->email)?$obj->BtpSociete->email:"jorgesiachoque08@gmail.com",
                          "phone"=> !empty($obj->BtpSociete->phone)?$obj->BtpSociete->phone:3013420572,
                          "postalCode"=> "000000",
                          "rutObligations"=> "R-99-PN",
                          "taxesDetails"=> "01",
                          "personAuthIdType"=> empty($obj->BtpSociete->siren)?$obj->BtpSociete->ape:$obj->BtpSociete->siren,
                          "personAuth"=> "13"
                        ],
                        "documentTotal"=> [
                          "subtotal"=>$obj->multicurrency_total_ht,
                          "taxable"=> $obj->multicurrency_total_ht,
                          "taxes"=> $obj->multicurrency_total_tva,
                          "discount"=> 0.00,
                          "charges"=> 0.00,
                          "prePaid"=> 0.00,
                          "totalPay"=> $obj->multicurrency_total_ttc,
                          "coin"=> isset($obj->multicurrency_code)?$obj->multicurrency_code:'COP'
                        ],
                        "documentItems"=> $items,
                        "payReference"=> [
                          "method"=> "1",
                          "mainPay"=> "2",
                          "dueDate"=> "2021-12-29",
                          "payNumber"=> ""
                        ],
                        "aditionalInformation"=> []
                      ]
                    ]
                ];
                $result = json_decode($this->PostCurlApiRest('https://app.estupendo.com.co/api/load/array/data',$body),false);
                $message = null;
                if(!empty($result->response)){
                    if($result->response[0]->code == 500){
                        $message = $result->response[0]->sintaxError;
                        $status = 0;
                    }else{
                        if($result->response[0]->message->result == true && $result->response[0]->message->estado == 2){
                            $message = $result->response[0]->message->message;
                            $status = 1;
                        }else{
                            $status = 3;
                            $message = $result->response[0]->message->message;

                        }
                    }
                }else{
                    $status = 2;
                    $message = "error al consumir la api de factura electronica";
                }
                $BtpLog = new BtpLog();
                $BtpLog->response = json_encode($result);
                $BtpLog->body = json_encode($body);
                $BtpLog->pdf = !empty($result->response[0]->message->pdf)?$result->response[0]->message->pdf :null;
                $BtpLog->cufe = !empty($result->response[0]->message->cufe)?$result->response[0]->message->cufe:null;
                $BtpLog->created_at = date("Y-m-d h:i:s");
                $BtpLog->message = empty($message)?"Problemas al consumir la api": $message;
                $BtpLog->fk_facture = $obj->rowid;
                $BtpLog->status = $status;
                $BtpLog->save();
                echo $pos;
                $pos++;
            }
			
            
        } catch (\Exception $ex) {
            $this->dbMaria->rollback();
            echo "Error en el servidor".$ex->getMessage();
            echo "\n";
        }

    }
}