<?php

namespace App\Jobs;

use App\Models\EngineerAttendanceSnapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class FetchAttendanceData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Ambil data absensi dari API eksternal
        $response = Http::get('http://localhost:3000/api/absence');

        if ($response->successful()) {
            $data = $response->json();

            // Log data yang diterima untuk debugging
            Log::info('Data received from API:', $data);

            // Simpan snapshot data absensi ke dalam database menggunakan model Eloquent
            foreach ($data as $attendance) {
                // Hanya proses data dengan status1 "Hadir"
                if ($attendance['status1'] === 'Hadir') {
                    Log::info('Processing attendance:', $attendance); // Log setiap data absensi

                    try {
                        // Cek apakah data sudah ada berdasarkan engineer_id dan tanggal
                        $existingAttendance = EngineerAttendanceSnapshot::where('engineer_id', $attendance['fsCardNo'])
                            ->whereDate('check_in_time', '=', date('Y-m-d', strtotime($attendance['CI'])))
                            ->first();

                        if ($existingAttendance) {
                            // Perbarui data jika sudah ada
                            $existingAttendance->update([
                                'status' => $attendance['status1'],
                                'check_in_time' => $attendance['CI'],
                            ]);
                            Log::info('Attendance updated successfully for engineer_id:', ['engineer_id' => $attendance['fsCardNo']]);
                        } else {
                            // Buat data baru jika belum ada
                            EngineerAttendanceSnapshot::create([
                                'engineer_id' => $attendance['fsCardNo'],
                                'status' => $attendance['status1'],
                                'check_in_time' => $attendance['CI'],
                            ]);
                            Log::info('Attendance saved successfully for engineer_id:', ['engineer_id' => $attendance['fsCardNo']]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to save attendance:', ['error' => $e->getMessage(), 'attendance' => $attendance]);
                    }
                }
            }
        } else {
            // Tangani kesalahan jika gagal mengambil data dari API
            Log::error('Failed to fetch attendance data from external database');
        }
    }
}