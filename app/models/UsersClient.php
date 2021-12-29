<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UsersClient extends \Phalcon\Mvc\Model
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
    public $user_name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

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
     * @var integer
     */
    public $company_id;

    /**
     *
     * @var integer
     */
    public $brand_id;

    /**
     *
     * @var string
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $mobile_phone;

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
        $this->setSource("users_client");
        $this->hasMany('id', 'UsersProfile', 'user_id', ['alias' => 'UsersProfile']);
        $this->belongsTo('brand_id', 'Brand', 'id', ['alias' => 'Brand']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersClient[]|UsersClient|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersClient|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
