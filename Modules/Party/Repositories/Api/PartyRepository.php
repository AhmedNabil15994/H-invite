<?php

namespace Modules\Party\Repositories\Api;

use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Party\Entities\Party;

class PartyRepository extends CrudRepository
{

    public function __construct(Party $model)
    {
        parent::__construct(Party::class);
        $this->model = $model;
    }

    public function getAllActive($order = 'id', $sort = 'desc',$request=null)
    {
        $record = $this->model->active()->whereHas('invitees',function ($q){
            $q->where('invitee_id',auth('sanctum')->id());
        })->orderBy($order, $sort)->get();
        return $record;
    }

    public function findById($id)
    {
        $model = $this->model->whereHas('invitees',function ($q){
            $q->where('invitee_id',auth('sanctum')->id());
        });
        if (method_exists($this->model, 'trashed')) {
            $model = $model->withDeleted()->find($id);
        } else {
            $model = $model->find($id);
        }

        return $model;
    }
}
