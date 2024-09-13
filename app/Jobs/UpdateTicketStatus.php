<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\EngineerTask;
use App\Models\EngineerActivities;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateTicketStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       // Daftar ID engineer yang ingin Anda ambil datanya
       $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602', 'Z126577','Z10066', 'operation01', 'operation02','Z126457'];

       // Inisialisasi array untuk tiket API
       $apiTickets = [];
       $engineerTicketCount = [];

       // Ambil data tiket dari API Sihepi
       foreach ($ids as $id) {
           $response = Http::withOptions(['verify' => false])
               ->get("https://kpcsgt-app04.kpc.co.id:4433/api/Sihepi/GetAllTicketById/{$id}");

           if ($response->successful()) {
               $data = $response->json();

               // Hitung jumlah tiket per engineer
               if (isset($data['listTicket'])) {
                   foreach ($data['listTicket'] as $ticket) {
                       $apiTickets[] = $ticket['ticketNo'];
                       $assignedTo = $ticket['assignedTo'];

                       if (!isset($engineerTicketCount[$assignedTo])) {
                           $engineerTicketCount[$assignedTo] = 0;
                       }
                       $engineerTicketCount[$assignedTo]++;

                       // cek apakah ticketNo ada di DB
                       $existingTask = EngineerTask::where('ticketNo', $ticket['ticketNo'])->first();
                       if ($existingTask) {
                           // Periksa apakah ada perubahan pada assignedTo atau status
                           if ($existingTask->assignedTo !== $ticket['assignedTo'] || $existingTask->status !== $ticket['status']) {
                               // Perbarui data di model EngineerTask
                               $existingTask->update([
                                   'engineerNumber' => $id,
                                   'assignedTo' => $ticket['assignedTo'],
                                   'status' => "Not Started",
                               ]);
                           }
                       } else {
                           // Masukkan data ke dalam model EngineerTask
                           EngineerTask::create([
                               'engineerNumber' => $id,
                               'ticketNo' => $ticket['ticketNo'],
                               'title' => $ticket['title'],
                               'assignedTo' => $ticket['assignedTo'],
                               'status' => "Not Started",
                           ]);
                       }
                   }
               }
           } else {
               // Tangani kesalahan jika gagal mengambil data dari API
               Log::error('Failed to fetch data from API for ID: ' . $id);
           }
       }

       // Ambil semua tiket dari database
       $dbTickets = EngineerTask::pluck('ticketNo')->toArray();

       // Cari tiket yang ada di database tapi tidak ada di API
       $completedTickets = array_diff($dbTickets, $apiTickets);

       // Tandai tiket yang sudah tidak ada di API sebagai selesai
       EngineerTask::whereIn('ticketNo', $completedTickets)->update(['status' => 'Completed']);
    }
}
