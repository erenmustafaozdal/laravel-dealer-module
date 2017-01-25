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
        return $query->with([
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





    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    /**
     * model boot method
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * model saved method
         *
         * @param $model
         */
        parent::saved(function($model)
        {
            // cache forget
            \Cache::forget(implode('_',['dealer_categories',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers_all']));
        });

        /**
         * model moved method
         *
         * @param $model
         */
        parent::moved(function($model)
        {
            // cache forget
            \Cache::forget(implode('_',['dealer_categories',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers_all']));
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            // cache forget
            \Cache::forget(implode('_',['dealer_categories',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers',$model->id]));
            \Cache::forget(implode('_',['dealer_categories','with_dealers_all']));
        });
    }
}
