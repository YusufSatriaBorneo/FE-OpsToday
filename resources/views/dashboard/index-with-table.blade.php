@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function fetchAbsensiData() {
            $.ajax({
                url: "{{ route('absensi.dashboard') }}", // Route to fetch data
                method: "GET",
                success: function(data) {
                    $('#absensi-table-body1').html(data); // Replace table content with new data
                }
            });
        }

        // Refresh data setiap 5 detik (5000 ms)
        function refreshPage() {
            console.log('Refreshing page...');
            location.reload();
        }
        // Jalankan refreshPage setiap 5 detik (5000 ms)
        setInterval(refreshPage, 30000);

        // Memuat data pertama kali saat halaman dimuat
        fetchAbsensiData();
    });
</script>
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
                        <!-- Congratulations card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body text-nowrap">
                                    <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations {{ $topEngineer }} 🎉</h5>
                                    <p class="mb-2">Telah Mencapai</p>
                                    <h4 class="text-primary mb-0">{{ $topTicketCount }} Tiket</h4>
                                    <p class="mb-2">Pada hari ini 🚀</p>
                                    <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                                </div>
                                <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83" alt="view sales" />
                            </div>
                        </div>
                        <!--/ Congratulations card -->
                        <!-- Engineer Today's Activities -->
                        <div class="col-md-6 col-lg-8">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Engineer Today's Activities</h5>
                                        <div class="dropdown">
                                            <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-more-2-line ri-24px"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="small mb-0"><span class="h6 mb-0">Rekap Jumlah Engineer</span> 😎</p>
                                </div>
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
                                                    <p class="mb-0">Available</p>
                                                    <h5 class="mb-0">{{ $statusCounts['Hadir'] + 2  }}</h5>
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
                                                    <p class="mb-0">Leave</p>
                                                    <h5 class="mb-0">{{ $statusCounts['Keluar'] }}</h5>
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
                                                    <p class="mb-0">Not Available</p>
                                                    <h5 class="mb-0">{{ $statusCounts['Absen'] }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Engineer Today's Activities -->
                        <!-- Engineer Current Task -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-1">Engineer Current Task</h5>
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
                                                {!! $chart->container() !!}
                                            </div>
                                            <div class="carousel-item">
                                                <div class="card overflow-hidden">
                                                    <div class="card-body">
                                                        @if(is_array($data) && !empty($data))
                                                        <div class="row row-cols-1 row-cols-md-5 g-4" id="absensi-card-container">
                                                            @foreach ($data as $item)
                                                            <div class="col">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center mb-3">
                                                                            <div class="avatar avatar-sm me-3">
                                                                                <img src="../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                                                                            </div>
                                                                            <small class="text-truncate">{{ $item['fsName'] }}</small>
                                                                        </div>
                                                                        <p class="card-text mb-2">
                                                                            @if ($item['status1'] === 'Absen')
                                                                            <span class="text-muted">Not Available</span>
                                                                            @else
                                                                            <span class="text-muted">{{ \Carbon\Carbon::parse($item['CI'])->format('H:i:s') }}</span>
                                                                            @endif
                                                                        </p>
                                                                        @if ($item['status1'] === 'Hadir')
                                                                        <span class="badge bg-label-success rounded-pill">Available</span>
                                                                        @elseif ($item['status1'] === 'Keluar')
                                                                        <span class="badge bg-label-warning rounded-pill">Leave</span>
                                                                        @else
                                                                        <span class="badge bg-label-info rounded-pill">Not Available</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            <div class="col">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center mb-3">
                                                                            <div class="avatar avatar-sm me-3">
                                                                                <img src="../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                                                                            </div>
                                                                            <small class="text-truncate">Data Center Operation</small>
                                                                        </div>
                                                                        <p class="card-text mb-2">
                                                                            <span class="text-muted">Group</span>
                                                                        </p>
                                                                        <span class="badge bg-label-success rounded-pill">Available</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center mb-3">
                                                                            <div class="avatar avatar-sm me-3">
                                                                                <img src="../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                                                                            </div>
                                                                            <small class="text-truncate">FP Operation</small>
                                                                        </div>
                                                                        <p class="card-text mb-2">
                                                                            <span class="text-muted">Group</span>
                                                                        </p>
                                                                        <span class="badge bg-label-success rounded-pill">Available</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <p class="text-center">Data absensi tidak tersedia</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var myCarousel = new bootstrap.Carousel(document.getElementById('engineerTaskSlider'), {
                                    interval: 5000,
                                    wrap: true
                                });
                            });
                        </script>
                        <script src="{{ $chart->cdn() }}"></script>
                        {{ $chart->script() }}
                        <style>
                            #engineerTaskSlider .carousel-control-prev,
                            #engineerTaskSlider .carousel-control-next {
                                display: none;
                            }

                            .cursor-pointer {
                                cursor: pointer;
                            }

                            .ri-lg {
                                font-size: 1.5rem;
                            }
                        </style>
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