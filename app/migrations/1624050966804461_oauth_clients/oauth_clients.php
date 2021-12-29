<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class OauthClientsMigration_1624050966804461
 */
class OauthClientsMigration_1624050966804461 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('oauth_clients', [
                'columns' => [
                    new Column(
                        'client_id',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'client_secret',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'after' => 'client_id'
                        ]
                    ),
                    new Column(
                        'redirect_uri',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'client_secret'
                        ]
                    ),
                    new Column(
                        'grant_types',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'after' => 'redirect_uri'
                        ]
                    ),
                    new Column(
                        'scope',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'grant_types'
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
                    ),
                    new Column(
                        'id_organization',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'expires'
                        ]
                    ),
                    new Column(
                        'id_company',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_organization'
                        ]
                    ),
                    new Column(
                        'uuid_company',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'id_company'
                        ]
                    ),
                    new Column(
                        'uuid_organization',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'uuid_company'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['client_id'], 'PRIMARY'),
                    new Index('fk_oauth_clients_1_idx', ['id_organization'], ''),
                    new Index('fk_oauth_clients_2_idx', ['id_company'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_oauth_clients_1',
                        [
                            'referencedTable' => 'organizations',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_organization'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'fk_oauth_clients_2',
                        [
                            'referencedTable' => 'companies',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_company'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
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
        $this->batchInsert('oauth_clients', [
                'client_id',
                'client_secret',
                'redirect_uri',
                'grant_types',
                'scope',
                'expires',
                'id_organization',
                'id_company',
                'uuid_company',
                'uuid_organization'
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('oauth_clients');
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('oauth_clients', [
                'client_id',
                'client_secret',
                'redirect_uri',
                'grant_types',
                'scope',
                'expires',
                'id_organization',
                'id_company',
                'uuid_company',
                'uuid_organization'
            ]
        );
     }
}
