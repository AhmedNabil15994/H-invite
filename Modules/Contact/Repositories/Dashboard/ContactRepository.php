<?php

namespace Modules\Contact\Repositories\Dashboard;

use App\Imports\ContactImport;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contact\Entities\Contact;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Party\Entities\Party;
use Modules\User\Entities\User;

class ContactRepository extends CrudRepository
{

    public function QueryTable($request)
    {
        $query = $this->model;

        if(isset($request->party_id) && !empty($request->party_id)){
            $query = $query->whereHas('invitations',function ($q) use ($request){
                $q->where('party_id',$request->party_id);
            })->withCount('invitations');
        }

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with') {
            $query->withDeleted();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '1') {
            $query->active();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->unactive();
        }

        return $query;
    }

    public function dealWithExcel($request)
    {
        if($request->hasFile('excel_file')){
            $rows = Excel::toArray(new ContactImport(), $request->file('excel_file'));
            $data = array_filter(array_slice($rows[0], 1, 100) , function ($subArray) {
                return $subArray[0] !== null;
            });

            collect($data)->map(function ($item) use ($request){
                $contact = Contact::firstOrCreate([
                    'mobile'  => $item[1],
                ],[
//                    'name'  => $item[0],
                    'mobile'  => $item[1],
                    'max_invitations'  => $item[2],
                ]);
                $contact->invitees()->syncWithPivotValues($request->invitee_id,[
                    'display_name'  => $item[0],
                ]);
            });


            return Response()->json([true, __('apps::dashboard.messages.created')]);
        }else{
            return Response()->json([false, __('apps::dashboard.messages.failed')]);
        }
    }

    public function modelUpdated($model, $request): void
    {
        $model->invitees()->syncWithPivotValues($request->invitee_id,[
            'display_name'  => $request->name,
        ]);

//        if(isset($request->party_id) && !empty($request->party_id)){
//            foreach ($request->party_id as $party_id) {
//                $invitationObj = $model->invitations()->where('party_id',$party_id)->first();
//                if($invitationObj){
//                    $model->invitations()->where('party_id',$party_id)->delete();
//                }
//                $partyObj = Party::find($party_id);
//                if($partyObj){
//                    $start = $partyObj->invitations()->orderBy('id','desc')->first()?->invitation_number+1 ?? $partyObj->sort_start;
//                    $related = null;
//                    for ($i = 0; $i < ($model->max_invitations ?? 1); $i++) {
//                        $newObj = $model->invitations()->create([
//                            'invitation_number' => $start,
//                            'code'  => strtoupper(\Str::random(10)),
//                            'status'    => 2,
//                            'party_id'  => $party_id,
//                            'contact_id'    => $model->id,
//                            'invitations'   => 1,
//                            'related_invitation_id' => $related,
//                        ]);
//                        if($i==0){
//                            $related = $newObj->id;
//                        }
//                        $start++;
//                    }
//                }
//            }
//        }
    }

    public function modelCreated($model, $request, $is_created = true): void
    {
        $model->invitees()->syncWithPivotValues($request->invitee_id,[
            'display_name'  => $request->name,
        ]);

        if(isset($request->party_id) && !empty($request->party_id)){
            foreach ($request->party_id as $party_id) {
                $partyObj = Party::find($party_id);
                if($partyObj){
                    $start = $partyObj->invitations()->orderBy('id','desc')->first()?->invitation_number+1 ?? $partyObj->sort_start;
                    $related = null;
                    for ($i = 0; $i < ($model->max_invitations ?? 1); $i++) {
                        $newObj = $model->invitations()->create([
                            'invitation_number' => $start,
                            'code'  => strtoupper(\Str::random(10)),
                            'status'    => 2,
                            'party_id'  => $party_id,
                            'contact_id'    => $model->id,
                            'invitations'   => 1,
                            'related_invitation_id' => $related,
                        ]);
                        if($i==0){
                            $related = $newObj->id;
                        }
                        $start++;
                    }
                }
            }
        }
    }
}
