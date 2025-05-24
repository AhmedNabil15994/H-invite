<?php

namespace Modules\User\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\Api\CompanyResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'first_login'          => $this->first_login == 1 ? true : false,
            'name'          => $this->name,
            'email'         => $this->email,
            'mobile'        => $this->mobile,
       ];
    }
}
