<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskOverloadMail;
use App\Models\EmailLog;
use Carbon\Carbon;
use App\Jobs\UpdateTicketStatus;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Kirim email pada pukul 07:30 pagi dan 16:30 sore setiap dua hari sekali
        $schedule->call(function () {
            Log::info('Scheduler is running.');

            $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602', 'Z126577'];
            $responses = [];
            $engineerTicketCount = [];

            foreach ($ids as $id) {
                $response = Http::withOptions(['verify' => false])
                    ->get("https://kpcsgt-app04.kpc.co.id:4433/api/Sihepi/GetAllTicketById/{$id}");

                if ($response->successful()) {
                    $data = $response->json();
                    $responses[$id] = $data;

                    if (isset($data['listTicket'])) {
                        foreach ($data['listTicket'] as $ticket) {
                            $assignedTo = $ticket['assignedTo'];

                            if (!isset($engineerTicketCount[$assignedTo])) {
                                $engineerTicketCount[$assignedTo] = 0;
                            }
                            $engineerTicketCount[$assignedTo]++;
                        }
                    }
                } else {
                    $responses[$id] = ['error' => 'Failed to fetch data'];
                }
            }

            $overloadedEngineers = [];
            foreach ($engineerTicketCount as $engineer => $count) {
                if ($count >= 10) {
                    $overloadedEngineers[] = [
                        'engineer' => $engineer,
                        'ticketCount' => $count,
                    ];
                }
            }

            if (!empty($overloadedEngineers)) {
                $now = Carbon::now();

                Log::info('Sending email to recipients.');

                // Kirim email ke beberapa penerima
                Mail::to(['Afif.Darmawan@kpc.co.id', 'Amos.Silamba@kpc.co.id', 'Help.Desk@kpc.co.id'])
                    ->cc(['Yundri.Saputra@kpc.co.id', 'Bayu.Adhitya@kpc.co.id', 'DiTechInfraServiceShift@kpc.co.id', 'Rudani@kpc.co.id'])
                    ->send(new TaskOverloadMail($overloadedEngineers));

                // Catat log pengiriman email
                EmailLog::create([
                    'sent_at' => $now,
                    'recipient' => 'Bayu.Adhitya@kpc.co.id, DiTechInfraSupportTeam@kpc.co.id',
                    'Afif.Darmawan@kpc.co.id',
                    'Amos.Silamba@kpc.co.id',
                    'Help.Desk@kpc.co.id',
                    'Yundri.Saputra@kpc.co.id',
                    'DiTechInfraServiceShift@kpc.co.id',
                    'Rudani@kpc.co.id',
                    'subject' => 'Task Overload Notification',
                    'content' => json_encode($overloadedEngineers)
                ]);
            } else {
                Log::info('No overloaded engineers found.');
            }
        })->cron('30 07,13,16 * * *');

        // Tambahkan tugas untuk mengosongkan file laravel.log setiap hari pada pukul 00:00
        $schedule->call(function () {
            file_put_contents(storage_path('logs/laravel.log'), '');
        })->daily();
        $schedule->job(new UpdateTicketStatus())->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
