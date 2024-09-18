<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
            <div class="card-body text-nowrap">
                @if($engineerOfTheDay)
                @php
                $engineerName = $engineerNames[$engineerOfTheDay->engineer_id] ?? 'Unknown';
                @endphp
                <h5 class="card-title mb-0 flex-wrap text-nowrap">Awesome job, {{ $engineerName }}</h5>
                <p class="mb-2">You've Completed</p>
                <h4 class="text-primary mb-0">{{ $engineerOfTheDay->count }} Tickets</h4>
                <p class="mb-2">and earned the title of Engineer of The Day 🌟🎉</p>
                <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
                <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83" alt="view sales" />
                @else
                <p>No Data</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-6">
        <div class="card mb-4"> <!-- Menambahkan mb-4 untuk margin bawah -->
            <div class="card-body text-nowrap">
                <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations {{ $topEngineer }} 🎉</h5>
                <p class="mb-2">Telah Mencapai</p>
                <h4 class="text-primary mb-0">{{ $topTicketCount }} Tiket</h4>
                <p class="mb-2">Engineer of The Month 🚀</p>
                <a href="javascript:;" class="btn btn-sm btn-primary">View</a>
            </div>
            <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83" alt="view sales" />
        </div>
    </div>
</div>