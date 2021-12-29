<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CitiesMigration_1624050806274274
 */
class CitiesMigration_1624050806274274 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('cities', [
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
                        'id_department',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id_country'
                        ]
                    ),
                    new Column(
                        'latitude',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'default' => "0",
                            'notNull' => false,
                            'after' => 'id_department'
                        ]
                    ),
                    new Column(
                        'longitude',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'default' => "0",
                            'notNull' => false,
                            'after' => 'latitude'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_BOOLEAN,
                            'default' => "1",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'longitude'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_cities_1_idx', ['id_department'], ''),
                    new Index('fk_cities_2_idx', ['id_country'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_cities_1',
                        [
                            'referencedTable' => 'departments',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_department'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    ),
                    new Reference(
                        'fk_cities_2',
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
                    'auto_increment' => '27',
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
        $this->batchInsert('cities', [
                'id',
                'name',
                'id_country',
                'id_department',
                'latitude',
                'longitude',
                'status'
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
        $this->batchDelete('cities');
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('cities', [
                'id',
                'name',
                'id_country',
                'id_department',
                'latitude',
                'longitude',
                'status'
            ]
        );
     }
}
