<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CountriesMigration_1624050710276119
 */
class CountriesMigration_1624050710276119 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('countries', [
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
                            'size' => 70,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_BOOLEAN,
                            'default' => "1",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'name'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '4',
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
        $this->batchInsert('countries', [
                'id',
                'name',
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
        $this->batchDelete('countries');
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('countries', [
                'id',
                'name',
                'status'
            ]
        );
     }
}
