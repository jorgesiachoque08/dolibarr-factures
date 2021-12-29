<?php

class OauthClients extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $client_id;

    /**
     *
     * @var string
     */
    public $client_secret;

    /**
     *
     * @var string
     */
    public $redirect_uri;

    /**
     *
     * @var string
     */
    public $grant_types;

    /**
     *
     * @var string
     */
    public $scope;

    /**
     *
     * @var string
     */
    public $expires;

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
    public $brand_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSource("oauth_clients");
        $this->hasMany('client_id', 'OauthClientsBrands', 'client_id', ['alias' => 'OauthClientsBrands']);
        $this->belongsTo('id_organization', '\Organizations', 'id', ['alias' => 'Organizations']);
        $this->belongsTo('id_company', '\Companies', 'id', ['alias' => 'Companies']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthClients[]|OauthClients|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthClients|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
