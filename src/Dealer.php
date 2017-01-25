<?php

namespace ErenMustafaOzdal\LaravelDealerModule;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

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
        'category_id',
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

    /**
     * get detail data with all of the relation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetDetail($query)
    {
        return $query->with([
            'county',
        ]);
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

    /**
     * Get the province of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    /**
     * Get the county of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function county()
    {
        return $this->belongsTo('App\County');
    }

    /**
     * Get the district of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo('App\District');
    }

    /**
     * Get the neighborhood of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function neighborhood()
    {
        return $this->belongsTo('App\Neighborhood');
    }

    /**
     * Get the postal code of the dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postalCode()
    {
        return $this->belongsTo('App\PostalCode');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Set category id
     *
     * @param $category_id
     */
    public function setCategoryIdAttribute($category_id)
    {
        $this->attributes['category_id'] =  $category_id == '' || $category_id == 0 ? null : $category_id;
    }

    /**
     * Set district id
     *
     * @param $district_id
     */
    public function setDistrictIdAttribute($district_id)
    {
        $this->attributes['district_id'] =  $district_id == '' || $district_id == 0 ? null : $district_id;
    }

    /**
     * Set neighborhood id
     *
     * @param $neighborhood_id
     */
    public function setNeighborhoodIdAttribute($neighborhood_id)
    {
        $this->attributes['neighborhood_id'] =  $neighborhood_id == '' || $neighborhood_id == 0 ? null : $neighborhood_id;
    }

    /**
     * Set postal code id
     *
     * @param $postal_code_id
     */
    public function setPostalCodeIdAttribute($postal_code_id)
    {
        $this->attributes['postal_code_id'] =  $postal_code_id == '' || $postal_code_id == 0 ? null : $postal_code_id;
    }

    /**
     * get zone
     *
     * @return string
     */
    public function getZoneAttribute()
    {
        return $this->county->county;
    }

    /**
     * get full address
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $address = '';
        // mahalle
        if ($this->neighborhood) {
            $address .= ' ' . $this->neighborhood->neighborhood;
        }
        // adres
        if ($this->address) {
            $address .= ' ' . $this->address;
        }
        // semt
        if ($this->district) {
            $address .= $this->county && $this->county->county === $this->district->district
                ? ''
                : ' ' . $this->district->district;
        }
        // il ve ilçe
        if ($this->province && $this->county) {
            $address .= " {$this->county->county}/{$this->province->province}";
        } else if($this->province) {
            $address .= ' ' . $this->province->province;
        } else if($this->county) {
            $address .= ' ' . $this->county->county;
        }
        // posta kodu
        if ($this->postalCode) {
            $address .= ' ' . $this->postalCode->postal_code;
        }
        return $address;
    }





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
            foreach(\App\DealerCategory::all(['id'])->keyBy('id')->keys() as $id) {
                \Cache::forget(implode('_',['dealer_categories','with_dealers',$id]));
            }
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
            foreach(\App\DealerCategory::all(['id'])->keyBy('id')->keys() as $id) {
                \Cache::forget(implode('_',['dealer_categories','with_dealers',$id]));
            }
            \Cache::forget(implode('_',['dealer_categories','with_dealers_all']));
        });
    }
}
