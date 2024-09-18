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
                                                @foreach($extraMilesData as $data)
                                                <tr>
                                                    <td>{{ $data->engineer_name }}</td>
                                                    <td>{{ $data->extra_miles_count }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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