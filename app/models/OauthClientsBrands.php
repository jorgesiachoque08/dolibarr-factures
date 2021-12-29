<?php

class OauthClientsBrands extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $client_id;

    /**
     *
     * @var integer
     */
    public $brand_id;

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
    public $id_country_company;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSource("oauth_clients_brands");
        $this->belongsTo('client_id', '\OauthClients', 'client_id', ['alias' => 'OauthClients']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthClientsBrands[]|OauthClientsBrands|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthClientsBrands|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
