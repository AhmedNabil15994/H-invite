<?php

namespace Modules\Party\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
            'invitation_number'        => $this->invitation_number,
            'code'        => $this->code,
            'contact'        => validatePhone($this?->inviteeContact->contact?->mobile),
            'invitee' => $this->party?->invitees()->pluck('name'),
            'party' => $this->party?->title,
            'invitations' => $this->invitations,
            'related_invitation'        => $this->related_invitation?->code,
            'status'        => $this->getStatus(),
            'attended_at'    => $this->attended_at,
            'scanned_at'    => $this->scanned_at,
            'deleted_at'    => $this->deleted_at,
            'created_at'    => date('d-m-Y' , strtotime($this->created_at)),
       ];
    }
}
