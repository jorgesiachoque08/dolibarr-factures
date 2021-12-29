<?php

class Cities extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $id_country;

    /**
     *
     * @var integer
     */
    public $id_department;

    /**
     *
     * @var string
     */
    public $latitude;

    /**
     *
     * @var string
     */
    public $longitude;

    /**
     *
     * @var string
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dbOAuth");
        $this->setSource("cities");
        $this->belongsTo('id_department', '\Departments', 'id', ['alias' => 'Departments']);
        $this->belongsTo('id_country', '\Countries', 'id', ['alias' => 'Countries']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cities[]|Cities|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cities|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
