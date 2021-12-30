<?php

class BtpFacturedet extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $rowid;

    /**
     *
     * @var integer
     */
    public $fk_facture;

    /**
     *
     * @var integer
     */
    public $fk_parent_line;

    /**
     *
     * @var integer
     */
    public $fk_product;

    /**
     *
     * @var string
     */
    public $label;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $vat_src_code;

    /**
     *
     * @var string
     */
    public $tva_tx;

    /**
     *
     * @var string
     */
    public $localtax1_tx;

    /**
     *
     * @var string
     */
    public $localtax1_type;

    /**
     *
     * @var string
     */
    public $localtax2_tx;

    /**
     *
     * @var string
     */
    public $localtax2_type;

    /**
     *
     * @var string
     */
    public $qty;

    /**
     *
     * @var string
     */
    public $remise_percent;

    /**
     *
     * @var string
     */
    public $remise;

    /**
     *
     * @var integer
     */
    public $fk_remise_except;

    /**
     *
     * @var string
     */
    public $subprice;

    /**
     *
     * @var string
     */
    public $price;

    /**
     *
     * @var string
     */
    public $total_ht;

    /**
     *
     * @var string
     */
    public $total_tva;

    /**
     *
     * @var string
     */
    public $total_localtax1;

    /**
     *
     * @var string
     */
    public $total_localtax2;

    /**
     *
     * @var string
     */
    public $total_ttc;

    /**
     *
     * @var integer
     */
    public $product_type;

    /**
     *
     * @var string
     */
    public $date_start;

    /**
     *
     * @var string
     */
    public $date_end;

    /**
     *
     * @var integer
     */
    public $info_bits;

    /**
     *
     * @var string
     */
    public $buy_price_ht;

    /**
     *
     * @var integer
     */
    public $fk_product_fournisseur_price;

    /**
     *
     * @var integer
     */
    public $special_code;

    /**
     *
     * @var integer
     */
    public $rang;

    /**
     *
     * @var integer
     */
    public $fk_contract_line;

    /**
     *
     * @var integer
     */
    public $fk_unit;

    /**
     *
     * @var string
     */
    public $import_key;

    /**
     *
     * @var integer
     */
    public $fk_code_ventilation;

    /**
     *
     * @var string
     */
    public $situation_percent;

    /**
     *
     * @var integer
     */
    public $fk_prev_id;

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
    public $multicurrency_subprice;

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
     *
     * @var string
     */
    public $ref_ext;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_facturedet");
        $this->hasMany('rowid', 'BtpSocieteRemiseExcept', 'fk_facture_line', ['alias' => 'BtpSocieteRemiseExcept']);
        $this->belongsTo('fk_facture', '\BtpFacture', 'rowid', ['alias' => 'BtpFacture']);
        $this->belongsTo('fk_unit', '\BtpCUnits', 'rowid', ['alias' => 'BtpCUnits']);
        $this->belongsTo('fk_product', '\BtpProduct', 'rowid', ['alias' => 'BtpProduct']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFacturedet[]|BtpFacturedet|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpFacturedet|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
