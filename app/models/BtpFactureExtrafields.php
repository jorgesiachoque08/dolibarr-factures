<?php

class BtpFactureExtrafields extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $rowid;

    /**
     *
     * @var string
     */
    public $tms;

    /**
     *
     * @var integer
     */
    public $fk_object;

    /**
     *
     * @var string
     */
    public $import_key;

    /**
     *
     * @var integer
     */
    public $is_facture_internacional;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_facture_extrafields");
        $this->belongsTo('fk_object', '\BtpFacture', 'rowid', ['alias' => 'BtpFacture']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFactureExtrafields[]|BtpFactureExtrafields|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFactureExtrafields|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
