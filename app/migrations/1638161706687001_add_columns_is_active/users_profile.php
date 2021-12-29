<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersProfileMigration_1638161706687001
 */
class UsersProfileMigration_1638161706687001 extends Migration
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
                'is_active',
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