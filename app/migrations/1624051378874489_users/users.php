<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersMigration_1624051378874489
 */
class UsersMigration_1624051378874489 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users', [
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
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => true,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "current_timestamp()",
                            'notNull' => true,
                            'after' => 'password'
                        ]
                    ),
                    new Column(
                        'last_login',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "current_timestamp()",
                            'notNull' => true,
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
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'is_active'
                        ]
                    ),
                    new Column(
                        'display_image',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'genre',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
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
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => true,
                            'after' => 'id_tinnova'
                        ]
                    ),
                    new Column(
                        'personal_timetable',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
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
                        'utm_source',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'birthdate'
                        ]
                    ),
                    new Column(
                        'utm_medium',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'utm_source'
                        ]
                    ),
                    new Column(
                        'utm_campaign',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'utm_medium'
                        ]
                    ),
                    new Column(
                        'emergency_contact_name',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'utm_campaign'
                        ]
                    ),
                    new Column(
                        'emergency_contact_phone',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'emergency_contact_name'
                        ]
                    ),
                    new Column(
                        'has_cat',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'emergency_contact_phone'
                        ]
                    ),
                    new Column(
                        'has_dog',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'has_cat'
                        ]
                    ),
                    new Column(
                        'mobile_phone',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'has_dog'
                        ]
                    ),
                    new Column(
                        'occupation',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'mobile_phone'
                        ]
                    ),
                    new Column(
                        'other_mascots',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'occupation'
                        ]
                    ),
                    new Column(
                        'profession',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'other_mascots'
                        ]
                    ),
                    new Column(
                        'utm_ad',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'profession'
                        ]
                    ),
                    new Column(
                        'utm_adset',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'utm_ad'
                        ]
                    ),
                    new Column(
                        'email_verified',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
                            'notNull' => false,
                            'after' => 'utm_adset'
                        ]
                    ),
                    new Column(
                        'terms_data',
                        [
                            'type' => Column::TYPE_MEDIUMTEXT,
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
                        'platform',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 20,
                            'after' => 'id_external'
                        ]
                    ),
                    new Column(
                        'uuid_organization',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'platform'
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
                    new Index('fk_users_1_idx', ['id_country'], ''),
                    new Index('fk_users_2_idx', ['id_organization'], ''),
                    new Index('fk_users_3_idx', ['id_company'], ''),
                    new Index('email_id_organization', ['id_organization', 'email'], ''),
                    new Index('organization_dt_tn', ['id_organization', 'document_number', 'document_type'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_users_1',
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
                        'fk_users_2',
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
                        'fk_users_3',
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
                    'auto_increment' => '330578',
                    'engine' => 'InnoDB',
                    'table_collation' => 'utf8mb4_unicode_ci'
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
