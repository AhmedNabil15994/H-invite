<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Modules\Party\Entities\Invitation;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder message the day before the party';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        App::setLocale('ar');
        $limitation = setting('reminder_in') ?? 24; // 24 hours

        $invitations = Invitation::whereHas('party',function ($q) use ($limitation){
            $q->whereBetween('start_at',[
                Carbon::now()->format('Y-m-d 00:00:00'),
                Carbon::now()->addHours($limitation)->format('Y-m-d 00:00:00'),
            ]);
        })->where([
            ['status',4],
            ['reminded_at',null],
            ['sent_at','!=',null],
        ])->groupBy('contact_id')->orderBy('id','desc')->get();

        foreach($invitations as $invitation){
            $mobile = $invitation->inviteeContact?->contact?->mobile;
            $mobile =  validatePhone($mobile);
            $mobile = strlen($mobile) == 8 ? '965'.$mobile : $mobile;

            $result = \Http::withHeaders([
                'ID'    => env('GOWASL_CHANNEL_ID'),
                'TOKEN' => env('GOWASL_CHANNEL_TOKEN'),
                'Authorization' => 'Bearer '.env('GOWASL_CHANNEL_IDENTIFIER'),
                'Accept'  => 'application/json'
            ])->post(env('GOWASL_API_URL').'/channels/messages/sendBulkMessage',[
                    'phones' => [$mobile],
                    'interval'  => 60,
                    'messageType'   => 1,
                    'messageData'   => [
                        'body'  => $invitation?->party?->reminder_msg,
                ],
            ]);

            $result = (array) $result?->json() ?? [];
            if(count($result)){
                $invitation->update(['reminded_at'=>Carbon::now()]);
            }
        }

        return true;
    }
}
