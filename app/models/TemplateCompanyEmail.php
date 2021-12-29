<?php

class TemplateCompanyEmail extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $id_company;

    /**
     *
     * @var string
     */
    public $data_template;

    /**
     *
     * @var string
     */
    public $type;
    /**
     *
     * @var integer
     */
    public $id_organization;
    /**
     *
     * @var string
     */
    public $name_file;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dbOAuth");
        $this->setSource("template_company_email");
        $this->belongsTo('id_company', '\Companies', 'id', ['alias' => 'Companies']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TemplateCompanyEmail[]|TemplateCompanyEmail|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TemplateCompanyEmail|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
