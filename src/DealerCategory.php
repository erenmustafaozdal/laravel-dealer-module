<?php

namespace ErenMustafaOzdal\LaravelDealerModule;

use Baum\Node;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DealerCategory extends Node
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealer_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'show_address',
        'show_province',
        'show_county',
        'show_district',
        'show_neighborhood',
        'show_postal_code',
        'show_land_phone',
        'show_mobile_phone',
        'show_url',
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the dealers of the dealer category.
     */
    public function dealers()
    {
        return $this->hasMany('App\Dealer');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
