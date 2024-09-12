@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container mt-5">
@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">In Progress Ticket</h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket No</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($engineerOnProgress as $engineerOnProgress)     
                        <tr>
                            <td>{{ $engineerOnProgress->ticketNo }}</td>
                            <td>{{ $engineerOnProgress->engineer_id }}</td>
                            <td>{{ $engineerOnProgress->status }}</td>
                            <td>{{ $engineerOnProgress->created_at }}</td>
                            <td>
                            <form action="{{ route('engineerOnProgress.destroy', $engineerOnProgress->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger waves-effect">
                                        <i class="ri-delete-bin-6-line me-2"></i> Delete
                                    </button>
                                </form>
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