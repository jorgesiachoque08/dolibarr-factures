<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class TemporalOauthMigration_1634145480039865
 */
class TemporalOauthMigration_1634145480039865 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('temporal_oauth', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'id_persona',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 11,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'apellido1',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'id_persona'
                        ]
                    ),
                    new Column(
                        'apellido2',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'apellido1'
                        ]
                    ),
                    new Column(
                        'descripcion',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'apellido2'
                        ]
                    ),
                    new Column(
                        'tipo_documento',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'descripcion'
                        ]
                    ),
                    new Column(
                        'dni',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'tipo_documento'
                        ]
                    ),
                    new Column(
                        'nombre',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'dni'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'nombre'
                        ]
                    ),
                    new Column(
                        'nacimiento',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'telefono',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'nacimiento'
                        ]
                    ),
                    new Column(
                        'movil',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'telefono'
                        ]
                    ),
                    new Column(
                        'genero',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'after' => 'movil'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '1046',
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
        $this::$connection->addColumn(
            'temporal_oauth',
            'dbOAuth',
            new Column(
                'id_company',
                [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 10
                ]
            ),
        );

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
