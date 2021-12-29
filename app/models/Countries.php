<?php

class Countries extends \Phalcon\Mvc\Model
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
        $this->setSource("countries");
        $this->hasMany('id', 'Cities', 'id_country', ['alias' => 'Cities']);
        $this->hasMany('id', 'Companies', 'id_country', ['alias' => 'Companies']);
        $this->hasMany('id', 'Departments', 'id_country', ['alias' => 'Departments']);
        $this->hasMany('id', 'Users', 'id_country', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Countries[]|Countries|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Countries|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
