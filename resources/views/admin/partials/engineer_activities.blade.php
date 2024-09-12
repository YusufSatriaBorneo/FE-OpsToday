@php
use Carbon\Carbon;
@endphp
<h5>Aktivitas Engineer: {{ $engineerName }}</h5>
<table class="table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jumlah Tiket Selesai</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach($activities as $activity)
        <tr>
            <td>{{ Carbon::parse($activity->date)->translatedFormat('l, d F Y') }}</td>
            <td>{{ $activity->count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>