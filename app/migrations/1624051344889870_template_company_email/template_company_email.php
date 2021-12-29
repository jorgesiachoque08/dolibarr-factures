<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class TemplateCompanyEmailMigration_1624051344889870
 */
class TemplateCompanyEmailMigration_1624051344889870 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('template_company_email', [
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
                        'id_company',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'data_template',
                        [
                            'type' => Column::TYPE_LONGTEXT,
                            'notNull' => false,
                            'after' => 'id_company'
                        ]
                    ),
                    new Column(
                        'type',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 45,
                            'after' => 'data_template'
                        ]
                    ),
                    new Column(
                        'id_organization',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => false,
                            'size' => 1,
                            'after' => 'type'
                        ]
                    ),
                    new Column(
                        'name_file',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'after' => 'id_organization'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_template_company_email_1_idx', ['id_company'], ''),
                    new Index('fk_template_company_email_2_idx', ['id_organization'], '')
                ],
                'references' => [
                    new Reference(
                        'fk_template_company_email_1',
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
                        'fk_template_company_email_2',
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
                    'auto_increment' => '9',
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
