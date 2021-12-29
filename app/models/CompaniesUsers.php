<?php
//namespace App\Models;
class CompaniesUsers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_company;

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var integer
     */
    public $id_tinnova;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $id_organization;

    /**
     *
     * @var integer
     */
    public $id_external;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dbOAuth");
        $this->setSource("companies_users");
        $this->belongsTo('id_user', '\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('id_company', '\Companies', 'id', ['alias' => 'Companies']);
        $this->belongsTo('id_organization', '\Organizations', 'id', ['alias' => 'Organizations']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompaniesUsers[]|CompaniesUsers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompaniesUsers|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
