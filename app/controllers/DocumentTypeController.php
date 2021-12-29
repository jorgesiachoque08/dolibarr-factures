<?php
declare(strict_types=1);

class DocumentTypeController extends BaseController
{

    public function listTypeDocument($id_country)
    {      try{
                $PHQL = 'SELECT 
                        d.id,d.name,d.external_code
                    FROM DocumentType AS d
                    WHERE d.status = 1 and d.id_country = :id_country:';
                $documentType = $this->modelsManager
                ->executeQuery(
                    $PHQL,
                    ["id_country"=>$id_country]
                );

                if(count($documentType) > 0){
                    $arrayRetorno = $this->response('success','Ok',$documentType);
                }else{
                    $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
                }
                $code = 200;
            }catch (\Exception $ex) {
                $code = 500;
                $arrayRetorno = $this->response('error',$ex->getMessage());
            }

            $this->response->setStatusCode($code);
            return $arrayRetorno;
    }

}

