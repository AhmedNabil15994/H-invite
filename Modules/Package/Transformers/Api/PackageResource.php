<?php

namespace Modules\Package\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description'=> $this->description,
            'price'=> number_format($this->price,3),
            'order'=> $this->order,
            'invitations_limit'=> $this->invitations_limit,
            'image' => $this->getFirstMediaUrl('images'),
        ];
    }
}
