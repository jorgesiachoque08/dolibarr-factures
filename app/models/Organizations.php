<?php

class Organizations extends \Phalcon\Mvc\Model
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
        $this->setSource("organizations");
        $this->hasMany('id', 'Companies', 'id_organization', ['alias' => 'Companies']);
        $this->hasMany('id', 'CompaniesUsers', 'id_organization', ['alias' => 'CompaniesUsers']);
        $this->hasMany('id', 'OauthClients', 'id_organization', ['alias' => 'OauthClients']);
        $this->hasMany('id', 'TemplateCompanyEmail', 'id_organization', ['alias' => 'TemplateCompanyEmail']);
        $this->hasMany('id', 'Users', 'id_organization', ['alias' => 'Users']);
        $this->hasMany('id', 'UsersInternal', 'id_organization', ['alias' => 'UsersInternal']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Organizations[]|Organizations|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Organizations|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
