<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    public function index()
    {
        // Mengambil data dari API Node.js
        $response = Http::get('http://localhost:3000/api/absence');

        // Cek apakah permintaan berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengubah JSON ke array PHP
            return view('absensi.index', compact('data')); // Kirim data ke view
        } else {
            return abort(500, 'Error connecting to Node.js API');
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
