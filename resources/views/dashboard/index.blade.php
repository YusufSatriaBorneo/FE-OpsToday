@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">
        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('components.navbar')
            <!-- / Navbar -->
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row gy-4">
                        @include('dashboard.partials.congratulations-card')
                        @include('dashboard.partials.engineer-today-activities')
                        @include('dashboard.partials.engineer-current-task')
                    </div>
                </div>
                <!-- / Content -->
                <!-- Footer -->
                @include('components.footer')
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
@endsection

@include('dashboard.partials.scripts')
@include('dashboard.partials.styles')