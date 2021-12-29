<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersProfileMigration_1636382774777074
 */
class UsersProfileMigration_1636382774777074 extends Migration
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
                'is_superuser',
                [
                    'type' => Column::TYPE_SMALLINTEGER,
                    'default' => "0",
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 1
                ]
            ),
        );

        $this::$connection->addColumn(
            'users_profile',
            'dbOAuth',
            new Column(
                'is_staff',
                [
                    'type' => Column::TYPE_SMALLINTEGER,
                    'default' => "0",
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 1
                ]
            ),
        );
        $this::$connection->addColumn(
            'users_profile',
            'dbOAuth',
            new Column(
                'type_user',
                [
                    'type' => Column::TYPE_SMALLINTEGER,
                    'default' => "0",
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
