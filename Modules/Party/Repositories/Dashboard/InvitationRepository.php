<?php

namespace Modules\Party\Repositories\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Party\Entities\Invitation;

class InvitationRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(Invitation::class);
//        $this->statusAttribute = ['status',];
    }

    public function QueryTable($request)
    {
        $query = $this->model->where(function ($q) use ($request){
            if (isset($request['search']['value']) && !empty($request['search']['value'])) {
                $q->where(DB::raw('lower(title)'),'LIKE','%'.strtolower($request['search']['value']).'%')
                    ->orWhere(DB::raw('lower(discount_desc)'),'LIKE','%'.strtolower($request['search']['value']).'%');
            }

            if (isset($request['status']) && !empty($request['status'])) {
                $q->whereStatus($request['status']);
            }

            if(isset($request->party_id) && !empty($request->party_id)){
                $q->where('party_id',$request->party_id);
            }
        });

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

    public function prepareData(array $data, Request $request, $is_create = true): array
    {
        $data['code'] = strtoupper(\Str::random(10));
        $data['status'] = $request->inv_status;
        $data['invitation_number'] = $this->model->where('party_id',$request->party_id)->latest('invitation_number')->first()?->invitation_number+1;
        unset($data['inv_status']);
        return $data;
    }

    public function getByCode($code){
        return $this->model->where([
            ['code',$code],
            ['scanned_at',null]
        ])->whereNotIn('status',[1,3])->first();
    }

    public function getInvitationsCount($request,$flag=null)
    {

        $record = $this->model;
        if(isset($request->party_id) && !empty($request->party_id)){
            $record = $record->wherePartyId($request->party_id);
        }

        if($flag){
            $record = $record->whereStatus($flag);
        }else{
            $record = $record->whereNotIn('status',[2,3]);
        }

        return $record->latest('id');
    }

//    public function modelUpdated($model, $request): void
//    {
//        $model->invitees()->sync($request->invitee_id);
//    }
//    public function modelCreated($model, $request, $is_created = true): void
//    {
//        $model->invitees()->sync($request->invitee_id);
//    }
}
