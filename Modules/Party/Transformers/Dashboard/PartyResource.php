<?php

namespace Modules\Party\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class PartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'        => $this->title,
            'description'        => $this->description,
            'invitee' => $this->invitees()->pluck('name'),
            'state'        => $this->state?->title ?? '',
            'city'        => $this->city?->title ?? '',
            'image' => $this->image,
            'status'        => $this->status,
            'start_at'        => date('d-m-Y' , strtotime($this->start_at)),
            'package'       => $this->package?->title,
            'expired_at'        => date('d-m-Y' , strtotime($this->expired_at)),
            'deleted_at'    => $this->deleted_at,
            'created_at'    => date('d-m-Y' , strtotime($this->created_at)),
       ];
    }
}
