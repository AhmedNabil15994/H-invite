<?php

namespace Modules\Contact\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'name'=> $this->invitees_contacts()->where('invitee_id',auth()->id())->first()?->display_name ?? $this->name,
            'email'=> $this->email,
            'mobile'=> $this->mobile,
            'max_invitations'=> $this->invitations_count ?? $this->max_invitations,
            'status' => $this->status ,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
