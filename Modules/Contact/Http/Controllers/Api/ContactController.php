<?php

namespace Modules\Contact\Http\Controllers\Api;

use App\Imports\ContactImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Apps\Http\Controllers\Api\ApiController;
use Modules\Contact\Entities\Contact;
use Modules\Contact\Http\Requests\Api\AttachRelatedRequest;
use Modules\Contact\Http\Requests\Api\ContactRequest;
use Modules\Contact\Http\Requests\Api\PhoneContactsRequest;
use Modules\Contact\Http\Requests\Api\RemoveInvitationRequest;
use Modules\Contact\Http\Requests\Api\UploadExcelRequest;
use Modules\Contact\Repositories\Api\ContactRepository;
use Modules\Contact\Transformers\Api\ContactResource;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\InviteeContact;
use Modules\Party\Entities\Party;


class ContactController extends ApiController
{
    public function __construct(ContactRepository $contact)
    {
        $this->contact = $contact;
    }
    public function index(Request $request) {
        $contacts = $this->contact->getAllActive('id','desc',$request);
        return $this->responsePaginationWithData(ContactResource::collection($contacts));
    }

    public function show(Request $request,$id) {
        $contact   = $this->contact->findById($id);
        if(!$contact){
            return $this->error(__('party::api.invalid_party'));
        }

        return $this->response( (new ContactResource($contact))->jsonSerialize());
    }

    public function store(ContactRequest $request)
    {
        DB::beginTransaction();
        try{
            if(isset($request->party_id)){
                $party = Party::find($request->party_id);
                $invitee = $party->invitees()->where('invitee_id',auth('sanctum')->user()->id)->first();

                $invitations = Invitation::where('party_id',$request->party_id)->whereHas('inviteeContact',function ($q){
                    $q->where('invitees_contacts.invitee_id',auth('sanctum')->id());
                })->count();

                $maxPerInvitee = $invitee?->pivot?->count ?? 0;
                $item = $request->validated();

                $contact = Contact::firstOrCreate([
                    'mobile'  => $item['mobile'],
                ],[
//                    'name'  => $item['name'],
                    'mobile'  => $item['mobile'],
                    'max_invitations'  => $item['invitations'] ?? 1,
                ]);

                $inviteeContact = InviteeContact::firstOrCreate([
                    'contact_id' => $contact->id ,
                    'invitee_id' => auth('sanctum')->id()
                ],[
                    'display_name'  => $item['name'] ?? '',
                ]);

                $contact_id = $inviteeContact?->id;

                if($maxPerInvitee > $invitations){
                    for ($i = 0; $i < ($item['invitations'] ?? 1); $i++) {
                        Invitation::create([
                            'party_id' => $request->party_id,
                            'step'  => $party->next_step,
                            'contact_id'    => $contact_id,
                            'invitation_number' => Invitation::where('party_id',$request->party_id)->latest('invitation_number')->first()?->invitation_number+1,
                            'code'  => strtoupper(\Str::random(10))
                        ]);
                    }
                }else{
                    return $this->error(__('contact::api.limit_exceeded'));
                }
                $contact->update(['max_invitations'=>1]);
                DB::commit();
            }
            return $this->response(__('contact::api.contact_stored'));
        }catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function uploadExcel(UploadExcelRequest $request)
    {
        if($request->hasFile('excel_file')){
            $rows = Excel::toArray(new ContactImport(), $request->file('excel_file'));
            $data = array_filter(array_slice($rows[0], 1, 100) , function ($subArray) {
                return $subArray[0] !== null;
            });

            $partyObj = Party::find($request->party_id);
            $invitee = $partyObj->invitees()->where('invitee_id',auth('sanctum')->id())->first();
            $ownerInvitations = Invitation::where('party_id',$request->party_id)->whereHas('inviteeContact',function ($q){
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id());
            })->count();

            $maxPerInvitee = $invitee?->pivot?->count ?? 0;
            $available = $maxPerInvitee - $ownerInvitations;
            $total = 0;

            collect($data)->map(function ($item) use ($request,$partyObj,$total){
               $total+=  $item[2];
            });

            if($available < $total ){
                return $this->error(__('contact::api.limit_exceeded'));
            }

            collect($data)->map(function ($item) use ($request,$partyObj){
                $contact = Contact::firstOrCreate([
                    'mobile'  => $item[1],
                ],[
//                    'name'  => $item[0],
                    'mobile'  => $item[1],
                    'max_invitations'  => $item[2],
                ]);

                $inviteeContact = InviteeContact::firstOrCreate([
                    'contact_id'=>$contact->id ,
                    'invitee_id' => auth('sanctum')->id()
                ],[
                    'display_name'  => $item[0],
                ]);

                $contact_id = $inviteeContact?->id;

                if($partyObj){
                    $invitation = Invitation::where('party_id',$request->party_id)->where('contact_id',$contact_id)->latest('id')->first();
                    $start = $partyObj->invitations()->orderBy('id','desc')->first()?->invitation_number+1 ?? $partyObj->sort_start;
                    $related = $invitation ? $invitation?->related_invitation_id : null;

                    for ($i = 0; $i < ($item[2] ?? 1); $i++) {
                        $newObj = $inviteeContact->invitations()->create([
                            'invitation_number' => $start,
                            'code'  => strtoupper(\Str::random(10)),
                            'status'    => 2,
                            'party_id'  => $request->party_id,
                            'contact_id'    => $contact_id,
                            'step'  => $partyObj->next_step,
                            'invitations'   => 1,
                            'related_invitation_id' => $related,
                        ]);
                        if($i==0 && !$related){
                            $related = $newObj->id;
                        }
                        $start++;
                    }
                }
                $contact->update(['max_invitations'=>1]);
            });

            return $this->response(null, __('contact::api.uploaded'));
        }else{
            return $this->error(__('apps::dashboard.messages.failed'));
        }
    }

