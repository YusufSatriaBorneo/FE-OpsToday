@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body pt-lg-10">
            <div class="row g-4">
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-success rounded shadow-xs">
                                <i class="ri-group-line ri-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0">Completed Ticket</p>
                            <h5 class="mb-0">{{ $completedTasksCount }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-warning rounded shadow-xs">
                                <i class="ri-group-line ri-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0">Not Started Ticket</p>
                            <h5 class="mb-0">{{ $inProgressTasksCount }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-info rounded shadow-xs">
                                <i class="ri-group-line ri-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0">Average Completion Time</p>
                            <h5 class="mb-0">{{ number_format($averageCompletionTimeInHours, 2) }} Hours</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($tasks2 as $task)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $task->title }}</h5>
                            <p class="card-text">{{ $task->ticketNo }}</p>
                            <a href="{{ route('admin.engineers') }}" class="btn btn-primary">Kerjakan</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection