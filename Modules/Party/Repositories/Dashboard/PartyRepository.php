<?php

namespace Modules\Party\Repositories\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contact\Entities\Contact;
use Modules\Contact\Repositories\Dashboard\ContactRepository;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Offer\Entities\Offer;
use Modules\Party\Entities\Party;

class PartyRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(Party::class);
        $this->statusAttribute = ['status',];
        $this->fileAttribute       = ['image' => 'images','invitation_file'=>'invitations'];
    }

    public function QueryTable($request)
    {
        $query = $this->model->where(function ($q) use ($request){
            if (isset($request['search']['value']) && !empty($request['search']['value'])) {
                $q->where(DB::raw('lower(title)'),'LIKE','%'.strtolower($request['search']['value']).'%')
                    ->orWhere(DB::raw('lower(discount_desc)'),'LIKE','%'.strtolower($request['search']['value']).'%');
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

    public function modelUpdated($model, $request): void
    {
        $data = [];
        foreach ($request->invitee_id as $one){
            $data[] = [
                'invitee_id' => $one,
                'count' => $request['counts'][$one],
            ];
        }
        $model->invitees()->sync($data);    }

    public function prepareData(array $data, Request $request, $is_create = true): array
    {
        if(!isset($request->title['en']) || empty($request->title['en'])){
            $data['title']['en'] = $request->title['ar'];
        }
        if(!isset($request->description['en']) || empty($request->description['en'])){
            $data['description']['en'] = $request->description['ar'];
        }
        if(!isset($request->whatsapp_msg['en']) || empty($request->whatsapp_msg['en'])){
            $data['whatsapp_msg']['en'] = $request->whatsapp_msg['ar'];
        }
        if(!isset($request->acceptance_reply['en']) || empty($request->acceptance_reply['en'])){
            $data['acceptance_reply']['en'] = $request->acceptance_reply['ar'];
        }
        if(!isset($request->rejection_reply['en']) || empty($request->rejection_reply['en'])){
            $data['rejection_reply']['en'] = $request->rejection_reply['ar'];
        }
        if(!isset($request->reminder_msg['en']) || empty($request->reminder_msg['en'])){
            $data['reminder_msg']['en'] = $request->reminder_msg['ar'];
        }

        return $data;
    }
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($key = array_search('null', $request->all())) {
                $request->merge([$key => null]);
            }
            // handle status attribute
            $status = $this->handleStatusInRequest($request);
            if (count($this->fileAttribute) > 0) {

                $data = $request->except(array_keys($this->fileAttribute));
            }

            if (count($status) > 0) {
                $data = array_merge($data, $status);
            }
            // call the prepareData function
            $data = $this->prepareData($data, $request, false);

            $model = $this->model->create($data);

            // call back model created
            $this->modelCreated($model, $request);

            $this->handleFileAttributeInRequest($model, $request, false);
            $this->dealWithContacts($request);
            $this->createInvitations($model,$request);

            DB::commit();
            $this->committedAction($model, $request, "create");

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function dealWithContacts($request)
    {
        if(isset($request->contact_type) && $request->contact_type == 2){
            (new ContactRepository())->dealWithExcel($request);
        }else if(isset($request->contact_type) && $request->contact_type == 3){
//            dd($request->all());
        }else{
            $contacts = json_decode($request->contacts,true);
            collect($contacts)->map(function ($item) use ($request){
                if(is_array($item)){
                    $contact = Contact::firstOrCreate([
                        'mobile'  => $item['mobile'],
                    ],[
//                        'name'  => $item['name'],
                        'mobile'  => $item['mobile'],
                        'email'  => $item['email'],
                        'max_invitations'  => $item['max_invitations'] ?? 1,
                    ]);
                    $contact->invitees()->syncWithPivotValues($request->invitee_id,[
                        'display_name'  => $item['name'],
                    ]);
                }
            });
        }
    }

    public function createInvitations($model,$request)
    {

        $start = $model->sort_start ?? 1;
        foreach ($model->invitees as $invitee){
            $contacts = $invitee->invitee_contacts->toArray();
            $contacts = array_slice($contacts,0,$invitee->pivot->count);
            foreach ($contacts as $contact) {
                $contact = Contact::with('invitations')->find($contact['id']);
                if(is_null($request->contact_type)){
                    foreach (json_decode($request->contacts,true) as $oneContact){
                        if($oneContact && $oneContact['mobile'] == $contact['mobile']){
                            $contact->max_invitations = $oneContact['max_invitations'];
                            $contact->save();
                        }
                    }
                }

                $invitation = $contact->invitations()->where('party_id',$model->id)->first();
                $related = $invitation ? $invitation?->related_invitation_id : null;
                for ($i = 0; $i < ($contact->max_invitations ?? 1); $i++) {
                    $newObj = $model->invitations()->create([
                        'invitation_number' => $start,
                        'code'  => strtoupper(\Str::random(10)),
                        'status'    => 2,
                        'contact_id'    => $contact->id,
                        'invitations'   => 1,
                        'related_invitation_id' => $related,
                    ]);
                    if($i==0){
                        $related = $newObj->id;
                    }
                    $start++;
                }
                $contact->update(['max_invitations'=>1]);
            }
        }
    }
    public function modelCreated($model, $request, $is_created = true): void
    {
        $data = [];
        foreach ($request->invitee_id as $one){
            $data[] = [
                'invitee_id' => $one,
                'count' => $request['counts'][$one],
            ];
        }
        $model->invitees()->sync($data);
    }

}
