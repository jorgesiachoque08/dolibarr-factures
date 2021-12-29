<?php

class Companies extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $id_organization;
    /**
     *
     * @var string
     */
    public $uuid;
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dbOAuth");
        $this->setSource("companies");
        $this->hasMany('id', 'CompaniesUsers', 'id_company', ['alias' => 'CompaniesUsers']);
        $this->hasMany('id', 'OauthClients', 'id_company', ['alias' => 'OauthClients']);
        $this->hasMany('id', 'Users', 'id_company', ['alias' => 'Users']);
        $this->belongsTo('id_country', '\Countries', 'id', ['alias' => 'Countries']);
        $this->belongsTo('id_organization', '\Organizations', 'id', ['alias' => 'Organizations']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Companies[]|Companies|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Companies|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
