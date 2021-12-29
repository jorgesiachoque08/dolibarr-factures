<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class LogMigration_1624051489276223
 */
class LogMigration_1624051489276223 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('log', [
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
                        'action',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'id_user',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'action'
                        ]
                    ),
                    new Column(
                        'new_record',
                        [
                            'type' => Column::TYPE_LONGTEXT,
                            'notNull' => true,
                            'after' => 'id_user'
                        ]
                    ),
                    new Column(
                        'old_record',
                        [
                            'type' => Column::TYPE_LONGTEXT,
                            'notNull' => false,
                            'after' => 'new_record'
                        ]
                    ),
                    new Column(
                        'table',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'old_record'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'default' => "current_timestamp()",
                            'notNull' => false,
                            'after' => 'table'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_log_1_idx', ['id_user'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_log_1',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_user'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '1',
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
