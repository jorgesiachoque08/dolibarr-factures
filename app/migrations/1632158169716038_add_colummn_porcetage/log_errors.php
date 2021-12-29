<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class LogErrorsMigration_1632158169716038
 */
class LogErrorsMigration_1632158169716038 extends Migration
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
    {   $this::$connection->addColumn(
            'log_errors',
            'dbOAuth',
            new Column(
                'percentage',
                [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => false,
                    'default' => NULL,
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
