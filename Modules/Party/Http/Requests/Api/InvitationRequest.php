<?php

namespace Modules\Party\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            // handle creates
            case 'post':
            case 'POST':

                return [
                    'party_id' => 'required|exists:parties,id',
                ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $v = [
            'title.required' => __('coupon::dashboard.coupons.validation.title.required'),
            'discount_title.required' => __('coupon::dashboard.coupons.validation.discount_title.required'),
            'discount_desc.required' => __('coupon::dashboard.coupons.validation.discount_desc.required'),
            'price.required' => __('coupon::dashboard.coupons.validation.price.required'),
            'seller_id.required' => __('coupon::dashboard.coupons.validation.seller_id.required'),
            'category_id.required' => __('coupon::dashboard.coupons.validation.category_id.required'),
            'main_image.required' => __('coupon::dashboard.coupons.validation.main_image.required'),
            'city_id.required' => __('coupon::dashboard.coupons.validation.city_id.required'),
            'state_id.required' => __('coupon::dashboard.coupons.validation.state_id.required'),
            'lat.required' => __('coupon::dashboard.coupons.validation.lat.required'),
            'lng.required' => __('coupon::dashboard.coupons.validation.lng.required'),
        ];
        return $v;
    }
}
