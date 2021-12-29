<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UsersInternal extends \Phalcon\Mvc\Model
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
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

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
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $is_superuser;

    /**
     *
     * @var string
     */
    public $is_staff;

    /**
     *
     * @var string
     */
    public $is_active;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $display_image;

    /**
     *
     * @var string
     */
    public $genre;

    /**
     *
     * @var string
     */
    public $document_number;

    /**
     *
     * @var string
     */
    public $document_type;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var integer
     */
    public $id_tinnova;

    /**
     *
     * @var string
     */
    public $auth_token_tinnova;

    /**
     *
     * @var string
     */
    public $personal_timetable;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var string
     */
    public $mobile_phone;

    /**
     *
     * @var string
     */
    public $email_verified;

    /**
     *
     * @var string
     */
    public $terms_data;

    /**
     *
     * @var string
     */
    public $type_user;

    /**
     *
     * @var integer
     */
    public $id_country;

    /**
     *
     * @var integer
     */
    public $id_organization;

    /**
     *
     * @var integer
     */
    public $id_company;

    /**
     *
     * @var integer
     */
    public $id_external;

    /**
     *
     * @var string
     */
    public $uuid_organization;

    /**
     *
     * @var string
     */
    public $uuid_company;
     /**
     *
     * @var string
     */
    public $user_name;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSource("users_internal");
        $this->belongsTo('id_country', '\Countries', 'id', ['alias' => 'Countries']);
        $this->belongsTo('id_organization', '\Organizations', 'id', ['alias' => 'Organizations']);
        $this->belongsTo('id_company', '\Companies', 'id', ['alias' => 'Companies']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersInternal[]|UsersInternal|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
