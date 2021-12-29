<?php

class UsersProfile extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

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
     * @var integer
     */
    public $document_type;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var integer
     */
    public $dv;

    /**
     *
     * @var string
     */
    public $platform;

    /**
     *
     * @var string
     */
    public $terms_data;

    /**
     *
     * @var integer
     */
    public $id_tinnova;

    /**
     *
     * @var integer
     */
    public $id_country;

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
    public $validate_cron;
    
    /**
     *
     * @var integer
     */
    public $brand_id;

     /**
     *
     * @var integer
     */
    public $is_superuser;

     /**
     *
     * @var integer
     */
    public $is_staff;

     /**
     *
     * @var integer
     */
    public $type_user;

    /**
     *
     * @var integer
     */
    public $msn;

    /**
     *
     * @var integer
     */
    public $sms;

    /**
     *
     * @var integer
     */
    public $push;

    /**
     *
     * @var string
     */
    public $mobile_phone;

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
     * @var integer
     */
    public $is_active;
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSource("users_profile");
        $this->belongsTo('user_id', '\UsersClient', 'id', ['alias' => 'UsersClient']);
        $this->belongsTo('document_type', '\DocumentType', 'id', ['alias' => 'DocumentType']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersProfile[]|UsersProfile|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersProfile|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
