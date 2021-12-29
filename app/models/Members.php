<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Members extends \Phalcon\Mvc\Model
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
    public $uuid;

    /**
     *
     * @var string
     */
    public $ref_internal;

    /**
     *
     * @var string
     */
    public $ref_external;

    /**
     *
     * @var string
     */
    public $genre;

    /**
     *
     * @var string
     */
    public $marital_status;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $first_name;

    /**
     *
     * @var integer
     */
    public $type_user;

    /**
     *
     * @var string
     */
    public $address;

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
    public $lat_log;

    /**
     *
     * @var integer
     */
    public $town_id;

    /**
     *
     * @var integer
     */
    public $region_id;

    /**
     *
     * @var integer
     */
    public $country_id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $mobile_phone;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $photo_body;

    /**
     *
     * @var string
     */
    public $note_private;

    /**
     *
     * @var string
     */
    public $note_public;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $update_at_db;

    /**
     *
     * @var string
     */
    public $created_at_db;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $organization_id;

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
     * @var integer
     */
    public $venue_use;

    /**
     *
     * @var integer
     */
    public $venue_purchase;

    /**
     *
     * @var integer
     */
    public $document_type;

    /**
     *
     * @var string
     */
    public $document_number;

    /**
     *
     * @var string
     */
    public $id_tinnova;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMy_bodytech');
        $this->setSchema("my_bodytech");
        $this->setSource("members");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Members[]|Members|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Members|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
