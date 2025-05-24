<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\Party\Entities\Invitation;

class SendInvitations implements ShouldQueue//,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public  $party_id;
    public  $invitee_id;
    public  $step;
    public function __construct($party_id,$invitee_id,$step)
    {
        $this->party_id = $party_id;
        $this->invitee_id = $invitee_id;
        $this->step = $step;
    }

//    public $uniqueFor = 3600;
//    public function uniqueId()
//    {
//        return $this->party_id . '-' . $this->invitee_id;
//    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function failed(\Exception $exception)
    {
        self::dispatch($this->party_id,$this->invitee_id,$this->step);
    }
    public function handle()
    {
        $i=0;
        App::setLocale('ar');

        $data = Invitation::where([
            ['party_id',$this->party_id],
            ['sent_at',null],
            ['step','<=',$this->step],
        ])->whereHas('inviteeContact',function ($q){
            $q->where('invitees_contacts.invitee_id',$this->invitee_id);
        })->groupBy('contact_id')->orderBy('id','asc')->get();

        foreach ($data as $invitation){
            $pdf = [];
            $i++;
            $checkArr = [['party_id',$invitation->party_id],['contact_id',$invitation->contact_id],['step',$invitation->step]];

            $count = Invitation::where($checkArr)->count();

            $msg = $invitation->party->whatsapp_msg;
            $msg.= " \r\n \r\n " . __('party::dashboard.parties.form.notes');
            $msg.= " \r\n " . __('party::dashboard.parties.form.invitations_count',['number' => $count ]);
            $msg.= " \r\n \r\n " .date('Y-m-d H:i:s',strtotime('+'.($i*60).' seconds'));
            $accept = $invitation->party->acceptance_reply ?? '';
            $refuse = $invitation->party->rejection_reply ?? '';

            $newImage = convertPdfToJpeg($invitation->party->getFirstMediaPath('images'),$invitation->party);
            $image = $newImage['image'];
            foreach ($invitation->where($checkArr)->get() as $invit){
                $qr = public_path().'/uploads/qr/'.$invit->code.'.png';//\URL::to('/uploads/qr/1RA2XBB6UC.png');
                $acceptImage = generateInvitation($image,$qr,$invit,1,null,null,$newImage['height'],$newImage['width']);
                $pdf[] =  \URL::to('/uploads/parties/party-'.$invit->party_id.'/'.$invit->invitation_number.'.pdf');
            }

            $notifyUrl = \URL::to('/webhooks/actions-webhook');
            $mobile = validatePhone($invitation?->inviteeContact?->contact?->mobile);
            $mobile = strlen($mobile) == 8 ? '965'.$mobile : $mobile;
            $sendAt = date('Y-m-d H:i:s',strtotime('+'.($i*60).' seconds'));

            $requestData= [
                'send_at' => $sendAt,
                'phone' => $mobile,
                'message_data' => [
                    'message_type' => 'image',
                    'url'   => $pdf,
                    'body'  => $msg,
                    'notify_url'    => $notifyUrl,
                    'agree' => [
                        'message_type'  => 'image',
                        'url'   => $acceptImage,
                        'caption'  => $accept,
                        'invitations'     => $count,
                    ],
                    'refuse'    => [
                        'message_type'  => 'text',
                        'body'  => $refuse,
                    ],
                ],
            ];

            $result = \Http::withHeaders([
                'ID'    => env('GOWASL_CHANNEL_ID'),
                'TOKEN' => env('GOWASL_CHANNEL_TOKEN'),
                'Authorization' => 'Bearer '.env('GOWASL_CHANNEL_IDENTIFIER'),
                'Accept'  => 'application/json'
            ])->post(env('GOWASL_API_URL').'/channels/messages/sendDecisionMessage',$requestData);

            $result = (array) $result?->json() ?? [];
            if(!isset($result['errors'])){
                Invitation::where($checkArr)->update([
                    'sent_at'    => $sendAt,
                    'queue_data' => [
                        'job_queue_id' => $result['data']['job_queue_id'] ?? '',
                    ],
                ]);
            }
        }
//        $this->release(5);
    }
}
