<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer;

use App\Http\Requests\Request;
use Sentinel;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('admin.dealer.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = is_null($this->segment(5)) ? $this->segment(3) : $this->segment(5);
        return [
            'category_id'       => 'required',
            'name'              => 'required|max:255',
            'address'           => 'max:255',
            'province_id'       => 'required|integer|exists:provinces,id',
            'county_id'         => 'required|integer|exists:counties,id',
            'district_id'       => 'integer|exists:districts,id',
            'neighborhood_id'   => 'integer|exists:neighborhoods,id',
            'postal_code_id'    => 'integer|exists:postal_codes,id',
            'land_phone'        => 'max:16|unique:dealers,land_phone,'.$id, // id
            'mobile_phone'      => 'max:16|unique:dealers,land_phone,'.$id, // id
            'web'               => 'max:255|active_url'
        ];
    }
}
