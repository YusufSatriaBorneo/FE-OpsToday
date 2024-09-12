<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TaskOverloadMail;
use App\Models\EngineerActivities;
use App\Models\EngineerTask;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Fungsi Menampilkan chart
        $taskController = new TaskController();
        $chart = $taskController->showIds();
        $engineerTicketCount = $taskController->getEngineerTicketCount();
        $topEngineer = key($engineerTicketCount);
        $topTicketCount = reset($engineerTicketCount);

        // Mendapatkan bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Mengambil jumlah tugas yang diselesaikan pada bulan dan tahun saat ini
        $completedTasksCount = EngineerTask::where('status', 'Completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $inProgressTasksCount = EngineerTask::where('status', 'Not Started')->count();

        // Fungsi Fingerprint
        $response = Http::get('http://localhost:3000/api/absence');
        $data = $response->successful() ? $response->json() : [];

        //Rekap jumlah hadir
        $statusCounts = [
            'Hadir' => 0,
            'Keluar' => 0,
            'Absen' => 0
        ];
        foreach ($data as $item) {
            if ($item['status1'] === 'Hadir') {
                $statusCounts['Hadir']++;
            } elseif ($item['status1'] === 'Keluar') {
                $statusCounts['Keluar']++;
            } else {
                $statusCounts['Absen']++;
            }
        }
        $engineers = User::all(); // Ambil semua engineer

        // Ambil aktivitas engineer yang sedang berlangsung
        $activities = EngineerActivities::all(); // Ambil semua aktivitas engineer
        // Hitung Engineer of the Day
        $engineerOfTheDay = EngineerActivities::select('engineer_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'Completed')
            ->whereDate('completion_time', Carbon::yesterday())
            ->groupBy('engineer_id')
            ->orderBy('count', 'desc')
            ->first();

        // Hitung Engineer of the Month
        $engineerOfTheMonth = EngineerActivities::select('engineer_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'Completed')
            ->whereMonth('completion_time', Carbon::now()->month)
            ->groupBy('engineer_id')
            ->orderBy('count', 'desc')
            ->first();

        $engineerNames = User::pluck('name', 'engineer_id');

        return view('dashboard.index', compact('chart', 'topEngineer', 'topTicketCount', 'engineerTicketCount', 'data', 'statusCounts', 'activities', 'engineerOfTheDay', 'engineerOfTheMonth', 'engineerNames', 'completedTasksCount', 'inProgressTasksCount'));
    }
    public function getDashboardContent()
    {
        // Ambil data yang diperlukan untuk dashboard
        $congratulationsCard = view('dashboard.partials.congratulations-card')->render();
        $engineerTodayActivities = view('dashboard.partials.engineer-today-activities')->render();
        $engineerCurrentTask = view('dashboard.partials.engineer-current-task')->render();

        return response()->json([
            'congratulationsCard' => $congratulationsCard,
            'engineerTodayActivities' => $engineerTodayActivities,
            'engineerCurrentTask' => $engineerCurrentTask,
        ]);
    }
}
