<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersMigration_1628784815870666
 */
class UsersMigration_1628784815870666 extends Migration
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
        $this::$connection->addColumn(
            'users',
            'dbOAuth',
            new Column(
                'validate_cron',
                [   
                    'type' => Column::TYPE_SMALLINTEGER,
                    'notNull' => false,
                    'default' => "0",
                    'size' => 1
                ]
            ),
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
