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
            <h4 class="mb-4">Engineer Extra Miles</h4>
            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New Data</button>
            <!-- Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.engineer_extra_miles.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Add New Data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="engineer_id" class="form-label">Nama</label>
                                    <select class="form-control" id="engineer_id" name="engineer_id" required>
                                        <option value="">Pilih Nama</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->engineer_id }}" data-name="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="engineer_name" name="engineer_name">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="activity_name" class="form-label">Activity Name</label>
                                    <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Engineer ID</th>
                            <th>Nama</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Activity Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($engineerExtraMiles as $engineerExtraMile)
                        <tr>
                            <td>{{ $engineerExtraMile->engineer_id }}</td>
                            <td>{{ $engineerExtraMile->engineer_name }}</td>
                            <td>{{ $engineerExtraMile->start_date }}</td>
                            <td>{{ $engineerExtraMile->end_date }}</td>
                            <td>{{ $engineerExtraMile->activity_name }}</td>
                            <td>
                                <form action="{{ route('admin.engineer_extra_miles.destroy', $engineerExtraMile->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');
        var engineerSelect = document.getElementById('engineer_id');
        var engineerNameInput = document.getElementById('engineer_name');

        engineerSelect.addEventListener('change', function() {
            var selectedOption = engineerSelect.options[engineerSelect.selectedIndex];
            engineerNameInput.value = selectedOption.getAttribute('data-name');
        });
    });
</script>
@endsection