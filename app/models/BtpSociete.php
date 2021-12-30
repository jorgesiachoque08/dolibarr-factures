<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class BtpSociete extends \Phalcon\Mvc\Model
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
    public $nom;

    /**
     *
     * @var string
     */
    public $name_alias;

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
    public $statut;

    /**
     *
     * @var integer
     */
    public $parent;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $code_client;

    /**
     *
     * @var string
     */
    public $code_fournisseur;

    /**
     *
     * @var string
     */
    public $code_compta;

    /**
     *
     * @var string
     */
    public $code_compta_fournisseur;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $zip;

    /**
     *
     * @var string
     */
    public $town;

    /**
     *
     * @var integer
     */
    public $fk_departement;

    /**
     *
     * @var integer
     */
    public $fk_pays;

    /**
     *
     * @var integer
     */
    public $fk_account;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $socialnetworks;

    /**
     *
     * @var string
     */
    public $skype;

    /**
     *
     * @var string
     */
    public $twitter;

    /**
     *
     * @var string
     */
    public $facebook;

    /**
     *
     * @var string
     */
    public $linkedin;

    /**
     *
     * @var string
     */
    public $instagram;

    /**
     *
     * @var string
     */
    public $snapchat;

    /**
     *
     * @var string
     */
    public $googleplus;

    /**
     *
     * @var string
     */
    public $youtube;

    /**
     *
     * @var string
     */
    public $whatsapp;

    /**
     *
     * @var integer
     */
    public $fk_effectif;

    /**
     *
     * @var integer
     */
    public $fk_typent;

    /**
     *
     * @var integer
     */
    public $fk_forme_juridique;

    /**
     *
     * @var string
     */
    public $fk_currency;

    /**
     *
     * @var string
     */
    public $siren;

    /**
     *
     * @var string
     */
    public $siret;

    /**
     *
     * @var string
     */
    public $ape;

    /**
     *
     * @var string
     */
    public $idprof4;

    /**
     *
     * @var string
     */
    public $idprof5;

    /**
     *
     * @var string
     */
    public $idprof6;

    /**
     *
     * @var string
     */
    public $tva_intra;

    /**
     *
     * @var string
     */
    public $capital;

    /**
     *
     * @var integer
     */
    public $fk_stcomm;

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
    public $prefix_comm;

    /**
     *
     * @var string
     */
    public $client;

    /**
     *
     * @var string
     */
    public $fournisseur;

    /**
     *
     * @var string
     */
    public $supplier_account;

    /**
     *
     * @var string
     */
    public $fk_prospectlevel;

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
     * @var string
     */
    public $customer_bad;

    /**
     *
     * @var string
     */
    public $customer_rate;

    /**
     *
     * @var string
     */
    public $supplier_rate;

    /**
     *
     * @var string
     */
    public $remise_client;

    /**
     *
     * @var string
     */
    public $remise_supplier;

    /**
     *
     * @var string
     */
    public $mode_reglement;

    /**
     *
     * @var string
     */
    public $cond_reglement;

    /**
     *
     * @var string
     */
    public $transport_mode;

    /**
     *
     * @var string
     */
    public $mode_reglement_supplier;

    /**
     *
     * @var string
     */
    public $cond_reglement_supplier;

    /**
     *
     * @var string
     */
    public $transport_mode_supplier;

    /**
     *
     * @var integer
     */
    public $fk_shipping_method;

    /**
     *
     * @var string
     */
    public $tva_assuj;

    /**
     *
     * @var string
     */
    public $localtax1_assuj;

    /**
     *
     * @var string
     */
    public $localtax1_value;

    /**
     *
     * @var string
     */
    public $localtax2_assuj;

    /**
     *
     * @var string
     */
    public $localtax2_value;

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
     * @var integer
     */
    public $price_level;

    /**
     *
     * @var string
     */
    public $outstanding_limit;

    /**
     *
     * @var string
     */
    public $order_min_amount;

    /**
     *
     * @var string
     */
    public $supplier_order_min_amount;

    /**
     *
     * @var string
     */
    public $default_lang;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var string
     */
    public $logo_squarred;

    /**
     *
     * @var string
     */
    public $canvas;

    /**
     *
     * @var integer
     */
    public $fk_entrepot;

    /**
     *
     * @var string
     */
    public $webservices_url;

    /**
     *
     * @var string
     */
    public $webservices_key;

    /**
     *
     * @var string
     */
    public $tms;

    /**
     *
     * @var string
     */
    public $datec;

    /**
     *
     * @var integer
     */
    public $fk_user_creat;

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
    public $import_key;

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
        $this->setConnectionService('dbMaria');
        $this->setSchema("dolibarr_prime");
        $this->setSource("btp_societe");
        $this->hasMany('rowid', 'BtpAdherent', 'fk_soc', ['alias' => 'BtpAdherent']);
        $this->hasMany('rowid', 'BtpCategorieFournisseur', 'fk_soc', ['alias' => 'BtpCategorieFournisseur']);
        $this->hasMany('rowid', 'BtpCategorieSociete', 'fk_soc', ['alias' => 'BtpCategorieSociete']);
        $this->hasMany('rowid', 'BtpCommande', 'fk_soc', ['alias' => 'BtpCommande']);
        $this->hasMany('rowid', 'BtpCommandeFournisseur', 'fk_soc', ['alias' => 'BtpCommandeFournisseur']);
        $this->hasMany('rowid', 'BtpContrat', 'fk_soc', ['alias' => 'BtpContrat']);
        $this->hasMany('rowid', 'BtpDelivery', 'fk_soc', ['alias' => 'BtpDelivery']);
        $this->hasMany('rowid', 'BtpExpedition', 'fk_soc', ['alias' => 'BtpExpedition']);
        $this->hasMany('rowid', 'BtpFacture', 'fk_soc', ['alias' => 'BtpFacture']);
        $this->hasMany('rowid', 'BtpFactureFourn', 'fk_soc', ['alias' => 'BtpFactureFourn']);
        $this->hasMany('rowid', 'BtpFactureRec', 'fk_soc', ['alias' => 'BtpFactureRec']);
        $this->hasMany('rowid', 'BtpFichinter', 'fk_soc', ['alias' => 'BtpFichinter']);
        $this->hasMany('rowid', 'BtpProductCustomerPrice', 'fk_soc', ['alias' => 'BtpProductCustomerPrice']);
        $this->hasMany('rowid', 'BtpProjet', 'fk_soc', ['alias' => 'BtpProjet']);
        $this->hasMany('rowid', 'BtpPropal', 'fk_soc', ['alias' => 'BtpPropal']);
        $this->hasMany('rowid', 'BtpReception', 'fk_soc', ['alias' => 'BtpReception']);
        $this->hasMany('rowid', 'BtpSocieteAccount', 'fk_soc', ['alias' => 'BtpSocieteAccount']);
        $this->hasMany('rowid', 'BtpSocieteContacts', 'fk_soc', ['alias' => 'BtpSocieteContacts']);
        $this->hasMany('rowid', 'BtpSocieteRemiseExcept', 'fk_soc', ['alias' => 'BtpSocieteRemiseExcept']);
        $this->hasMany('rowid', 'BtpSocieteRib', 'fk_soc', ['alias' => 'BtpSocieteRib']);
        $this->hasMany('rowid', 'BtpSocpeople', 'fk_soc', ['alias' => 'BtpSocpeople']);
        $this->belongsTo('rowid', '\BtpSocieteExtrafields', 'fk_object', ['alias' => 'BtpSocieteExtrafields']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpSociete[]|BtpSociete|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BtpSociete|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
