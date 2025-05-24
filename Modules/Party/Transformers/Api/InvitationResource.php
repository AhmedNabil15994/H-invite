<?php

namespace Modules\Party\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Modules\Party\Entities\Invitation;
use Modules\Party\Repositories\Api\InvitationRepository;

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
        $invitations = (new InvitationRepository(new Invitation()))->getInvitationsCount($request , $request['flag'] == 10 ? null : $request['flag'])->whereContactId($this->contact_id)->count() ?? 0;

        $base = [
            'id'            => $this->id,
            'code'        => $this->code,
            'qr'            =>  URL::to('/uploads/qr/'.$this->code.'.png') ,
            'contact'        => $this?->inviteeContact?->contact?->display_name,
            'mobile'         => $this?->inviteeContact?->contact?->mobile,
            'invitee' => $this->party?->invitees()->pluck('name'),
            'party' => $this->party?->title,
            'should_attend_at' => $this->party?->start_at,
            'invitations' => $invitations,//(count($this->children_invitations) ?? 0) + 1,
            'status'        => strip_tags($this->getStatus($request['flag'])),
            'scanned_at'    => null,
            'attended_at'    => null,
            'related_invitation'    =>  null,//count($this->children_invitations) ? self::collection($this->children_invitations) : null,
        ];

        $extra = [];
        if($this->status == 1){
            $extra['scanned_at'] = $this->scanned_at;
        }else if($this->status == 4){
            $extra['attended_at'] = $this->attended_at;
        }

        return array_merge($base,$extra);
    }
}
