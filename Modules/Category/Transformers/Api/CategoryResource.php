<?php

namespace Modules\Category\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $response = [
           'id'            => $this->id,
           'image'         => $this->getFirstMediaUrl('images'),
           'title'         => $this->title,
            'banner'         => $this->getFirstMediaUrl('mobile_banners') ?? '',
            'start_at'          => $this->start_at,
            'expired_at'        => $this->expired_at,
       ];

       if(is_null($this->category_id)){

            $response['children'] = CategoryResource::collection($this->children()->active()->get());
       }

        return $response;
    }
}
