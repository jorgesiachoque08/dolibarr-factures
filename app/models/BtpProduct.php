<?php

class BtpProduct extends \Phalcon\Mvc\Model
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
    public $datec;

    /**
     *
     * @var string
     */
    public $tms;

    /**
     *
     * @var integer
     */
    public $fk_parent;

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
    public $note_public;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var string
     */
    public $customcode;

    /**
     *
     * @var integer
     */
    public $fk_country;

    /**
     *
     * @var integer
     */
    public $fk_state;

    /**
     *
     * @var string
     */
    public $price;

    /**
     *
     * @var string
     */
    public $price_ttc;

    /**
     *
     * @var string
     */
    public $price_min;

    /**
     *
     * @var string
     */
    public $price_min_ttc;

    /**
     *
     * @var string
     */
    public $price_base_type;

    /**
     *
     * @var string
     */
    public $cost_price;

    /**
     *
     * @var string
     */
    public $default_vat_code;

    /**
     *
     * @var string
     */
    public $tva_tx;

    /**
     *
     * @var integer
     */
    public $recuperableonly;

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
     * @var string
     */
    public $tosell;

    /**
     *
     * @var string
     */
    public $tobuy;

    /**
     *
     * @var string
     */
    public $onportal;

    /**
     *
     * @var string
     */
    public $tobatch;

    /**
     *
     * @var integer
     */
    public $fk_product_type;

    /**
     *
     * @var string
     */
    public $duration;

    /**
     *
     * @var double
     */
    public $seuil_stock_alerte;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $barcode;

    /**
     *
     * @var integer
     */
    public $fk_barcode_type;

    /**
     *
     * @var string
     */
    public $accountancy_code_sell;

    /**
     *
     * @var string
     */
    public $accountancy_code_sell_intra;

    /**
     *
     * @var string
     */
    public $accountancy_code_sell_export;

    /**
     *
     * @var string
     */
    public $accountancy_code_buy;

    /**
     *
     * @var string
     */
    public $accountancy_code_buy_intra;

    /**
     *
     * @var string
     */
    public $accountancy_code_buy_export;

    /**
     *
     * @var string
     */
    public $partnumber;

    /**
     *
     * @var double
     */
    public $net_measure;

    /**
     *
     * @var string
     */
    public $net_measure_units;

    /**
     *
     * @var double
     */
    public $weight;

    /**
     *
     * @var string
     */
    public $weight_units;

    /**
     *
     * @var double
     */
    public $length;

    /**
     *
     * @var string
     */
    public $length_units;

    /**
     *
     * @var double
     */
    public $width;

    /**
     *
     * @var string
     */
    public $width_units;

    /**
     *
     * @var double
     */
    public $height;

    /**
     *
     * @var string
     */
    public $height_units;

    /**
     *
     * @var double
     */
    public $surface;

    /**
     *
     * @var string
     */
    public $surface_units;

    /**
     *
     * @var double
     */
    public $volume;

    /**
     *
     * @var string
     */
    public $volume_units;

    /**
     *
     * @var string
     */
    public $stock;

    /**
     *
     * @var string
     */
    public $pmp;

    /**
     *
     * @var string
     */
    public $fifo;

    /**
     *
     * @var string
     */
    public $lifo;

    /**
     *
     * @var integer
     */
    public $fk_default_warehouse;

    /**
     *
     * @var string
     */
    public $canvas;

    /**
     *
     * @var string
     */
    public $finished;

    /**
     *
     * @var string
     */
    public $hidden;

    /**
     *
     * @var string
     */
    public $import_key;

    /**
     *
     * @var string
     */
    public $model_pdf;

    /**
     *
     * @var integer
     */
    public $fk_price_expression;

    /**
     *
     * @var double
     */
    public $desiredstock;

    /**
     *
     * @var integer
     */
    public $fk_unit;

    /**
     *
     * @var string
     */
    public $price_autogen;

    /**
     *
     * @var integer
     */
    public $fk_project;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_product");
        $this->hasMany('rowid', 'BtpCategorieProduct', 'fk_product', ['alias' => 'BtpCategorieProduct']);
        $this->hasMany('rowid', 'BtpContratdet', 'fk_product', ['alias' => 'BtpContratdet']);
        $this->hasMany('rowid', 'BtpMrpProduction', 'fk_product', ['alias' => 'BtpMrpProduction']);
        $this->hasMany('rowid', 'BtpProductCustomerPrice', 'fk_product', ['alias' => 'BtpProductCustomerPrice']);
        $this->hasMany('rowid', 'BtpProductFournisseurPrice', 'fk_product', ['alias' => 'BtpProductFournisseurPrice']);
        $this->hasMany('rowid', 'BtpProductLang', 'fk_product', ['alias' => 'BtpProductLang']);
        $this->hasMany('rowid', 'BtpProductPrice', 'fk_product', ['alias' => 'BtpProductPrice']);
        $this->belongsTo('fk_barcode_type', '\BtpCBarcodeType', 'rowid', ['alias' => 'BtpCBarcodeType']);
        $this->belongsTo('fk_default_warehouse', '\BtpEntrepot', 'rowid', ['alias' => 'BtpEntrepot']);
        $this->belongsTo('finished', '\BtpCProductNature', 'code', ['alias' => 'BtpCProductNature']);
        $this->belongsTo('fk_country', '\BtpCCountry', 'rowid', ['alias' => 'BtpCCountry']);
        $this->belongsTo('fk_unit', '\BtpCUnits', 'rowid', ['alias' => 'BtpCUnits']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpProduct[]|BtpProduct|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpProduct|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
