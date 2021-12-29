<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersCollaboratorsMigration_1635312838045387
 */
class UsersCollaboratorsMigration_1635312838045387 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users_collaborators', [
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
                        'user_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'user_name'
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
                        'organization_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'password'
                        ]
                    ),
                    new Column(
                        'uuid_organization',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'organization_id'
                        ]
                    ),
                    new Column(
                        'last_login',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'uuid_organization'
                        ]
                    ),
                    new Column(
                        'created_at_db',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'last_login'
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
                    new Index('email_idx', ['email'], ''),
                    new Index('user_name_idx', ['user_name'], '')
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
