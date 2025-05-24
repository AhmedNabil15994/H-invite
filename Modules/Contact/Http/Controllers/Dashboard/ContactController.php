<?php

namespace Modules\Contact\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contact\Transformers\Api\ContactResource;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\User\Entities\User;

class ContactController extends Controller
{

    use CrudDashboardController;

    public function extraData($model): array
    {
        return [
            'model' => $model,
            'invitees' => User::doesntHave('roles.permissions')->get(),
        ];
    }
    public function import()
    {
        $model = new $this->model;
        $invitees = $this->extraData($model)['invitees'];
        return view('contact::dashboard.contacts.import',compact('model','invitees'));
    }

    public function import_file(Request $request)
    {
        return $this->repository->dealWithExcel($request);
    }

    public function inviteesContacts(Request $request)
    {
        $ids = $request->ids;
        $data = $this->model->whereHas('invitees',function ($q) use ($ids){
            $q->whereIn('invitee_id',$ids);
        })->orderBy('id','desc')->get();

        return response()->json([
            'success'   => true,
            'contacts'  => ContactResource::collection($data),
        ]);
    }
}