    public function syncPhoneContacts(PhoneContactsRequest $request)
    {
        DB::beginTransaction();
        $contactIds = [];
        try{
            $partyObj = Party::find($request->party_id);

            $invitee = $partyObj->invitees()->where('invitee_id',auth('sanctum')->id())->first();
            $ownerInvitations = Invitation::where('party_id',$request->party_id)->whereHas('inviteeContact',function ($q){
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id());
            })->count();

            $maxPerInvitee = $invitee?->pivot?->count ?? 0;
            $available = $maxPerInvitee - $ownerInvitations;
            $total = 0;
            $diffs = 0;

            foreach($request->contacts as $item){
                $total+=  $item['invitations'];
                $contact = Contact::where('mobile',$item['mobile'])->first();
                $oldCount = $contact?->invitations()->whereHas('inviteeContact',function ($q){
                    $q->where('invitees_contacts.invitee_id',auth('sanctum')->id());
                })->where('party_id',$partyObj->id)->count();
                $diffs+= abs(abs($item['invitations']) - $oldCount) ?? 0;
            }

            if($available < $diffs && $maxPerInvitee < $diffs + $total){
                return $this->error(__('contact::api.limit_exceeded'));
            }

            foreach($request->contacts as $item){
                $contact = Contact::firstOrCreate([
                    'mobile'  => $item['mobile'],
                ],[
    //                'name'  => $item['name'],
                    'mobile'  => $item['mobile'],
                    'max_invitations'  => $item['invitations'],
                ]);

                $inviteeContact = InviteeContact::firstOrCreate([
                    'contact_id'=>$contact->id ,
                    'invitee_id' => auth('sanctum')->id()
                ],[
                    'display_name'  => $item['name'],
                ]);

                $contact_id = $inviteeContact?->id;

                $contactInvitations =  Invitation::where('party_id',$request->party_id)->where('contact_id',$contact_id)->count() ?? 0;
                $available = $maxPerInvitee - $ownerInvitations;
                $inserted = 0;
                $invitations = (int) $item['invitations'];

                if($contactInvitations > $invitations){
                    $oldCount = Invitation::where('party_id',$request->party_id)->where('contact_id',$contact_id)->whereNotNull('sent_at')->latest('invitation_number')->take(($contactInvitations - $invitations))->count();
                    if($oldCount){
                        $mobile = validatePhone($contact?->mobile);
                        $mobile = strlen($mobile) == 8 ? '965'.$mobile : $mobile;
                        return $this->error(__('contact::api.sent_before',['contact' => $mobile]));
                    }
                    Invitation::where('party_id',$request->party_id)->where('contact_id',$contact_id)->latest('invitation_number')->take(($contactInvitations - $invitations))->forceDelete();
                }else if($contactInvitations < $invitations){
                    $inserted = $invitations - $contactInvitations;
                }

                if($available >= $inserted && $inserted){
                    $invitation = Invitation::where('party_id',$request->party_id)->where('contact_id',$contact_id)->latest('id')->first();
                    $start = $partyObj->invitations()->orderBy('id','desc')->first()?->invitation_number+1 ?? $partyObj->sort_start;

                    $related = $invitation ? $invitation?->related_invitation_id : null;
                    for ($i = 0; $i < ($inserted ?? 1); $i++) {
                        $newObj = $inviteeContact->invitations()->create([
                            'invitation_number' => $start,
                            'code'  => strtoupper(\Str::random(10)),
                            'status'    => 2,
                            'party_id'  => $request->party_id,
                            'contact_id'    => $contact_id,
                            'step'  => $partyObj->next_step,
                            'invitations'   => 1,
                            'related_invitation_id' => $related,
                        ]);
                        if($i==0 && !$related){
                            $related = $newObj->id;
                        }
                        $start++;
                    }
                    $contact->update(['max_invitations'=>1]);
                }
                $contactIds[] = $contact_id;
            }
            $partyObj->invitations()->whereHas('inviteeContact',function ($q){
                $q->where('invitees_contacts.invitee_id',auth('sanctum')->id());
            })->whereNotIn('contact_id',$contactIds)->forceDelete();
            DB::commit();
            return $this->response(null, __('contact::api.contacts_stored'));
        }catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
