@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Manajemen Tiket</h4>
            <form method="GET" action="{{ route('admin.tickets') }}">
                <div class="mb-4">
                    <label for="engineer_id" class="form-label">Filter berdasarkan Nama Engineer</label>
                    <select name="engineer_id" id="engineer_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Engineer</option>
                        @foreach($engineerNames as $id => $name)
                        <option value="{{ $id }}" {{ $selectedEngineer == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Engineer ID</th>
                            <th>Jumlah Tiket Keseluruhan</th>
                            <th>Jumlah Tiket Selesai</th>
                            <th>Jumlah Tiket Belum Selesai</th>
                            <th>Waktu Keseluruhan</th>
                            <th>Waktu Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($data as $engineer)
                        <tr>
                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#engineerModal" data-engineer-id="{{ $engineer['engineer_id'] }}">{{ $engineer['engineer_name'] }}</a></td>
                            <td>{{ $engineer['engineer_id'] }}</td>
                            <td>{{ $engineer['total_tickets'] }}</td>
                            <td>{{ $engineer['completed_tickets'] }}</td>
                            <td>{{ $engineer['not_started_tickets'] }}</td>
                            <td> @php
                                $totalMinutes = $engineer['total_time'];
                                $hours = intdiv($totalMinutes, 60);
                                $minutes = $totalMinutes % 60;
                                $formattedTotalTime = '';
                                if ($hours > 0) {
                                $formattedTotalTime .= $hours . ' jam ';
                                }
                                if ($minutes > 0) {
                                $formattedTotalTime .= $minutes . ' menit';
                                }
                                if ($hours == 0 && $minutes == 0) {
                                $formattedTotalTime = '0 menit';
                                }
                                @endphp
                                {{ $formattedTotalTime }}
                            </td>
                            <td> @php
                                $averageMinutes = $engineer['average_time'];
                                $hours = intdiv($averageMinutes, 60);
                                $minutes = $averageMinutes % 60;
                                $formattedAverageTime = '';
                                if ($hours > 0) {
                                $formattedAverageTime .= $hours . ' jam ';
                                }
                                if ($minutes > 0) {
                                $formattedAverageTime .= $minutes . ' menit';
                                }
                                if ($hours == 0 && $minutes == 0) {
                                $formattedAverageTime = '0 menit';
                                }
                                @endphp
                                {{ $formattedAverageTime }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="engineerModal" tabindex="-1" aria-labelledby="engineerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <!-- Konten modal akan dimuat di sini -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var engineerModal = document.getElementById('engineerModal');
        engineerModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var engineerId = button.getAttribute('data-engineer-id');

            // Lakukan permintaan AJAX untuk mengambil data aktivitas engineer
            fetch(`/admin/engineer/${engineerId}/activities`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = data;
                });
        });
    });
</script>
@endsection