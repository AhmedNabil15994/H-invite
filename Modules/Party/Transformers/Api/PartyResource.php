<?php

namespace Modules\Party\Transformers\Api;

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
        $invitee_id = auth('sanctum')->id();
        $invitee = $this->invitees()->where('parties_invitees.invitee_id',$invitee_id)->first();
        $pivotCount = $invitee?->pivot?->count ?? 0;
        $invitationsCount = $this->invitations()->whereHas('inviteeContact',function ($q) use ($invitee_id){
            $q->where('invitees_contacts.invitee_id',$invitee_id);
        })->count() ?? 0;

        return  [
            'id'            => $this->id,
            'title'        => $this->title,
            'description'        => $this->description,
            'invitee' => $this->invitees()->pluck('name'),
            'address'        => $this->address ?? '',
            'address_link'        => $this->address_link ?? '',
            'lat'        => $this->lat ?? '',
            'lng'        => $this->lng ?? '',
            'state'        => $this->state?->title ?? '',
            'city'        => $this->city?->title ?? '',
            'package'       => $this->package?->title,
            'invitations_limit'       => $pivotCount,
            'remaining_invitations' => $invitationsCount >= $pivotCount ? 0 : abs($pivotCount - $invitationsCount ),
            'image' => $this->image,
            'status'        => $this->status,
            'start_at'        => $this->start_at,
            'expired_at'        => $this->expired_at,
            'created_at'    => date('d-m-Y' , strtotime($this->created_at)),
        ];
    }
}
