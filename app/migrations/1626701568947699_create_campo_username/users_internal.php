<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersInternalMigration_1626701568947699
 */
class UsersInternalMigration_1626701568947699 extends Migration
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
            'users_internal',
            'dbOAuth',
            new Column(
                'user_name',
                [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 200
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
