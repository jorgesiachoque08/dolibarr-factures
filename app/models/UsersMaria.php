<?php
//namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UsersMaria extends \Phalcon\Mvc\Model
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
    public $utm_source;

    /**
     *
     * @var string
     */
    public $utm_medium;

    /**
     *
     * @var string
     */
    public $utm_campaign;

    /**
     *
     * @var string
     */
    public $emergency_contact_name;

    /**
     *
     * @var string
     */
    public $emergency_contact_phone;

    /**
     *
     * @var string
     */
    public $has_cat;

    /**
     *
     * @var string
     */
    public $has_dog;

    /**
     *
     * @var string
     */
    public $mobile_phone;

    /**
     *
     * @var string
     */
    public $occupation;

    /**
     *
     * @var string
     */
    public $other_mascots;

    /**
     *
     * @var string
     */
    public $profession;

    /**
     *
     * @var string
     */
    public $utm_ad;

    /**
     *
     * @var string
     */
    public $utm_adset;

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
    public $platform;

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
        $this->setConnectionService('databaseAmigrar');
        $this->setSource("user_user");
        $this->hasMany('id', 'CompaniesUsers', 'id_user', ['alias' => 'CompaniesUsers']);
        $this->hasMany('id', 'Log', 'id_user', ['alias' => 'Log']);
        $this->belongsTo('city_id', '\Cities', 'id', ['alias' => 'Cities']);
        $this->belongsTo('document_type', '\DocumentType', 'id', ['alias' => 'DocumentType']);
        $this->belongsTo('id_country', '\Countries', 'id', ['alias' => 'Countries']);
        $this->belongsTo('id_organization', '\Organizations', 'id', ['alias' => 'Organizations']);
        $this->belongsTo('id_company', '\Companies', 'id', ['alias' => 'Companies']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
