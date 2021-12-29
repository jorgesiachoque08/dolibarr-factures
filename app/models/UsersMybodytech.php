<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Users
 */
class UsersMybodytech extends \Phalcon\Mvc\Model
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
    public $document_number;

    /**
     *
     * @var integer
     */
    public $user_profiles_id;

    /**
     *
     * @var integer
     */
    public $document_type_id;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $digital_signature;

    /**
     *
     * @var string
     */
    public $professional_card;

    /**
     *
     * @var string
     */
    public $is_active;

    /**
     *
     * @var integer
     */
    public $appointment_mode_id;

    /**
     *
     * @var integer
     */
    public $type_contract_id;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var integer
     */
    public $id_country;

    /**
     *
     * @var integer
     */
    public $id_city;

    /**
     *
     * @var string
     */
    public $phone_number;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $genre;

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
     * @var string
     */
    public $is_virtual;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

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
        $this->setConnectionService('dbMy_bodytech');
        $this->setSource("users");

    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersMybodytech[]|UsersMybodytech|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }
    
}
