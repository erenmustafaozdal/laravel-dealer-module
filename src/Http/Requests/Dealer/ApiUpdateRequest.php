<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer;

use App\Http\Requests\Request;
use Sentinel;

class ApiUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('api.dealer.update')) {
            return true;
        }
        return false;
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
            'name'              => 'required|max:255',
            'address'           => 'max:255',
            'province_id'       => 'required|integer|exists:provinces,id',
            'county_id'         => 'required|integer|exists:counties,id',
            'district_id'       => 'required|integer|exists:districts,id',
            'neighborhood_id'   => 'required|integer|exists:neighborhoods,id',
            'postal_code_id'    => 'required|integer|exists:postal_codes,id',
            'land_phone'        => 'required|max:16|unique:dealers,land_phone,'.$id, // id
            'mobile_phone'      => 'required|max:16|unique:dealers,land_phone,'.$id, // id
            'web'               => 'max:255|active_url'
        ];
    }
}
