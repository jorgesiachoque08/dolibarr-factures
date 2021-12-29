<?php

class UsersCollaboratorsBrands extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $brand_id;

    /**
     *
     * @var integer
     */
    public $company_id;

    /**
     *
     * @var integer
     */
    public $organization_id;

    /**
     *
     * @var string
     */
    public $uuid_brand;

    /**
     *
     * @var string
     */
    public $uuid_company;

    /**
     *
     * @var string
     */
    public $uuid_organization;

    /**
     *
     * @var string
     */
    public $created_at_db;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $update_at_db;

    /**
     *
     * @var string
     */
    public $update_at;

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
        $this->setSource("users_collaborators_brands");
        $this->belongsTo('user_id', '\UsersCollaborators', 'id', ['alias' => 'UsersCollaborators']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersCollaboratorsBrands[]|UsersCollaboratorsBrands|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersCollaboratorsBrands|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
