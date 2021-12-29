<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UserDwMigration_1633960320070557
 */
class UserDwMigration_1633960320070557 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this::$connection->execute("CREATE 
    VIEW `user_dw` AS
        SELECT 
            `U`.`id` AS `NID`,
            `H`.`id` AS `id`,
            `H`.`id_persona` AS `id_persona`,
            `H`.`apellido1` AS `apellido1`,
            `H`.`apellido2` AS `apellido2`,
            `H`.`descripcion` AS `descripcion`,
            `H`.`tipo_documento` AS `tipo_documento`,
            `H`.`dni` AS `dni`,
            `H`.`nombre` AS `nombre`,
            `H`.`email` AS `email`,
            `H`.`nacimiento` AS `nacimiento`,
            `H`.`telefono` AS `telefono`,
            `H`.`movil` AS `movil`,
            `H`.`genero` AS `genero`
        FROM
            (`temporal_oauth` `H`
            LEFT JOIN `users` `U` ON (`U`.`document_number` = `H`.`dni`))
        HAVING `NID` IS NULL");
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
