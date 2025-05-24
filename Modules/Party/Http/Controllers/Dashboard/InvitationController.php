<?php

namespace Modules\Party\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\City;
use Modules\Area\Entities\State;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\Core\Traits\DataTable;
use Modules\Coupon\Http\Requests\Dashboard\CouponRequest;
use Modules\Coupon\Repositories\CouponRepository;
use Modules\Coupon\Transformers\Dashboard\CouponResource;
use Modules\Order\Entities\OrderItem;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\Party;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class InvitationController extends Controller
{
    use CrudDashboardController;

    public function extraData($model): array
    {
        return [
            'model' => $model,
            'parties'   => Party::active()->latest('id')->get(),
            'related_invitation'  =>  Invitation::where([ ['id','!=',$model->id] , ['related_invitation_id',null]  ])->get(),
            'invitees' => User::doesntHave('roles.permissions')->get(),
            'cities' => City::active()->get(),
            'states' => State::active()->get(),
        ];
    }

    public function rejected_invitations()
    {
        return view('party::dashboard.invitations.rejected');
    }

    public function attended_invitations()
    {
        return view('party::dashboard.invitations.attended');
    }

    public function pending_invitations()
    {
        return view('party::dashboard.invitations.pending');
    }

    public function active_invitations()
    {
        return view('party::dashboard.invitations.active');
    }

    public function deleteMediaFiles(Request $request) {
        Media::whereIn('id',$request->id)->delete();
        return response()->json([true,__('apps::dashboard.messages.deleted')]);
    }

    public function redeem($code){
        $invitation = $this->repository->getByCode($code);
        if(!$invitation){
            return response()->json([
                'success' => false,
                'message' => __('apps::dashboard.messages.scanned_before'),
            ], 400);
        }
        $invitation->status = 1;
        $invitation->scanned_at = date('Y-m-d H:i:s');
        $invitation->save();

        return response()->json([
            'success' => true,
            'data' => [
                'remaining_invitations_count' => Invitation::where('code',$code)->whereNotIn('status',[1,3])->count(),
            ],
            'message' => __('apps::dashboard.messages.scanned'),
        ], 200);
    }
}
