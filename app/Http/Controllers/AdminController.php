<?php

namespace App\Http\Controllers;

use App\Models\EngineerActivities;
use App\Models\EngineerLeave;
use App\Models\EngineerTask;
use App\Models\ExtraMiles;
use App\Models\TicketDuration;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

/** 
 * Controller untuk halaman admin
 * Fitur :
 * 1. Dashboard
 * 2. Manage Users
 * 3. Manage Tickets
 * 4. Engineer Activities
 * 5. Engineer Leaves
 * 6. Engineer On Progress
 * Author: Bayu
 * Creation Date: 2024-08-27
 */
class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $completedTasksCount = EngineerTask::where('status', 'Completed')->count();
        $inProgressTasksCount = EngineerTask::where('status', 'Not Started')->count();

        $tasks = EngineerActivities::where('status', 'Completed')->get();
        $tasks = $tasks->map(function ($task) {
            $task->duration = $task->created_at->diffInMinutes($task->completion_time);
            return $task;
        });
        $tasks2 = EngineerTask::where('status', 'In Progress')
            ->where('engineerNumber', $user->engineer_id)
            ->get();

        // Convert average completion time to hours
        $averageCompletionTimeInMinutes = $tasks->avg('duration');
        $averageCompletionTimeInHours = $averageCompletionTimeInMinutes / 60;

        return view('admin.index', compact('completedTasksCount', 'inProgressTasksCount', 'averageCompletionTimeInHours', 'tasks2'));
    }

    // public function manageEngineers()
    // {
    //     $engineers = Engineer::all();
    //     return view('admin.engineers', compact('engineers'));
    // }

    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Metode untuk menampilkan halaman edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Metode untuk menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully');
    }

    public function manageTickets(Request $request)
    {
        // Ambil semua engineer
        $engineers = EngineerTask::select('engineerNumber')->distinct()->get();
        // Ambil daftar nama engineer dari model User
        $engineerNames = User::pluck('name', 'engineer_id');

        $selectedEngineer = $request->input('engineer_id');

        $data = [];

        foreach ($engineers as $engineer) {
            $engineerId = $engineer->engineerNumber;
            // Filter berdasarkan engineer_id jika ada
            if ($selectedEngineer && $selectedEngineer != $engineerId) {
                continue;
            }
            $engineerName = User::where('engineer_id', $engineerId)->value('name');

            // Hitung jumlah tiket keseluruhan
            $totalTickets = EngineerTask::where('engineerNumber', $engineerId)->count();

            // Hitung jumlah tiket selesai
            $completedTickets = EngineerTask::where('engineerNumber', $engineerId)
                ->where('status', 'Completed')
                ->count();

            // Hitung jumlah tiket belum selesai
            $notStartedTickets = EngineerTask::where('engineerNumber', $engineerId)
                ->where('status', 'Not Started')
                ->count();

            // Hitung waktu keseluruhan dari TicketDuration
            $totalTime = TicketDuration::where('engineer_id', $engineerId)->sum('duration');

            // Hitung waktu rata-rata dari TicketDuration
            $averageTime = TicketDuration::where('engineer_id', $engineerId)->avg('duration');

            $data[] = [
                'engineer_id' => $engineerId,
                'engineer_name' => $engineerName,
                'total_tickets' => $totalTickets,
                'completed_tickets' => $completedTickets,
                'not_started_tickets' => $notStartedTickets,
                'total_time' => $totalTime,
                'average_time' => $averageTime,
            ];
        }
        
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

        return view('admin.tickets', compact('data', 'engineerNames', 'selectedEngineer', 'engineerOfTheDay', 'engineerOfTheMonth', 'totalTime'));
    }
    public function engineerActivities($engineer_id)
    {
        // Ambil nama engineer dari tabel users
        $engineerName = User::where('engineer_id', $engineer_id)->value('name');

        // Ambil aktivitas yang diselesaikan berdasarkan tanggal
        $activities = EngineerActivities::where('engineer_id', $engineer_id)
            ->where('status', 'Completed')
            ->selectRaw('DATE(completion_time) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.partials.engineer_activities', compact('engineerName', 'activities'));
    }
    public function engineerLeavesView()
    {
        //Use Soon (Do Not Delete)
        // $activities = EngineerActivities::where('status', 'Completed')->get();

        // foreach ($activities as $activity) {
        //     // Hitung durasi dalam menit
        //     $duration = Carbon::parse($activity->created_at)->diffInMinutes(Carbon::parse($activity->completion_time));

        //     // Simpan data ke TicketDuration
        //     $ticketDuration = new TicketDuration;
        //     $ticketDuration->engineer_id = $activity->engineer_id;
        //     $ticketDuration->ticketNo = $activity->ticketNo;
        //     $ticketDuration->duration = $duration;
        //     $ticketDuration->save();
        // }
        $engineerLeaves = EngineerLeave::all();
        $users = User::all();
        return view('admin.engineer_leaves', compact('users', 'engineerLeaves'));
    }
    public function engineerLeavesStore(Request $request)
    {
        $request->validate([
            'engineer_id' => 'required|exists:users,engineer_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::find($request->engineer_id);

        $engineerLeave = new EngineerLeave;
        $engineerLeave->engineer_id = $request->engineer_id;
        $engineerLeave->engineer_name = $request->engineer_name;
        $engineerLeave->start_date = $request->start_date;
        $engineerLeave->end_date = $request->end_date;
        $engineerLeave->save();

        return redirect()->route('admin.engineer.leaves')->with('success', 'Engineer leave added successfully.');
        // dd($request->all());
    }
    public function engineerOnProgressView()
    {
        $engineerOnProgress = EngineerActivities::where('status', 'In Progress')->get();
        return view('admin.engineer_onprogress', compact('engineerOnProgress'));
    }
    public function destroyEngineerOnProgress($id)
    {
        $engineerOnProgress = EngineerActivities::findOrFail($id);
        $engineerOnProgress->delete();
        return redirect()->route('admin.engineer.onprogress')->with('success', 'Engineer on progress deleted successfully.');
    }
    public function engineerExtraMilesView()
    {
        $users = User::all();
        $engineerExtraMiles = ExtraMiles::all();
        return view('admin.engineer_extra_miles', compact('users', 'engineerExtraMiles'));
    }
    public function engineerExtraMilesStore(Request $request)
    {
        $request->validate([
            'engineer_id' => 'required|exists:users,engineer_id',
            'activity_name' => 'required|string',
        ]);

        ExtraMiles::create($request->all());

        return redirect()->route('admin.engineer.extra-miles')->with('success', 'Engineer extra miles added successfully.');
        // dd($request->all());
    }
    public function destroyEngineerExtraMiles($id)
    {
        $engineerExtraMiles = ExtraMiles::findOrFail($id);
        $engineerExtraMiles->delete();
        return redirect()->route('admin.engineer.extra-miles')->with('success', 'Engineer extra miles deleted successfully.');
    }
}
