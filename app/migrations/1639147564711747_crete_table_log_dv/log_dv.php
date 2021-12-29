<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class LogDvMigration_1639147564711747
 */
class LogDvMigration_1639147564711747 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('log_dv', [
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
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'status_log',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'message',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 200,
                            'after' => 'status_log'
                        ]
                    ),
                    new Column(
                        'dv_new',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'message'
                        ]
                    ),
                    new Column(
                        'dv_old',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'dv_new'
                        ]
                    ),
                    new Column(
                        'document_number',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'after' => 'dv_old'
                        ]
                    ),
                    new Column(
                        'id_tinnova_new',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'document_number'
                        ]
                    ),
                    new Column(
                        'id_tinnova_old',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'id_tinnova_new'
                        ]
                    ),
                    new Column(
                        'created_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'after' => 'id_tinnova_old'
                        ]
                    ),
                    new Column(
                        'updated_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => false,
                            'after' => 'created_date'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default' => "1",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'updated_date'
                        ]
                    ),
                    new Column(
                        'document_type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'status'
                        ]
                    ),
                    new Column(
                        'status_id_tinnova',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'document_type'
                        ]
                    ),
                    new Column(
                        'message_id_tinnova',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 200,
                            'after' => 'status_id_tinnova'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '24',
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
