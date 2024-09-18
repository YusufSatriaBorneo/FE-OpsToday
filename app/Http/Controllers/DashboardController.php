<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TaskOverloadMail;
use App\Models\EngineerActivities;
use App\Models\EngineerLeave;
use App\Models\EngineerTask;
use App\Models\ExtraMiles;
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

        // Ambil data dari EngineerLeaves
        $currentDate = Carbon::now()->toDateString();


        // Dapatkan nama bulan saat ini
        $currentMonth = Carbon::now()->format('F');

        $engineers = User::all(); // Ambil semua engineer

        // Ambil aktivitas engineer yang sedang berlangsung
        $activities = EngineerActivities::all(); // Ambil semua aktivitas engineer
        // Hitung Engineer of the Day
        $engineerOfTheDay = DB::table(DB::raw('(
            SELECT engineer_id, 
                   COUNT(*) as count, 
                   AVG(TIMESTAMPDIFF(SECOND, created_at, completion_time)) as avg_completion_time
            FROM engineer_activities
            WHERE status = "Completed"
              AND DATE(completion_time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            GROUP BY engineer_id
        ) as sub'))
        ->orderBy('avg_completion_time', 'asc')
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

        $extraMilesData = ExtraMiles::select('engineer_name', DB::raw('count(*) as extra_miles_count'))
        ->groupBy('engineer_name')
        ->get();

        return view('dashboard.index', compact('topEngineer', 'topTicketCount', 'engineerTicketCount', 'activities', 'engineerOfTheDay', 'engineerOfTheMonth', 'engineerNames', 'completedTasksCount', 'inProgressTasksCount', 'currentMonth', 'extraMilesData'));
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
    public function statusCount()
    {
        // Ambil data dari API eksternal
        $response = Http::get('http://localhost:3000/api/absence');
        $data = $response->successful() ? $response->json() : [];

        // Rekap jumlah hadir, keluar, dan absen
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
            } elseif ($item['status1'] === 'Absen') {
                $statusCounts['Absen']++;
            }
        }
        // Ambil data dari EngineerLeaves
        $currentDate = Carbon::now()->toDateString();
        $engineerLeaves = EngineerLeave::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->get();

        // Buat array engineer_ids dari EngineerLeave
        $engineerIdsOnLeave = $engineerLeaves->pluck('engineer_id')->toArray();

        // Kurangi jumlah orang yang absen jika fsCardNo cocok dengan engineer_id
        foreach ($data as $item) {
            if ($item['status1'] === 'Absen' && in_array($item['fsCardNo'], $engineerIdsOnLeave)) {
                $statusCounts['Absen']--;
            }
        }

        return response()->json($statusCounts);
    }
}
