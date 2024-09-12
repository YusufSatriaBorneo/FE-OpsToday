<?php

namespace App\Http\Controllers;

use App\Models\EngineerActivities;
use App\Models\EngineerTask;
use App\Models\TicketDuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EngineerController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil tugas yang belum dimulai berdasarkan engineer_id
        $tasks = EngineerTask::where('status', 'Not Started')
            ->where('engineerNumber', $user->engineer_id)
            ->get();
        $currentActivity = EngineerActivities::where('engineer_id', $user->engineer_id)
            ->where('status', 'In Progress')
            ->get();

        $completedTickets = EngineerActivities::where('engineer_id', $user->engineer_id)
            ->where('status', 'Completed')
            ->get()
            ->map(function ($activity) {
                $task = EngineerTask::where('ticketNo', $activity->ticketNo)->first();
                $activity->title = $task ? $task->title : 'Unknown';
                $activity->completion_time = Carbon::parse($activity->created_at)->diffInMinutes(Carbon::parse($activity->completion_time));
                return $activity;
            });

        // Inisialisasi array untuk tiket API
        $apiTickets = [];

        // Daftar ID engineer yang ingin Anda ambil datanya
        $ids = [$user->engineer_id]; // Sesuaikan dengan ID engineer yang relevan

        // Ambil data tiket dari API Sihepi
        foreach ($ids as $id) {
            $response = Http::withOptions(['verify' => false])
                ->get("https://kpcsgt-app04.kpc.co.id:4433/api/Sihepi/GetAllTicketById/{$id}");

            if ($response->successful()) {
                $data = $response->json();

                // Hitung jumlah tiket per engineer
                if (isset($data['listTicket'])) {
                    foreach ($data['listTicket'] as $ticket) {
                        $assignedTo = $ticket['assignedTo'];

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
                // Tangani kesalahan jika gagal mengambil data dari API
                $responses[$id] = ['error' => 'Failed to fetch data'];
            }
        }

        // Ambil semua tiket dari database
        $dbTickets = EngineerActivities::pluck('ticketNo')->toArray();

        // Ambil tiket yang sudah selesai di EngineerTask
        $completedTasks = EngineerTask::where('status', 'Completed')->pluck('ticketNo')->toArray();



        // Perbarui hanya jika ada tiket yang sudah selesai di EngineerTask
        if (!empty($completedTasks)) {
            // Tandai tiket yang sesuai di EngineerActivities sebagai selesai
            $completedActivities = EngineerActivities::whereIn('ticketNo', $completedTasks)->get();

            foreach ($completedActivities as $activity) {
                DB::transaction(function () use ($activity) {
                    // Ambil data lagi dengan lock for update untuk mencegah perubahan bersamaan
                    $activity = EngineerActivities::where('ticketNo', $activity->ticketNo)
                        ->lockForUpdate()->first();

                    // Cek ulang status sebelum update
                    if (!$activity->completion_time && $activity->status !== 'Completed') {
                        // Update hanya jika belum pernah diupdate sebelumnya
                        $endTime = Carbon::now();
                        $activity->update([
                            'status' => 'Completed',
                            'isOnProgress' => 0,
                            'completion_time' => $endTime // Menyimpan waktu saat update
                        ]);

                        // Hitung durasi pengerjaan dan simpan ke tabel ticket_durations
                        if (!TicketDuration::where('ticketNo', $activity->ticketNo)->exists()) {
                            $startTime = Carbon::parse($activity->created_at);
                            $duration = $startTime->diffInMinutes($endTime);

                            TicketDuration::create([
                                'engineer_id' => $activity->engineer_id,
                                'ticketNo' => $activity->ticketNo,
                                'duration' => $duration
                            ]);
                        }
                    }
                });
            }
        }
        // // Hitung durasi pengerjaan dan simpan ke tabel ticket_durations
        // foreach ($completedActivities as $activity) {
        //     if (!TicketDuration::where('ticketNo', $activity->ticketNo)->exists()) {
        //         $startTime = Carbon::parse($activity->created_at);
        //         $endTime = Carbon::parse($activity->completion_time);
        //         $duration = $startTime->diffInMinutes($endTime);

        //         TicketDuration::create([
        //             'engineer_id' => $activity->engineer_id,
        //             'ticketNo' => $activity->ticketNo,
        //             'duration' => $duration
        //         ]);
        //     }
        // }


        return view('engineer.dashboard', compact('tasks', 'currentActivity', 'completedTickets'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'ticketNo' => 'required|string|unique:engineer_activities,ticketNo',
            'engineerNumber' => 'required|string',
        ]);

        // Ambil engineer yang sedang login
        // Ambil engineer_id dari engineer_tasks berdasarkan ticket_no
        $task = EngineerTask::where('ticketNo', $request->ticketNo)->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
        }

        // Simpan aktivitas engineer
        EngineerActivities::create([
            'ticketNo' => $request->ticketNo, // Mengambil ticket_no dari request
            'engineer_id' => $request->engineerNumber, // ID engineer yang sedang login
            'status' => 'In Progress',
            'isOnProgress' => 1, // Set isOnProgress jika diperlukan
        ]);

        // Update status tiket di tabel tiket (jika ada)
        // Ticket::where('ticket_no', $request->ticket_no)->update(['status' => 'In Progress']);

        return redirect()->back()->with('success', 'Tiket sedang dikerjakan.');
    }
}
