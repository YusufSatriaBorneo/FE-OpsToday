<?php

namespace App\Http\Controllers;

use App\Models\EngineerActivities;
use App\Models\EngineerLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    public function index()
    {
        // Mengambil data dari API Node.js
        // Fungsi Fingerprint
        $response = Http::get('http://localhost:3000/api/absence');
        if ($response->successful()) {
            $data = $response->json();
            
            // Mengambil data aktivitas engineer dari database
            $activities = EngineerActivities::all();

            // Mengambil data cuti engineer dari database
            $currentDate = Carbon::now()->toDateString();
            $leaves = EngineerLeave::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->get();
            
            // Menggabungkan data absensi dengan aktivitas engineer
            foreach ($data as &$item) {
                // Mendapatkan aktivitas saat ini berdasarkan fsCardNo (atau ID lain yang sesuai)
                $currentActivities = $activities->where('engineer_id', $item['fsCardNo']);
                
                // Mengecek apakah engineer sedang dalam progress (isOnProgress)
                $item['isOnProgress'] = $currentActivities->contains('isOnProgress', 1);
                $item['isOnLeave'] = $leaves->contains('engineer_id', $item['fsCardNo']);
            }
            
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }
    public function fetchData()
    {
        // Mengambil data terbaru dari API Node.js
        $response = Http::get('http://localhost:3000/api/absence');

        if ($response->successful()) {
            $data = $response->json();
            return view('absensi.partials.table', compact('data'));
        } else {
            return response()->json(['error' => 'Gagal terhubung ke API'], 500);
        }
    }
    public function fetchDataDashboard()
    {
        // Mengambil data terbaru dari API Node.js
        $response = Http::get('http://localhost:3000/api/absence');

        if ($response->successful()) {
            $data = $response->json();
            return view('dashboard.index', compact('data'));
        } else {
            return response()->json(['error' => 'Gagal terhubung ke API'], 500);
        }
    }
}
