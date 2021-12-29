<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersProfileMigration_1637878531393412
 */
class UsersProfileMigration_1637878531393412 extends Migration
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
            'users_profile',
            'dbOAuth',
            new Column(
                'msn',
                [   
                    'type' => Column::TYPE_SMALLINTEGER,
                    'notNull' => false,
                    'default' => "1",
                    'size' => 1
                ]
            ),
        );

        $this::$connection->addColumn(
            'users_profile',
            'dbOAuth',
            new Column(
                'sms',
                [   
                    'type' => Column::TYPE_SMALLINTEGER,
                    'notNull' => false,
                    'default' => "1",
                    'size' => 1
                ]
            ),
        );

        $this::$connection->addColumn(
            'users_profile',
            'dbOAuth',
            new Column(
                'push',
                [   
                    'type' => Column::TYPE_SMALLINTEGER,
                    'notNull' => false,
                    'default' => "1",
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
