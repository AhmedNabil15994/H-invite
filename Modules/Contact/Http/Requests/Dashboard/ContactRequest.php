<?php

namespace Modules\Contact\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'mobile' => 'required|unique:contacts,mobile,'.$this->id,
            'email' => 'required|email|unique:contacts,email,'.$this->id,
//            'max_invitations'   => 'required|integer|gt:0'
        ];
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
}
