<?php

namespace Modules\Contact\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AttachRelatedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'party_id'  => 'required|exists:parties,id',
            'contact_id'  => 'required|exists:contacts,id',
            'invitations' => 'required|numeric|gte:1',
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
