<?php

namespace Modules\Party\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\Area\Entities\City;
use Modules\Area\Entities\State;
use Modules\Contact\Entities\Contact;
use Modules\Contact\Repositories\Dashboard\ContactRepository;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\Core\Traits\DataTable;
use Modules\Coupon\Http\Requests\Dashboard\CouponRequest;
use Modules\Coupon\Repositories\CouponRepository;
use Modules\Coupon\Transformers\Dashboard\CouponResource;
use Modules\Package\Entities\Package;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\InviteeContact;
use Modules\Party\Entities\Party;
use Modules\Party\Repositories\Dashboard\InvitationRepository;
use Modules\Party\Repositories\Dashboard\PartyRepository;
use Modules\Party\Transformers\Api\PartyResource;
use Modules\User\Entities\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PartyController extends Controller
{
    use CrudDashboardController;

    use CrudDashboardController {
        CrudDashboardController::__construct as private __tConstruct;
    }

    public function __construct(PartyRepository $repository, InvitationRepository $invitation)
    {

        $this->__tConstruct();
        $this->repository = $repository;
        $this->invitation = $invitation;
    }

    public function extraData($model): array
    {
        return [
            'model' => $model,
            'invitees' => User::doesntHave('roles.permissions')->get(),
            'cities' => City::active()->get(),
            'states' => State::active()->get(),
            'packages' => Package::active()->latest('order')->get(),
        ];
    }
    public function edit($id)
    {
        $model = $this->repository->findById($id);
        $qrHeight = 100;
        $qrWidth = 100;
        $code = '123123123';
        $invitationNumber = '123123123';
        if($model->invitations()->first()){
            $image_2 = imagecreatefrompng(public_path().'/uploads/qr/'.$model->invitations()->first()?->code.'.png');
            $code = $model->invitations()->first()?->code;
            $qrHeight = imagesy($image_2);
            $qrWidth  = imagesx($image_2);
            $invitationNumber = $model->invitations()->first()?->invitation_number;
        }else{
            generateQrCode('123123123');
            $image_2 = imagecreatefrompng(public_path().'/uploads/qr/123123123.png');
        }

        $newImage = convertPdfToJpeg($model->getFirstMediaPath('images'),$model);
        $image_1 = imagecreatefromstring((file_get_contents($newImage['path'])));

        $invitationHeight = 880;
        $invitationWidth = 560;
        $modelDimensions = $model?->dimensions ? (array) json_decode($model?->dimensions) : [];
        $dimensions = [
            'qrHeight'  => $modelDimensions['qr_height'] ?? $qrHeight,
            'qrWidth'  => $modelDimensions['qr_width'] ?? $qrWidth,
            'invitationWidth'  => $modelDimensions['invitation_width'] ?? $invitationWidth,
            'invitationHeight'  => $modelDimensions['invitation_height'] ?? $invitationHeight,
            'background'        => isset($modelDimensions['background']) && $modelDimensions['background']  == 'on' ? 1:0,
            'distX' => $modelDimensions['dist_x'] ?? (($invitationWidth - $qrWidth) / 2) - 60,
            'distY' => $modelDimensions['dist_y'] ?? $invitationHeight - $qrHeight - 60,
            'distX2' => $modelDimensions['dist_x2'] ?? (($invitationWidth - $qrWidth) / 2) + 75,
            'distY2' => $modelDimensions['dist_y2'] ?? $invitationHeight - $qrHeight - 40,
            'qr'    => \URL::to('/uploads/qr/'.$code.'.png'),
            'code'  => $code,
            'invitationNumber'  => $invitationNumber,
            'image' =>  $newImage['image'],
            'height'    => $newImage['height'],
            'width'     => $newImage['width']
        ];

        $data =  $this->extraData($model);
        $data['dimensions'] = $dimensions;

        return view('party::dashboard.parties.edit', $data);
    }

    public function deleteMediaFiles(Request $request) {
        Media::whereIn('id',$request->id)->delete();
        return response()->json([true,__('apps::dashboard.messages.deleted')]);
    }

    public function getContacts(Request $request){
        $party = $this->model->active()->where('id',$request->party_id)->first();
        $invitees = $party?->invitees;
        $data = [];
        if($request->invitee_id){
            $data = InviteeContact::with('contact')->where('invitee_id',$request->invitee_id)->get();
        }

        return response()->json([
            'contacts' => $data,
            'invitees'  => $invitees,
            'party_card' => view('party::dashboard.parties.components.party-card',compact('party'))->render(),
        ]);
    }

    public function getByInvitee(Request $request)
    {
        $parties = $this->model->active()->whereHas('invitees',function ($q){
            $q->whereIn('invitee_id',request()->ids ?? []);
        })->get();
        return response()->json([
            'parties' => PartyResource::collection($parties),
        ]);
    }

    public function print($id,Request $request)
    {
        $party = $this->model->where('id',$id)->first();
        $request['party_id'] = $id;
        $data = [
            'party'  => $party,
            'package_limit' => $party?->package?->invitations_limit ?? 0,
            'remaining_invitations' => ($party?->package?->invitations_limit ?? 0) - ( $party->invitations()->count() ?? 0),
            'accepted'  => $this->invitation->getInvitationsCount($request,null)->count() ?? 0,
            'rejected'  => $this->invitation->getInvitationsCount($request,3)->count() ?? 0,
            'attended'  => $this->invitation->getInvitationsCount($request,1)->count() ?? 0,
            'pending'   => $this->invitation->getInvitationsCount($request,2)->count() ?? 0,
            'active'    => $this->invitation->getInvitationsCount($request,1)->count() ?? 0,
        ];
        return view('party::dashboard.parties.components.party_statistics',compact('data'));
    }

    public function sendStatistics($id,Request $request)
    {
        $party = $this->model->where('id',$id)->first();
        $request['party_id'] = $id;

        foreach($party->invitees as $invitee){
            $pivotCount = $invitee?->pivot?->count ?? 0;
            $invitationsCount = $party->invitations()->whereHas('inviteeContact',function ($q) use ($invitee){
                $q->where('invitees_contacts.invitee_id',$invitee->id);
            })->count() ?? 0;

            $data = [
                'party'  => $party,
                'name'   => $invitee->name,
                'package_limit' => $pivotCount,
                'remaining_invitations' => $invitationsCount >= $pivotCount ? 0 : abs($pivotCount - $invitationsCount),
                'accepted'  => $this->invitation->getInvitationsCount($request,null)->whereHas('inviteeContact',function ($q) use ($invitee){
                        $q->where('invitees_contacts.invitee_id',$invitee->id);
                    })->count() ?? 0,
                'rejected'  => $this->invitation->getInvitationsCount($request,3)->whereHas('inviteeContact',function ($q) use ($invitee){
                        $q->where('invitees_contacts.invitee_id',$invitee->id);
                    })->count() ?? 0,
                'attended'  => $this->invitation->getInvitationsCount($request,1)->whereHas('inviteeContact',function ($q) use ($invitee){
                        $q->where('invitees_contacts.invitee_id',$invitee->id);
                    })->count() ?? 0,
                'pending'   => $this->invitation->getInvitationsCount($request,2)->whereHas('inviteeContact',function ($q) use ($invitee){
                        $q->where('invitees_contacts.invitee_id',$invitee->id);
                    })->count() ?? 0,
                'active'    => $this->invitation->getInvitationsCount($request,1)->whereHas('inviteeContact',function ($q) use ($invitee){
                        $q->where('invitees_contacts.invitee_id',$invitee->id);
                    })->count() ?? 0,
                'invitee_id'    => $invitee->id,
            ];

            $pdf = \PDF::loadView('party::dashboard.parties.components.invitee_party_statistics', compact('data'));
            $fileName = 'party_'.$id.'inviteeStatistics_'.$invitee->id.'.pdf';
            $filePath = public_path('/uploads/party_invitees/'. $fileName) ;
            if(file_exists($filePath)){
                unlink($filePath);
            }
            $pdf->save($filePath);
            $result = \Http::withHeaders([
                'ID'    => env('GOWASL_CHANNEL_ID'),
                'TOKEN' => env('GOWASL_CHANNEL_TOKEN'),
                'Authorization' => 'Bearer '.env('GOWASL_CHANNEL_IDENTIFIER'),
                'Accept'  => 'application/json'
            ])->post(env('GOWASL_API_URL').'/channels/messages/sendFile',[
                'url'   => \URL::to('/uploads/party_invitees/'.$fileName),
                'phone' => validatePhone(strlen($invitee->mobile) == 8 ? '965'.$invitee->mobile : $invitee->mobile),
            ]);
        }

        return redirect()->back()->with(['msg'=> 'جاري ارسال الاحصائيات للداعيين عبر الواتساب']);
    }

    public function actions($party_id,$decryptedContactId)
    {
        $vector = 34567;
        $contactId = $decryptedContactId / $vector;
        $party = $this->repository->findById($party_id);
        if(!is_int($contactId) || !$party){
            abort(404);
        }

        $contactInvitations = $party->invitations()->where('contact_id',$contactId)->orderBy('id','asc')->get();

        if(!$contactInvitations || $party->expired_at < date('Y-m-d')){
            return redirect()->away('https://h-invite.com/');
        }

        $invitations = $party->invitations()->where('status',2)->where('contact_id',$contactId)->orderBy('id','asc')->get();
        if(!count($invitations)){
            $count = $party->invitations()->where('contact_id',$contactId)->whereIn('status',[1,4])->count();
            $inviteeContact = InviteeContact::find($contactId);
            $invitee_id = $inviteeContact?->invitee_id ?? null;
            return view('party::dashboard.parties.components.accepted',compact('party','count','invitee_id','inviteeContact'));
        }

        return view('party::dashboard.parties.components.actions',compact('invitations','party'));
    }

    public function postActions($party_id,$decryptedContactId,Request $request)
    {
        App::setLocale('ar');
        $contactId = $decryptedContactId / 34567;
        $party = $this->repository->findById($party_id);
        if(!is_int($contactId) || !$party){
            abort(404);
        }

        foreach ($request->actions as $invitationId => $action){
            $invitation = $this->invitation->findById($invitationId);
            $requestData = [
                'phone' => strlen($invitation?->inviteeContact?->contact?->mobile) == 8 ? '965'.$invitation?->inviteeContact?->contact?->mobile : $invitation?->inviteeContact?->contact?->mobile,
            ];
            if($action){
                $status = 4;
                $route = 'sendMessage';
                $requestData['body'] = ($invitation->party->acceptance_reply ?? ''); //. " \r\n \r\n " . __('party::dashboard.parties.form.invitations_count',['number' => 1]);
//                $requestData['url'] = str_replace('http://127.0.0.1:8000','https://41d3-197-40-168-23.ngrok-free.app',$requestData['url']);
            }else{
                $status = 3;
                $route = 'sendMessage';
                $requestData['body'] = ($invitation->party->rejection_reply ?? '');// . " \r\n \r\n " . __('party::dashboard.parties.form.invitations_count',['number' => 1]);
            }

            $updateData = [
                'status' => $status,
                'attended_at'   => $status == 4 ? date('Y-m-d H:i:s') : null ,
            ];

            if($requestData['body']){
//                $result = \Http::timeout(0)->withHeaders([
//                    'ID'    => env('GOWASL_CHANNEL_ID'),
//                    'TOKEN' => env('GOWASL_CHANNEL_TOKEN'),
//                    'Authorization' => 'Bearer '.env('GOWASL_CHANNEL_IDENTIFIER'),
//                    'Accept'  => 'application/json',
//                ])->post(env('GOWASL_API_URL').'/channels/messages/'.$route,$requestData);
//
//                $result = (array) $result?->json() ?? [];

            }
            $invitation->update($updateData);
        }

        $count = $party->invitations()->where('contact_id',$contactId)->whereIn('status',[1,4])->count();
        $inviteeContact = InviteeContact::find($contactId);
        $invitee_id = $inviteeContact?->invitee_id ?? null;
        return view('party::dashboard.parties.components.accepted',compact('party','inviteeContact','count','invitee_id'));
    }

    public function download($party_id,$decryptedContactId,Request $request)
    {
        $vector = 34567;
        $contactId = $decryptedContactId / $vector;
        $party = $this->repository->findById($party_id);
        if(!is_int($contactId) || !$party){
            abort(404);
        }

        $invitations = $party->invitations()->where('status',2)->where('contact_id',$contactId)->orderBy('id','asc')->get();
        if(!count($invitations)){
            abort(404);
        }

        $files = [];
        foreach ($party->invitations()->where('status','!=',2)->where('contact_id',$contactId)->orderBy('id','asc')->get() as $invitation){
            $file = public_path('/uploads/parties/party-'.$party_id.'/'.$invitation->invitation_number.'.pdf');
            if(file_exists($file)){
                $files[] = $file;
            }
        }

        $zipname = public_path('/uploads/parties/party-'.$party_id.'/'.'party-'.$party_id.'-invitations-'.$contactId.'.zip');
        if(file_exists($zipname)){
            unlink($zipname);
        }

        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file);
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        return readfile($zipname);
    }

}
