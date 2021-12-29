<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class TemplateCompanyEmailMigration_1635313359278969
 */
class TemplateCompanyEmailMigration_1635313359278969 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this::$connection->addColumn(
            'template_company_email',
            'dbOAuth',
            new Column(
                'brand_id',
                [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => false,
                    'default' => NULL,
                    'size' => 11
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
