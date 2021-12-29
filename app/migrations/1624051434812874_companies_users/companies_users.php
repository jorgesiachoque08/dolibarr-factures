<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CompaniesUsersMigration_1624051434812874
 */
class CompaniesUsersMigration_1624051434812874 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('companies_users', [
                'columns' => [
                    new Column(
                        'id_company',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'id_user',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id_company'
                        ]
                    ),
                    new Column(
                        'id_tinnova',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_user'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_BOOLEAN,
                            'default' => "1",
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id_tinnova'
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
                        'id_external',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id_organization'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id_company', 'id_user'], 'PRIMARY'),
                    new Index('fk_companies_users_1_idx', ['id_user'], ''),
                    new Index('fk_companies_users_2_idx', ['id_company'], ''),
                    new Index('companies_users_3_idx', ['id_organization'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_companies-users_1',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_user'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'fk_companies-users_2',
                        [
                            'referencedTable' => 'companies',
                            'referencedSchema' => 'dbOAuth',
                            'columns' => ['id_company'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'fk_companies-users_3',
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
                    'auto_increment' => '',
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
