<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class DocumentTypeMigration_1624475165695548
 */
class DocumentTypeMigration_1624475165695548 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('document_type', [
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
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_BOOLEAN,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'external_code',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'status'
                        ]
                    ),
                    new Column(
                        'id_country',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'external_code'
                        ]
                    ),
                    new Column(
                        'id_dw_type_document',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_country'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_document_type_1_idx', ['id_country'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_document_type_1',
                        [
                            'referencedTable' => 'countries',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_country'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '50',
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
        $this->batchInsert('document_type', [
                'id',
                'name',
                'created_at',
                'status',
                'external_code',
                'id_country',
                'id_dw_type_document'
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
        $this->batchDelete('document_type');
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('document_type', [
                'id',
                'name',
                'created_at',
                'status',
                'external_code',
                'id_country',
                'id_dw_type_document'
            ]
        );
     }
}
