<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory;

use App\Http\Requests\Request;
use Sentinel;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.dealer_category.store')) {
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
        return [
            'name'              => 'required|max:255',
            'show_address'      => 'required',
            'show_province'     => 'required',
            'show_county'       => 'required',
            'show_district'     => 'required',
            'show_neighborhood' => 'required',
            'show_postal_code'  => 'required',
            'show_land_phone'   => 'required',
            'show_mobile_phone' => 'required',
            'show_url'          => 'required',
            'parent'            => 'integer'
        ];
    }
}
