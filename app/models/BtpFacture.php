<?php

class BtpFacture extends \Phalcon\Mvc\Model
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
    public $ref;

    /**
     *
     * @var integer
     */
    public $entity;

    /**
     *
     * @var string
     */
    public $ref_ext;

    /**
     *
     * @var string
     */
    public $ref_int;

    /**
     *
     * @var string
     */
    public $ref_client;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $fk_soc;

    /**
     *
     * @var string
     */
    public $datec;

    /**
     *
     * @var string
     */
    public $datef;

    /**
     *
     * @var string
     */
    public $date_pointoftax;

    /**
     *
     * @var string
     */
    public $date_valid;

    /**
     *
     * @var string
     */
    public $tms;

    /**
     *
     * @var string
     */
    public $date_closing;

    /**
     *
     * @var string
     */
    public $paye;

    /**
     *
     * @var string
     */
    public $remise_percent;

    /**
     *
     * @var string
     */
    public $remise_absolue;

    /**
     *
     * @var string
     */
    public $remise;

    /**
     *
     * @var string
     */
    public $close_code;

    /**
     *
     * @var string
     */
    public $close_note;

    /**
     *
     * @var string
     */
    public $tva;

    /**
     *
     * @var string
     */
    public $localtax1;

    /**
     *
     * @var string
     */
    public $localtax2;

    /**
     *
     * @var string
     */
    public $revenuestamp;

    /**
     *
     * @var string
     */
    public $total;

    /**
     *
     * @var string
     */
    public $total_ttc;

    /**
     *
     * @var string
     */
    public $fk_statut;

    /**
     *
     * @var integer
     */
    public $fk_user_author;

    /**
     *
     * @var integer
     */
    public $fk_user_modif;

    /**
     *
     * @var integer
     */
    public $fk_user_valid;

    /**
     *
     * @var integer
     */
    public $fk_user_closing;

    /**
     *
     * @var string
     */
    public $module_source;

    /**
     *
     * @var string
     */
    public $pos_source;

    /**
     *
     * @var integer
     */
    public $fk_fac_rec_source;

    /**
     *
     * @var integer
     */
    public $fk_facture_source;

    /**
     *
     * @var integer
     */
    public $fk_projet;

    /**
     *
     * @var string
     */
    public $increment;

    /**
     *
     * @var integer
     */
    public $fk_account;

    /**
     *
     * @var string
     */
    public $fk_currency;

    /**
     *
     * @var integer
     */
    public $fk_cond_reglement;

    /**
     *
     * @var integer
     */
    public $fk_mode_reglement;

    /**
     *
     * @var string
     */
    public $date_lim_reglement;

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
    public $model_pdf;

    /**
     *
     * @var string
     */
    public $last_main_doc;

    /**
     *
     * @var integer
     */
    public $fk_incoterms;

    /**
     *
     * @var string
     */
    public $location_incoterms;

    /**
     *
     * @var integer
     */
    public $fk_mode_transport;

    /**
     *
     * @var string
     */
    public $situation_cycle_ref;

    /**
     *
     * @var string
     */
    public $situation_counter;

    /**
     *
     * @var string
     */
    public $situation_final;

    /**
     *
     * @var string
     */
    public $retained_warranty;

    /**
     *
     * @var string
     */
    public $retained_warranty_date_limit;

    /**
     *
     * @var integer
     */
    public $retained_warranty_fk_cond_reglement;

    /**
     *
     * @var string
     */
    public $import_key;

    /**
     *
     * @var string
     */
    public $extraparams;

    /**
     *
     * @var integer
     */
    public $fk_multicurrency;

    /**
     *
     * @var string
     */
    public $multicurrency_code;

    /**
     *
     * @var string
     */
    public $multicurrency_tx;

    /**
     *
     * @var string
     */
    public $multicurrency_total_ht;

    /**
     *
     * @var string
     */
    public $multicurrency_total_tva;

    /**
     *
     * @var string
     */
    public $multicurrency_total_ttc;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_facture");
        $this->hasMany('rowid', 'BtpFacture', 'fk_facture_source', ['alias' => 'BtpFacture']);
        $this->hasMany('rowid', 'BtpFacturedet', 'fk_facture', ['alias' => 'BtpFacturedet']);
        $this->hasMany('rowid', 'BtpPaiementFacture', 'fk_facture', ['alias' => 'BtpPaiementFacture']);
        $this->hasMany('rowid', 'BtpSocieteRemiseExcept', 'fk_facture', ['alias' => 'BtpSocieteRemiseExcept']);
        $this->hasMany('rowid', 'BtpSocieteRemiseExcept', 'fk_facture_source', ['alias' => 'BtpSocieteRemiseExcept']);
        $this->belongsTo('fk_facture_source', '\BtpFacture', 'rowid', ['alias' => 'BtpFacture']);
        $this->belongsTo('fk_projet', '\BtpProjet', 'rowid', ['alias' => 'BtpProjet']);
        $this->belongsTo('fk_soc', 'BtpSociete', 'rowid', ['alias' => 'BtpSociete']);
        $this->belongsTo('fk_user_author', '\BtpUser', 'rowid', ['alias' => 'BtpUser']);
        $this->belongsTo('fk_user_valid', '\BtpUser', 'rowid', ['alias' => 'BtpUser']);
        $this->belongsTo('rowid', '\BtpFactureExtrafields', 'fk_object', ['alias' => 'BtpFactureExtrafields']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFacture[]|BtpFacture|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFacture|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
