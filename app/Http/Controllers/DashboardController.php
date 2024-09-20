<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TaskOverloadMail;
use App\Models\EngineerActivities;
use App\Models\EngineerAttendanceSnapshot;
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
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastYear = Carbon::now()->subMonth()->year;

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
        $engineerOfTheMonth = DB::table(DB::raw('(
            SELECT engineer_id, 
                   COUNT(*) as count, 
                   AVG(TIMESTAMPDIFF(SECOND, created_at, completion_time)) as avg_completion_time
            FROM engineer_activities
            WHERE status = "Completed"
              AND MONTH(completion_time) = ?
              AND YEAR(completion_time) = ?
            GROUP BY engineer_id
        ) as sub'))
            ->setBindings([$lastMonth, $lastYear])
            ->orderBy('avg_completion_time', 'asc')
            ->orderBy('count', 'desc')
            ->first();

        $engineerNames = User::pluck('name', 'engineer_id');

        $extraMilesData = ExtraMiles::all();

        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Daftar engineer_id yang akan dikecualikan
        $excludedEngineerIds = ['Z126457', 'Z126397', 'Z126577', 'Z67254'];

        // Ambil data absensi hari ini
        $snapshots = DB::table('engineer_attendance_snapshots')
            ->whereDate('created_at', $today)
            ->whereNotIn('engineer_id', $excludedEngineerIds)
            ->get();

        // Lakukan perhitungan timeliness
        $onTimeCount = 0;
        $lateCount = 0;

        foreach ($snapshots as $snapshot) {
            // Format check_in_time menjadi hanya jam dan menit
            $checkInTime = Carbon::parse($snapshot->check_in_time);

            // Set waktu ke jam 8 pagi pada tanggal yang sama dengan check_in_time
            $eightAM = Carbon::parse($snapshot->check_in_time)->setTime(8, 0, 0);

            if ($snapshot->status == 'Hadir' && $checkInTime->lessThanOrEqualTo($eightAM)) {
                $onTimeCount++;
            } else {
                $lateCount++;
            }
        }

        // Hitung total engineer yang hadir hari ini
        $totalCount = $onTimeCount + $lateCount;

        // Hitung persentase ketepatan waktu
        $clockInTimeliness = $totalCount > 0 ? round(($onTimeCount / $totalCount) * 100, 2) : 0;

        // Hitung jumlah engineer yang hadir sebelum pukul 8.00 pagi
        $onTimeCount = EngineerAttendanceSnapshot::whereDate('check_in_time', $today)
            ->whereTime('check_in_time', '<=', '08:00:00')
            ->whereNotIn('engineer_id', $excludedEngineerIds)
            ->count();

        // Hitung total engineer yang hadir hari ini
        $totalCount = EngineerAttendanceSnapshot::whereDate('check_in_time', $today)
            ->whereNotIn('engineer_id', $excludedEngineerIds)
            ->count();

        // Hitung persentase ketepatan waktu
        $clockInTimeliness = $totalCount > 0 ? round(($onTimeCount / $totalCount) * 100, 2) : 0;

        return view('dashboard.index', compact('topEngineer', 'topTicketCount', 'engineerTicketCount', 'activities', 'engineerOfTheDay', 'engineerOfTheMonth', 'engineerNames', 'completedTasksCount', 'inProgressTasksCount', 'currentMonth', 'extraMilesData', 'clockInTimeliness', 'onTimeCount', 'lateCount','engineerOfTheMonth'));
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
