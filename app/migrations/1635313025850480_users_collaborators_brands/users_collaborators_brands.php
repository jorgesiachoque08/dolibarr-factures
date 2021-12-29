<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersCollaboratorsBrandsMigration_1635313025850480
 */
class UsersCollaboratorsBrandsMigration_1635313025850480 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users_collaborators_brands', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 1,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'brand_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'company_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'brand_id'
                        ]
                    ),
                    new Column(
                        'organization_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'company_id'
                        ]
                    ),
                    new Column(
                        'uuid_brand',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'organization_id'
                        ]
                    ),
                    new Column(
                        'uuid_company',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'uuid_brand'
                        ]
                    ),
                    new Column(
                        'uuid_organization',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'uuid_company'
                        ]
                    ),
                    new Column(
                        'created_at_db',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'uuid_organization'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'created_at_db'
                        ]
                    ),
                    new Column(
                        'update_at_db',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'notNull' => false,
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'update_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'notNull' => false,
                            'after' => 'update_at_db'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_TINYINTEGER,
                            'default' => "1",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'update_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('user_brand_company_orga_unq', ['user_id', 'brand_id', 'company_id', 'organization_id'], 'UNIQUE')
                ],
                'references' => [
                    new Reference(
                        'users_collaborator_fk',
                        [
                            'referencedTable' => 'users_collaborators',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['user_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '4',
                    'engine' => 'InnoDB',
                    'table_collation' => 'utf8mb4_0900_ai_ci'
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
