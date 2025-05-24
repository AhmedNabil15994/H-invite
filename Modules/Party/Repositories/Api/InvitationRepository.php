<?php

namespace Modules\Party\Repositories\Api;

use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Party\Entities\Invitation;

class InvitationRepository extends CrudRepository
{

    public function __construct(Invitation $model)
    {
        $this->model = $model;
    }

    public function getAllActive($order = 'id', $sort = 'desc',$request=null)
    {
        $record = $this->model;
        if(isset($request->party_id) && !empty($request->party_id)){

        }
        $record = $record->orderBy($order, $sort)->get();
        return $record;
    }

    public function getInvitationsCount($request,$flag=null)
    {
        $invitee_id = auth('sanctum')->id();
        $record = $this->model;
        if(isset($request->party_id) && !empty($request->party_id)){
            $record = $record->wherePartyId($request->party_id);
        }

        if($flag){
            $record = $record->whereStatus($flag);
        }else{
            $record = $record->whereNotIn('status',[2,3]);
        }

        $record = $record->whereHas('inviteeContact',function ($q) use ($invitee_id){
            $q->where('invitees_contacts.invitee_id',$invitee_id);
        });

        return $record->latest('id');
    }

    public function getInvitations($request,$flag=null)
    {
        $record = $this->model->with(['children_invitations' => function ($q) use ($flag) {
            if($flag){
                $q->whereStatus($flag);
            }else{
                $q->whereNotIn('status',[2,3]);
            }
        }]);

        if(isset($request->party_id) && !empty($request->party_id)){
            $record = $record->wherePartyId($request->party_id);
        }

        if($flag){
            $record = $record->whereStatus($flag);
        }else{
            $record = $record->whereNotIn('status',[2,3]);
        }

        $invitee_id = auth('sanctum')->id();
        $record = $record->whereHas('inviteeContact',function ($q) use ($invitee_id){
            $q->where('invitees_contacts.invitee_id',$invitee_id);
        });

        return $record->withCount([
            'children_invitations AS invitations_count' => function ($query) {
                $query->select(\DB::raw("count(id) as invitations_count"));
            }
        ])->groupBy('contact_id','party_id')->orderBy('id','asc');
    }
}
