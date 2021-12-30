<?php

class BtpSocieteExtrafields extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $fk_document_type_id;

    /**
     *
     * @var string
     */
    public $fk_country_externo;

    /**
     *
     * @var string
     */
    public $fk_country_externo_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_societe_extrafields");
        $this->belongsTo('fk_object', '\BtpSociete', 'rowid', ['alias' => 'BtpSociete']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpSocieteExtrafields[]|BtpSocieteExtrafields|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpSocieteExtrafields|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
