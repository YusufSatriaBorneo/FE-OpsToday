@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manajemen Engineer</h5>
                    <p class="card-text">Kelola data engineer.</p>
                    <a href="{{ route('admin.engineers') }}" class="btn btn-primary">Kelola Engineer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manajemen User</h5>
                    <p class="card-text">Kelola data user.</p>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">Kelola User</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manajemen Tiket</h5>
                    <p class="card-text">Kelola data tiket.</p>
                    <a href="{{ route('admin.tickets') }}" class="btn btn-primary">Kelola Tiket</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection