<?php

class Brand extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $uuid;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $company_id;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $update_at;

    /**
     *
     * @var string
     */
    public $created_at_db;

    /**
     *
     * @var string
     */
    public $update_at_db;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('dbMy_bodytech');
        $this->setSource("brand");
        // $this->hasMany('id', 'bodytech\Venue', 'brand_id', ['alias' => 'Venue']);
        // $this->belongsTo('company_id', 'bodytech\Company', 'id', ['alias' => 'Company']);
    }


}
