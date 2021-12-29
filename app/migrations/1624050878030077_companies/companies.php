<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CompaniesMigration_1624050878030077
 */
class CompaniesMigration_1624050878030077 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('companies', [
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
                            'size' => 100,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'id_country',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
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
                            'after' => 'id_country'
                        ]
                    ),
                    new Column(
                        'id_organization',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'status'
                        ]
                    ),
                    new Column(
                        'uuid',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'id_organization'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_companies_1_idx', ['id_country'], ''),
                    new Index('fk_companies_2_idx', ['id_organization'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_companies_1',
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
                        'fk_companies_2',
                        [
                            'referencedTable' => 'organizations',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_organization'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '6',
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
        $this->batchInsert('companies', [
                'id',
                'name',
                'id_country',
                'status',
                'id_organization',
                'uuid'
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
        $this->batchDelete('companies');
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('companies', [
                'id',
                'name',
                'id_country',
                'status',
                'id_organization',
                'uuid'
            ]
        );
     }
}
