<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersMigration_1624475310494688
 */
class UsersMigration_1624475310494688 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this::$connection->modifyColumn(
            'users',
            'dbOAuth',
            new Column(
                'genre',
                [
                    'type' => Column::TYPE_ENUM,
                    'notNull' => false,
                    'size' => "'Masculino','Femenino','No definido'",
                    'default' => 'No definido'
                ]
            ),
            null
        );
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
