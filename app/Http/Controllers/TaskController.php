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

class TaskController extends Controller
{
    public function showIds()
    {
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602', 'Z126577','Z10066', 'operation01', 'operation02','Z126457'];
        $responses = [];
        $engineerTicketCount = [];
        $colors = [];

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

         // Ambil semua tiket dari database
        //  $dbTickets = EngineerTask::pluck('ticketNo')->toArray();

         // Cari tiket yang ada di database tapi tidak ada di API
        //  $completedTickets = array_diff($dbTickets, $apiTickets);
 
         // Tandai tiket yang sudah tidak ada di API sebagai selesai
        //  EngineerTask::whereIn('ticketNo', $completedTickets)->update(['status' => 'Completed']);

        // Kumpulkan semua engineer yang memiliki lebih dari 10 tiket
        $overloadedEngineers = [];
        foreach ($engineerTicketCount as $engineer => $count) {
            if ($count > 10) {
                $overloadedEngineers[] = [
                    'engineer' => $engineer,
                    'ticketCount' => $count,
                ];
            }
        }
        $colors = [];
        foreach ($engineerTicketCount as $engineer => $count) {
            if ($count > 10) {
                $colors[] = '#FF0000'; // Merah untuk > 10 tiket
            } else {
                $colors[] = '#822EFF'; // Hijau untuk ≤ 10 tiket
            }
        }
        $chart = (new LarapexChart)->barChart()
            ->addData('Total Tiket', array_values($engineerTicketCount))
            ->setXAxis(array_keys($engineerTicketCount))
            //->setXAxis($engineerNames) // Set X-axis dengan nama yang sudah diproses
            ->setFontFamily('Inter')
            ->setGrid()
            ->setFontColor('#000000')
            ->setColors($colors) // Set warna berdasarkan kondisi
            ->setHeight(400) // atur tinggi chart
            ->setDataLabels(true)
            ->setToolbar(true)
            ->setHorizontal(false);

        // Konfigurasi tambahan menggunakan array options
        $chart->setOptions([
            'xaxis' => [
                'labels' => [
                    'rotate' => 0, // Set tulisan horizontal
                ]
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Jumlah Tiket'
                ]
            ],
            'dataLabels' => [
                'enabled' => true,
                'position' => 'top',
                'style' => [
                    'colors' => ['#000000'],
                    'fontSize' => '15px',
                    'fontWeight' => 'bold'
                ]
            ],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '5%', // Atur lebar bar di sini (50% adalah contoh, bisa disesuaikan)
                    'borderRadius' => 5,    // Opsional: menambahkan sudut melengkung pada bar
                    'dataLabels' => [
                        'position' => 'top' // Memastikan label ada di atas bar
                    ],
                    'barPadding' => 0.2 // Menambah jarak antar grup bar
                ] 
            ],
            'stroke' => [
                'show' => false // Menghilangkan garis tepi bar untuk tampilan yang lebih bersih
            ],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'plotOptions' => [
                            'bar' => [
                                'columnWidth' => '80%' // Bar lebih lebar pada layar kecil
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // Mengirim data dan jumlah tiket per engineer ke view
        // return view('consumeAPI.engineerTask', compact('responses', 'engineerTicketCount', 'chart'));
        return $chart;
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
}
