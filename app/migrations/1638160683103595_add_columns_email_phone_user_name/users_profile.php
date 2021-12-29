<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersProfileMigration_1638160683103595
 */
class UsersProfileMigration_1638160683103595 extends Migration
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
                'mobile_phone',
                [   
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 100
                ]
            ),
        );

        $this::$connection->addColumn(
            'users_profile',
            'dbOAuth',
            new Column(
                'email',
                [   
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 200,
                ]
            ),
        );

        $this::$connection->addColumn(
            'users_profile',
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
