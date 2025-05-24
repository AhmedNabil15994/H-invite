<?php

namespace Modules\Party\Http\Controllers\Api;

use App\Jobs\SendInvitations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\Api\ApiController;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\Party;
use Modules\Party\Http\Requests\Api\InvitationRequest;
use Modules\Party\Repositories\Api\InvitationRepository;
use Modules\Party\Transformers\Api\InvitationResource;


class InvitationController extends ApiController
{
    public function __construct(InvitationRepository $invitation)
    {
        $this->invitation = $invitation;
    }

    public function statistics(Request $request)
    {
        $invitee_id = auth('sanctum')->id();
        $party =  Party::find($request->party_id);
        $invitee = $party->invitees()->where('parties_invitees.invitee_id',$invitee_id)->first();
        $pivotCount = $invitee?->pivot?->count ?? 0;
        $invitationsCount = $party->invitations()->whereHas('inviteeContact',function ($q) use ($invitee_id){
            $q->where('invitees_contacts.invitee_id',$invitee_id);
        })->count() ?? 0;

        return $this->response( [
            'package_limit'       => $pivotCount,
            'remaining_invitations' => $invitationsCount >= $pivotCount ? 0 : abs($pivotCount - $invitationsCount ),
            'accepted'  => $this->invitation->getInvitationsCount($request,null)->count() ?? 0,
            'rejected'  => $this->invitation->getInvitationsCount($request,3)->count() ?? 0,
            'attended'  => $this->invitation->getInvitationsCount($request,1)->count() ?? 0,
            'pending'   => $this->invitation->getInvitationsCount($request,2)->count() ?? 0,
            'active'    => $this->invitation->getInvitationsCount($request,1)->count() ?? 0,
        ]);
    }

    public function accepted(Request $request) {
        $request['flag'] = 10;
        $invitations = $this->invitation->getInvitations($request,null)->paginate(15);
        return $this->responsePaginationWithData(InvitationResource::collection($invitations));
    }

    public function rejected(Request $request) {
        $request['flag'] = 3;
        $invitations = $this->invitation->getInvitations($request,$request['flag'])->paginate(15);
        return $this->responsePaginationWithData(InvitationResource::collection($invitations));
    }

    public function attended(Request $request) {
        $request['flag'] = 1;
        $invitations = $this->invitation->getInvitations($request,$request['flag'])->paginate(15);
        return $this->responsePaginationWithData(InvitationResource::collection($invitations));
    }

    public function pending(Request $request) {
        $request['flag'] = 2;
        $invitations = $this->invitation->getInvitations($request,$request['flag'])->paginate(15);
        return $this->responsePaginationWithData(InvitationResource::collection($invitations));
    }

    public function active(Request $request) {
        $request['flag'] = 1;
        $invitations = $this->invitation->getInvitations($request,$request['flag'])->paginate(15);
        return $this->responsePaginationWithData(InvitationResource::collection($invitations));
    }

    public function send_message(InvitationRequest $request)
    {
        $partyObj = Party::find($request->party_id);
        $nextStep = $partyObj->next_step ?? 1;
        $on = 0;

        if($partyObj->invitations()->where('step',$nextStep)->count()){
            if($nextStep > 1){
                $count = $partyObj->invitations()->where('sent_at',null)->where('step','<',$nextStep)->count();
                $on = $count * 30;
            }
            $partyObj->update(['next_step'=>$nextStep+1]);
        }

        dispatch((new SendInvitations($request->party_id,auth('sanctum')->id(),$nextStep))->onConnection('invitations'))->delay(Carbon::now()->addSeconds($on));
        return $this->response( [] , 'Invitations are going to be sent in '.($on ? round($on/60) : 2).' minutes !!');
    }
}
