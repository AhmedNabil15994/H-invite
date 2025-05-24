<?php

namespace Modules\Party\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class PartyRequest extends FormRequest
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
                    'title.ar' => 'required',
                    'invitee_id' => 'required|exists:users,id',
                    'package_id' => 'required|exists:packages,id',
                    'description.*' => 'nullable',
                    'start_at' => 'nullable',
                    'expired_at' => 'nullable',
                    'image'    => 'required',
                    'city_id'    => 'nullable|exists:cities,id',
                    'state_id'    => 'nullable|exists:states,id',
                    'lat'    => 'nullable',
                    'lng'    => 'nullable',
                    'address'  => 'nullable',
                    'address_link'  => 'nullable',
                    'whatsapp_msg.ar'  => 'required',
                    'acceptance_reply.ar'  => 'nullable',
                    'rejection_reply.ar'  => 'nullable',
                    'reminder_msg.ar'  => 'required',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'title.ar' => 'required',
                    'invitee_id' => 'required|exists:users,id',
                    'package_id' => 'required|exists:packages,id',
                    'description.*' => 'nullable',
                    'start_at' => 'nullable',
                    'expired_at' => 'nullable',
                    'city_id'    => 'nullable|exists:cities,id',
                    'state_id'    => 'nullable|exists:states,id',
                    'lat'    => 'nullable',
                    'lng'    => 'nullable',
                    'address'  => 'nullable',
                    'address_link'  => 'nullable',
                    'whatsapp_msg.ar'  => 'required',
                    'acceptance_reply.ar'  => 'nullable',
                    'rejection_reply.ar'  => 'nullable',
                    'reminder_msg.ar'  => 'required',
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
