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
                        <div class="row">
                            <div class="col-md-4 col-lg-4">
                                <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations {{ $topEngineer }} 🎉</h5>
                                        <p class="mb-2">You've Completed</p>
                                        <h4 class="text-primary mb-0">{{ $topTicketCount }} Tickets</h4>
                                        <p class="mb-2">and You've excelled as the best engineer this month 🌟</p>
                                        <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
                                    <div class="card-body text-nowrap">
                                        @if($engineerOfTheDay)
                                        @php
                                        $engineerName = $engineerNames[$engineerOfTheDay->engineer_id] ?? 'Unknown';
                                        @endphp
                                        <h5 class="card-title mb-0 flex-wrap text-nowrap">Great job, {{ $engineerName }}</h5>
                                        <p class="mb-2">You've Completed</p>
                                        <h4 class="text-primary mb-0">{{ $engineerOfTheDay->count }} Tickets</h4>
                                        <p class="mb-2">and You've been the top engineer today 🌟</p>
                                        <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                                        <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83" alt="view sales" />
                                        @else
                                        <p>No Data</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="card mb-4 style="height: 100%;"> <!-- Menambahkan mb-4 untuk margin bawah dan h-100 untuk tinggi penuh -->
                                    <div class="card-body text-center">
                                        <div id="current-date" style="font-size: 18px;"></div>
                                        <div id="digital-clock" class="mb-3" style="font-size: 48px; font-weight: bold;"></div>
                                        <div id="current-day" style="font-size: 18px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-0 mb-4"> <!-- Mengurangi margin atas dan menambahkan margin bawah -->
                            <div class="col-md-6 col-lg-8">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="card-title m-0 me-2">Engineer Productivity</h5>
                                            <div class="dropdown">
                                                <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-more-2-line ri-24px"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                                    <a class="dropdown-item" href="/login">Login</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-3"> <!-- Mengurangi padding atas -->
                                        <div class="row g-3"> <!-- Mengurangi jarak antar elemen -->
                                            <div class="col-md-4 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-success rounded shadow-xs">
                                                            <i class="ri-group-line ri-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-2"> <!-- Mengurangi margin -->
                                                        <p class="mb-0">Available</p>
                                                        <h5 class="mb-0" id="status-hadir"></h5>
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
                                                    <div class="ms-2"> <!-- Mengurangi margin -->
                                                        <p class="mb-0">Out of Office</p>
                                                        <h5 class="mb-0" id="status-keluar"></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-danger rounded shadow-xs">
                                                            <i class="ri-close-line ri-24px"></i> <!-- Ikon untuk tiket tidak tersedia -->
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0">Not Available</p>
                                                        <h5 class="mb-0" id="status-absen"></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-success rounded shadow-xs">
                                                            <i class="ri-check-line ri-24px"></i> <!-- Ikon untuk tiket selesai -->
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0">Completed Ticket ({{ $currentMonth }})</p>
                                                        <h5 class="mb-0">{{ $completedTasksCount }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-warning rounded shadow-xs">
                                                            <i class="ri-time-line ri-24px"></i> <!-- Ikon untuk tiket belum dimulai -->
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
                                                    <div class="ms-2"> <!-- Mengurangi margin -->
                                                        <p class="mb-0">On Leave</p>
                                                        <h5 class="mb-0" id="status-on-leave"></h5>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="card mb-4 h-100"> <!-- Menambahkan mb-4 untuk margin bawah dan h-100 untuk tinggi penuh -->
                                    <div class="card-body text-center">
                                    <p class="mb-0" >Clock in timeliness</p>
                                    <br>
                                    <br>
                                    <h5 class="mb-0" style="font-size: 48px; font-weight: bold;">94%</h5>
                                    </div>
                                </div>
                            </div>
                            <!-- Card lainnya -->
                        </div>
                        <div class="row"> <!-- Menambahkan row baru untuk Team Status Overview -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Team Status Overview</h5>
                                            <div class="custom-arrow-container">
                                                <i class="ri-arrow-left-s-line ri-lg text-secondary cursor-pointer custom-arrow" data-bs-target="#engineerTaskSlider" data-bs-slide="prev"></i>
                                                <i class="ri-arrow-right-s-line ri-lg text-secondary cursor-pointer custom-arrow" data-bs-target="#engineerTaskSlider" data-bs-slide="next"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="engineerTaskSlider" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="card overflow-hidden">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <canvas id="engineerTasksChart" width="300" height="90"></canvas>
                                                                </div>
                                                            </div>
                                                            <p class="text-center" id="no-data-message" style="display: display;">A</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card overflow-hidden">
                                                        <div class="card-body">
                                                            <div class="row row-cols-1 row-cols-md-5 g-4" id="absensi-card-container">
                                                                <!-- Konten akan dimuat oleh JavaScript -->
                                                            </div>
                                                            <p class="text-center" id="no-data-message" style="display: display;"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tambahkan konten baru di sini -->
                                                <div class="carousel-item">
                                                    <div class="card overflow-hidden">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-0">Engineer On Leave</h5>
                                                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Engineer</th>
                                                                            <th>Leave Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="engineer-leave-content">
                                                                        <!-- Data akan dimuat oleh JavaScript -->
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card overflow-hidden">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-0">Extra Miles Contribution</h5>
                                                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Engineer</th>
                                                                            <th>Count of Extra Miles</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse($extraMilesData as $data)
                                                                        <tr>
                                                                            <td>{{ $data->engineer_name }}</td>
                                                                            <td>{{ $data->extra_miles_count }}</td>
                                                                        </tr>
                                                                        @empty
                                                                        <tr>
                                                                            <td colspan="2" class="text-center">No engineer on leave</td>
                                                                        </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Akhir dari konten baru -->
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#engineerTaskSlider" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#engineerTaskSlider" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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