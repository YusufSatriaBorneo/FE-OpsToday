@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Engineer Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <p class="note">Setelah close tiket di SIHEPI tunggu 1-30 detik untuk ticket yang lama hilang</p>
</div>
<div class="container mt-5">
    @auth
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href="{{ route('admin.logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="menu-icon tf-icons ri-logout-box-line"></i>
        Logout
    </a>
    @endauth
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#completedTicketsModal">
        <i class="menu-icon tf-icons ri-file-list-line"></i>
        View Completed Tickets
    </button>

    <div class="row">
        @foreach($tasks as $task)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $task->title }}</h5>
                    <p class="card-text">{{ $task->ticketNo }}</p>
                    @foreach($currentActivity as $activity)
                    @if($activity->ticketNo == $task->ticketNo)
                    <span class="badge bg-warning text-dark">In Progress</span>
                    @endif
                    @endforeach
                    <form action="{{ route('engineer.activities.store', ['ticketNo' => $task->ticketNo]) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="ticket_no" value="{{ $task->ticketNo }}">
                        <input type="hidden" name="engineerNumber" value="{{ $task->engineerNumber }}">
                        <button type="submit" class="btn btn-primary">Kerjakan</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Tambahkan skrip JavaScript untuk auto-refresh-->
    <script>
        setTimeout(function() {
            location.reload();
        }, 10000); // 5000 milidetik = 5 detik 
    </script>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>
<!-- Modal -->
<div class="modal fade" id="completedTicketsModal" tabindex="-1" aria-labelledby="completedTicketsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completedTicketsModalLabel">Completed Tickets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket No</th>
                            <th>Title</th>
                            <th>Durasi Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticketNo }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>@php
                                $hours = intdiv($ticket->completion_time, 60);
                                $minutes = $ticket->completion_time % 60;
                                $formattedTime = '';
                                if ($hours > 0) {
                                $formattedTime .= $hours . ' jam ';
                                }
                                if ($minutes > 0) {
                                $formattedTime .= $minutes . ' menit';
                                }
                                if ($hours == 0 && $minutes == 0) {
                                $formattedTime = '0 menit';
                                }
                                @endphp
                                {{ $formattedTime }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection