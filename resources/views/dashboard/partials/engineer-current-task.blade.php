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
                                                @php
                                                $currentActivities = $activities->where('engineer_id', $item['fsCardNo']);
                                                $isOnProgress = $currentActivities->contains('isOnProgress', 1);
                                                @endphp
                                                @if ($isOnProgress)
                                                <span class="badge bg-label-info rounded-pill">On Remote</span>
                                                @elseif ($item['status1'] === 'Hadir')
                                                <span class="badge bg-label-success rounded-pill">Available</span>
                                                @elseif ($item['status1'] === 'Keluar')
                                                <span class="badge bg-label-warning rounded-pill">Out of Office</span>
                                                @else
                                                <span class="badge bg-label-danger rounded-pill">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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