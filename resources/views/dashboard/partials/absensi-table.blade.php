@if(is_array($absensiData) && !empty($absensiData))
@foreach ($absensiData as $item)
<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-4">
                <img src="../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
            </div>
            <div>
                <h6 class="mb-0 text-truncate">{{ $item['fsName'] }}</h6>
                <small class="text-truncate">{{ $item['fsDiv'] }}</small>
            </div>
        </div>
    </td>
    <td class="text-truncate">{{ \Carbon\Carbon::parse($item['fdDate'])->format('d-m-Y') }}</td>
    <td class="text-truncate">
        <div class="d-flex align-items-center">
            <span>{{ \Carbon\Carbon::parse($item['CI'])->format('H:i:s') }}</span>
        </div>
    </td>
    <td>
        @if ($item['status1'] === 'Hadir')
        <span class="badge bg-label-success rounded-pill">Hadir</span>
        @elseif ($item['status1'] === 'Keluar')
        <span class="badge bg-label-warning rounded-pill">Keluar</span>
        @else
        <span class="badge bg-label-secondary rounded-pill">Absen</span>
        @endif
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="4" class="text-center">Data absensi tidak tersedia</td>
</tr>
@endif