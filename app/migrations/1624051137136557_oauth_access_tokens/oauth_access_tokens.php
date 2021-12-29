<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class OauthAccessTokensMigration_1624051137136557
 */
class OauthAccessTokensMigration_1624051137136557 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('oauth_access_tokens', [
                'columns' => [
                    new Column(
                        'access_token',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'client_id',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'after' => 'access_token'
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'client_id'
                        ]
                    ),
                    new Column(
                        'scope',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'expires',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "current_timestamp()",
                            'notNull' => true,
                            'after' => 'scope'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['access_token'], 'PRIMARY')
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '',
                    'engine' => 'InnoDB',
                    'table_collation' => 'latin1_swedish_ci'
                ],
            ]
        );
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
