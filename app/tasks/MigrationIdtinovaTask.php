<?php

use Phalcon\Cli\Task;

class MigrationIdtinovaTask extends Task
{
    public function mainAction()
    {

        try {
            $UsersClientController = new UsersClientController();
            $usersProfile = UsersProfile::find(
                ['conditions' => 'id_tinnova is null']);
            foreach ($usersProfile as $key => $user) {
                $userOld = Users::findFirstById($user->user_id);
                if($userOld){
                    $user->id_tinnova = $userOld->id_tinnova;
                    if($user->save()){
                        echo "Se el usuario ".$user->user_id." actualizo el id_tinnova por ".$userOld->id_tinnova;
                        echo "\n";
                    };
                }
                
                
                
            }
            
        } catch (\Exception $ex) {
            $this->dbMaria->rollback();
            echo "Error en el servidor".$ex->getMessage();
            echo "\n";
        }

    }

}