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
                            <h5 class="mb-0">{{ $statusCounts['Hadir'] }}</h5>
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
                            <h5 class="mb-0">{{ $statusCounts['Keluar'] }}</h5>
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
                            <h5 class="mb-0">{{ $statusCounts['Absen'] }}</h5>
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
                            <p class="mb-0">Completed Ticket</p>
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
                            <p class="mb-0">On Remote</p>
                            <h5 class="mb-0">{{ $statusCounts['Hadir'] }}</h5>
                        </div>
                    </div>
                </div>
                <!-- Card baru pertama -->
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 flex-wrap text-nowrap">Engineer On Leave</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Engineer</th>
                                        <th>Leave Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Bayu</td>
                                        <td>2023-09-12</td>
                                    </tr>
                                    <!-- Tambahkan data lainnya di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card baru kedua -->
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 flex-wrap text-nowrap">Extra Miles Engineer</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Engineer</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Oby</td>
                                        <td>Task at GOR</td>
                                    </tr>
                                    <!-- Tambahkan data lainnya di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--
            <div class="row gy-4">  Menambahkan row untuk mengatur card dalam satu baris
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations {{ $topEngineer }} 🎉</h5>
                            <p class="mb-2">Telah Mencapai</p>
                            <h4 class="text-primary mb-0">{{ $topTicketCount }} Tiket</h4>
                            <p class="mb-2">Engineer of The Day 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                        </div>
                        <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-3 mb-3" width="60" alt="view sales" /> 
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card mb-3"> 
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations {{ $topEngineer }} 🎉</h5>
                            <p class="mb-2">Telah Mencapai</p>
                            <h4 class="text-primary mb-0">{{ $topTicketCount }} Tiket</h4>
                            <p class="mb-2">Engineer of The Month 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                        </div>
                        <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-3 mb-3" width="60" alt="view sales" /> 
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>