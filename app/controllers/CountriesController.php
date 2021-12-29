<?php
declare(strict_types=1);

class CountriesController extends BaseController
{

    public function listCoutries()
    {      try{
                $PHQL = 'SELECT 
                        c.id,c.name
                    FROM Countries AS c
                    WHERE c.status = 1';
                $countries = $this->modelsManager
                ->executeQuery(
                    $PHQL
                );

                if(count($countries) > 0){
                    $arrayRetorno = $this->response('success','Ok',$countries);
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

