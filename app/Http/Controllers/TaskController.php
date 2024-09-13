<?php

namespace App\Http\Controllers;

use App\Charts\ExpensesChart;
use App\Mail\TaskOverloadMail;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\EmailLog;
use Carbon\Carbon;
use App\Models\EngineerTask;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function showIds()
    {
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602', 'Z126577','Z10066', 'operation01', 'operation02','Z126457'];
        $responses = [];
        $engineerTicketCount = [];

        foreach ($ids as $id) {
            $response = Http::withOptions(['verify' => false])
                ->get("https://kpcsgt-app04.kpc.co.id:4433/api/Sihepi/GetAllTicketById/{$id}");

            if ($response->successful()) {
                $data = $response->json();
                $responses[$id] = $data;

                // Hitung jumlah tiket per engineer
                if (isset($data['listTicket'])) {
                    foreach ($data['listTicket'] as $ticket) {
                        $assignedTo = $ticket['assignedTo'];

                        if (!isset($engineerTicketCount[$assignedTo])) {
                            $engineerTicketCount[$assignedTo] = 0;
                        }
                        $engineerTicketCount[$assignedTo]++;

                         // Tambahkan tiket ke daftar tiket API
                         $apiTickets[] = $ticket['ticketNo'];

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
                $responses[$id] = ['error' => 'Failed to fetch data'];
            }
        }


    }
    public function getEngineerTicketCount()
    {
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602', 'Z126577'];
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

        // Mengurutkan array berdasarkan jumlah tiket (dari yang terbanyak)
        arsort($engineerTicketCount);

        return $engineerTicketCount;
    }
    public function taskDuration()
    {
        // Ambil data dari model EngineerTask dengan status complete
        $tasks = EngineerTask::where('status', 'Completed')->get();

        // Hitung durasi tugas
        $tasks = $tasks->map(function ($task) {
            $task->duration = $task->created_at->diffForHumans($task->updated_at, true);
            return $task;
        });

        // Kirim data ke view
        return view('tasks.duration', compact('tasks'));
    }
    public function chartJs(){
        $tasks = DB::table('users')
        ->leftJoin('engineer_tasks', 'users.engineer_id', '=', 'engineer_tasks.engineerNumber')
        ->select('users.name as engineer_name', DB::raw('count(engineer_tasks.id) as task_count'))
        ->where('engineer_tasks.status', 'Not Started')
        ->groupBy('users.name')
        ->having(DB::raw('count(engineer_tasks.id)'), '>', 0)
        ->get();

    return response()->json($tasks);
    }

}
