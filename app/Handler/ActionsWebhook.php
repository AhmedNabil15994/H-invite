<?php
namespace App\Handler;
use Modules\Contact\Entities\Contact;
use Modules\Party\Entities\Invitation;
use \Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Http;
class ActionsWebhook extends ProcessWebhookJob{

	public function handle(){
	    $data = json_decode($this->webhookCall, true);
	    $mainData = $data['payload'];

        $invitationObj = Invitation::where('queue_data->job_queue_id',$mainData['job_queue_id'])->first();
        if($invitationObj){
            $status = $mainData['action'] == 'agree' ? 4 : 3;
            $updateData = [
                'status' => $status,
                'attended_at'   => null,
            ];

            if($status == 4){
                $updateData['attended_at'] = date('Y-m-d H:i:s');
            }

            $invitationObj->update($updateData);
            Invitation::where('related_invitation_id',$invitationObj->id)->update($updateData);
        }

   		return true;
	}
}
