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
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function showIds()
    {
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602'];
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
                    }
                }
            } else {
                $responses[$id] = ['error' => 'Failed to fetch data'];
            }
        }

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
        if (!empty($overloadedEngineers)) {
            $now = Carbon::now();
            $today8am = Carbon::today()->setHour(23)->setMinute(57)->setSecond(0);

            // Cek apakah sudah pernah kirim email hari ini
            $lastEmail = EmailLog::whereDate('sent_at', $now->toDateString())->first();

            if (!$lastEmail && $now->greaterThanOrEqualTo($today8am)) {
                // Kirim email
                Mail::to('Bayu.Adhitya@kpc.co.id')->send(new TaskOverloadMail($overloadedEngineers));

                // Catat log pengiriman email
                EmailLog::create([
                    'sent_at' => $now,
                    'recipient' => 'Bayu.Adhitya@kpc.co.id',
                    'subject' => 'Task Overload Notification',
                    'content' => json_encode($overloadedEngineers)
                ]);
            }
        }

        // Kirim satu email jika ada engineer yang overload
        // if (!empty($overloadedEngineers)) {
        //     Mail::to('Bayu.Adhitya@kpc.co.id')->send(new TaskOverloadMail($overloadedEngineers));
        // }
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
            ->setFontFamily('DM Sans')
            ->setGrid()
            ->setFontColor('#000000')
            ->setColors($colors) // Set warna berdasarkan kondisi
            ->setHeight(400) // atur tinggi chart
            ->setDataLabels(true)
            ->setToolbar(true)
            ->setHorizontal(false);

        // Konfigurasi tambahan menggunakan array options
        $chart->setOptions([
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
        $ids = ['Z109553', 'Z80469', 'Z119238', 'Z124187', 'Z125074', 'Z117607', 'Z110780', 'Z100665', 'Z124644', 'Z126735', 'Z125602'];
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
}
