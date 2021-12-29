<?php
declare(strict_types=1);

class CitiesController extends BaseController
{

    public function listByCountry($cod_country)
    {      try{
                $PHQL = 'SELECT 
                        c.id,c.name
                    FROM Cities AS c
                    WHERE c.id_country = :id_country: and c.status = 1';
                $cities = $this->modelsManager
                ->executeQuery(
                    $PHQL,
                    ['id_country'=>$cod_country]
                );

                if(count($cities) > 0){
                    $arrayRetorno = $this->response('success','Ok',$cities);
                }else{
                    $arrayRetorno = $this->response('success','No se encontraron resultados',[]);
                }
                $code = 200;
            }catch (\Exception $ex) {
                $code = 500;
                $arrayRetorno = $this->response('error',"Error en el servidor");
            }

            $this->response->setStatusCode($code);
            return $arrayRetorno;
    }

}

