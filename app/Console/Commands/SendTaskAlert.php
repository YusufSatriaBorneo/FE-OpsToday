<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskOverloadMail;
use Illuminate\Support\Facades\Http;

class SendTaskAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-overload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task overload email at 23:45';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602'];
           $engineerTicketCount = [];

           foreach ($ids as $id) {
               $response = Http::withOptions(['verify' => false])
                   ->get("https://kpcsgt-app04.kpc.co.id:4433/api/Sihepi/GetAllTicketById/{$id}");

               if ($response->successful()) {
                   $data = $response->json();

                   if (isset($data['listTicket'])) {
                       foreach ($data['listTicket'] as $ticket) {
                           $assignedTo = $ticket['assignedTo'];

                           if (!isset($engineerTicketCount[$assignedTo])) {
                               $engineerTicketCount[$assignedTo] = 0;
                           }
                           $engineerTicketCount[$assignedTo]++;
                       }
                   }
               }
           }

           $overloadedEngineers = [];
           foreach ($engineerTicketCount as $engineer => $count) {
               if ($count > 10) {
                   $overloadedEngineers[] = [
                       'engineer' => $engineer,
                       'ticketCount' => $count,
                   ];
               }
           }

           if (!empty($overloadedEngineers)) {
               $now = Carbon::now();
               $lastEmail = EmailLog::whereDate('sent_at', $now->toDateString())->first();

               if (!$lastEmail) {
                   Mail::to('Bayu.Adhitya@kpc.co.id')->send(new TaskOverloadMail($overloadedEngineers));

                   EmailLog::create([
                       'sent_at' => $now,
                       'recipient' => 'Bayu.Adhitya@kpc.co.id',
                       'subject' => 'Task Overload Notification',
                       'content' => json_encode($overloadedEngineers)
                   ]);

                   $this->info('Task overload email sent successfully.');
               } else {
                   $this->info('Email already sent today.');
               }
           } else {
               $this->info('No overloaded engineers found. Email not sent.');
           }
    }
}
