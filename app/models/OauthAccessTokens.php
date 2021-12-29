<?php

class OauthAccessTokens extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $access_token;

    /**
     *
     * @var string
     */
    public $client_id;

    /**
     *
     * @var integer
     */
    public $user_id;

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
     * Initialize method for model.
     */
    public function initialize()
    {   $this->setConnectionService('dbMaria');
        $this->setSchema("dbOAuth");
        $this->setSource("oauth_access_tokens");
        $this->belongsTo('user_id', 'UsersClient', 'id', ['alias' => 'UsersClient']);
        $this->belongsTo('user_id', 'UsersCollaborators', 'id', ['alias' => 'UsersCollaborators']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthAccessTokens[]|OauthAccessTokens|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OauthAccessTokens|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
