<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class OauthClientsMigration_1634909481003244
 */
class OauthClientsMigration_1634909481003244 extends Migration
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
            'oauth_clients',
            'dbOAuth',
            new Column(
                'brand_id',
                [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 11
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
