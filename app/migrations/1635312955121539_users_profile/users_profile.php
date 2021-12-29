<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersProfileMigration_1635312955121539
 */
class UsersProfileMigration_1635312955121539 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users_profile', [
                'columns' => [
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'first_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'last_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'first_name'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'last_name'
                        ]
                    ),
                    new Column(
                        'display_image',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'genre',
                        [
                            'type' => Column::TYPE_ENUM,
                            'default' => "No definido",
                            'notNull' => false,
                            'size' => "'Masculino','Femenino','No definido'",
                            'after' => 'display_image'
                        ]
                    ),
                    new Column(
                        'document_number',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'genre'
                        ]
                    ),
                    new Column(
                        'document_type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'document_number'
                        ]
                    ),
                    new Column(
                        'city_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'document_type'
                        ]
                    ),
                    new Column(
                        'birthdate',
                        [
                            'type' => Column::TYPE_DATE,
                            'notNull' => false,
                            'after' => 'city_id'
                        ]
                    ),
                    new Column(
                        'dv',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'birthdate'
                        ]
                    ),
                    new Column(
                        'platform',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'dv'
                        ]
                    ),
                    new Column(
                        'terms_data',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'platform'
                        ]
                    ),
                    new Column(
                        'id_tinnova',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'terms_data'
                        ]
                    ),
                    new Column(
                        'id_country',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_tinnova'
                        ]
                    ),
                    new Column(
                        'created_at_db',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'id_country'
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
                            'default' => "CURRENT_TIMESTAMP",
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
                        'validate_cron',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "0",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'update_at'
                        ]
                    ),
                    new Column(
                        'brand_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'validate_cron'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['user_id'], 'PRIMARY'),
                    new Index('users_client_fk_idx', ['user_id'], ''),
                    new Index('document_type_and_number_idx', ['document_type', 'document_number'], '')
                ],
                'references' => [
                    new Reference(
                        'users_client_fk',
                        [
                            'referencedTable' => 'users_client',
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
                    'auto_increment' => '',
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
