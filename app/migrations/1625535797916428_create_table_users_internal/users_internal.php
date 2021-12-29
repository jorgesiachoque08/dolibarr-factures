<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersInternalMigration_1625535797916428
 */
class UsersInternalMigration_1625535797916428 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users_internal', [
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
                        'first_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'id'
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
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'last_name'
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'password'
                        ]
                    ),
                    new Column(
                        'last_login',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'is_superuser',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'last_login'
                        ]
                    ),
                    new Column(
                        'is_staff',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'is_superuser'
                        ]
                    ),
                    new Column(
                        'is_active',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'is_staff'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'is_active'
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
                            'notNull' => false,
                            'size' => 200,
                            'after' => 'genre'
                        ]
                    ),
                    new Column(
                        'document_type',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "1",
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
                        'id_tinnova',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'city_id'
                        ]
                    ),
                    new Column(
                        'auth_token_tinnova',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'after' => 'id_tinnova'
                        ]
                    ),
                    new Column(
                        'personal_timetable',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'after' => 'auth_token_tinnova'
                        ]
                    ),
                    new Column(
                        'birthdate',
                        [
                            'type' => Column::TYPE_DATE,
                            'notNull' => false,
                            'after' => 'personal_timetable'
                        ]
                    ),
                    new Column(
                        'mobile_phone',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'birthdate'
                        ]
                    ),
                    new Column(
                        'email_verified',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'mobile_phone'
                        ]
                    ),
                    new Column(
                        'terms_data',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'email_verified'
                        ]
                    ),
                    new Column(
                        'type_user',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "0",
                            'notNull' => true,
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
                            'after' => 'type_user'
                        ]
                    ),
                    new Column(
                        'id_organization',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_country'
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
                        'id_external',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_company'
                        ]
                    ),
                    new Column(
                        'uuid_organization',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'id_external'
                        ]
                    ),
                    new Column(
                        'uuid_company',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'uuid_organization'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_users_my_bodytech_1_idx', ['id_country'], ''),
                    new Index('fk_users_my_bodytech_2_idx', ['id_organization'], ''),
                    new Index('fk_users_my_bodytech_3_idx', ['id_company'], ''),
                    new Index('email_organization', ['id_organization', 'email'], ''),
                    new Index('organization_dt_tn', ['id_organization', 'document_number', 'document_type'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_users_my_bodytech_1',
                        [
                            'referencedTable' => 'countries',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_country'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'fk_users_my_bodytech_2',
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
                        'fk_users_my_bodytech_3',
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
                    'auto_increment' => '330804',
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
