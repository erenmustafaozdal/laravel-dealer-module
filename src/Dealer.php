<?php

namespace ErenMustafaOzdal\LaravelDealerModule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;

class Dealer extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'province_id',
        'county_id',
        'district_id',
        'neighborhood_id',
        'postal_code_id',
        'land_phone',
        'mobile_phone',
        'url',
        'is_publish'
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query filter with id scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        // filter id
        if ($request->has('id')) {
            $query->where('id',$request->get('id'));
        }
        // filter title
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        // filter category
        if ($request->has('category')) {
            $query->whereHas('category', function ($query) use($request) {
                $query->where('name', 'like', "%{$request->get('category')}%");
            });
        }
        // filter status
        if ($request->has('status')) {
            $query->where('is_publish',$request->get('status'));
        }
        // filter created_at
        if ($request->has('created_at_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('created_at_from')));
        }
        if ($request->has('created_at_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('created_at_to')));
        }
        return $query;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\DealerCategory');
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
            if (Request::has('category_id')) {
                $refferer = explode('/', removeDomain(Request::server('HTTP_REFERER')));
                $ids = is_array(Request::get('category_id')) && Request::get('category_id')[0] != '0'
                    ? Request::get('category_id')
                    : (
                        is_string(Request::get('category_id'))
                            ? explode(',',Request::get('category_id'))
                            : []
                    );
                if ( $refferer[1] === config('laravel-dealer-module.url.dealer_category') ) {
                    $ids[] = $refferer[2];
                    if ( ! is_null($model->categories->first()) && ! in_array($model->categories->first()->id,$ids) ) {
                        $ids[] = $model->categories->first()->id;
                    }
                }
                $model->categories()->sync( $ids );
            }

        });
    }
}
