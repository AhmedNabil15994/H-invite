<?php

namespace Modules\Contact\Repositories\Api;

use Illuminate\Support\Facades\DB;
use Modules\Contact\Entities\Contact;
use Modules\Core\Repositories\Dashboard\CrudRepository;

class ContactRepository extends CrudRepository
{

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public function getAllActive($order = 'id', $sort = 'desc',$request=null)
    {
        $record = $this->model->active();

        if(isset($request->search) && !empty($request->search)){
            $record = $record->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('mobile', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if(isset($request->party_id) && !empty($request->party_id)){
            $record = $record->whereHas('invitees_contacts',function ($q) use ($request) {
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id())->whereHas('invitations',function ($q) use ($request){
                    $q->where('party_id',$request->party_id);
                });
            })->withCount(['invitations' => function ($q) use ($request) {
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id())->where('party_id',$request->party_id);
            }]);
        }

        $record = $record->orderBy($order, $sort)->get();
        return $record;
    }

    public function findById($id)
    {
        if (method_exists($this->model, 'trashed')) {
            $model = $this->model->withDeleted()->where('id',$id);
        } else {
            $model = $this->model->where('id',$id);
        }

        if(request()->party_id){
            $model = $model->withCount(['invitations' => function ($q) {
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id())->where('party_id',request()->party_id);
            }]);
        }

        return $model->first();
    }
}
