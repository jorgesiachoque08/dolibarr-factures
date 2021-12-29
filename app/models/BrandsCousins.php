<?php

class BrandsCousins extends \Phalcon\Mvc\Model
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
    public $brand_id;

    /**
     *
     * @var string
     */
    public $cousins;

    /**
     *
     * @var integer
     */
    public $id_country_company;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSource("brands_cousins");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BrandsCousins[]|BrandsCousins|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BrandsCousins|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
