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

    /**
     * get detail data with all of the relation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetDealerWithDetail($query)
    {
        return $query->hasPublishedElement('dealers')->with([
            'dealers' => function($query)
            {
                return $query->getDetail()->published()->orderBy('id','desc');
            }
        ]);
    }





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
        return $this->hasMany('App\Dealer','category_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
