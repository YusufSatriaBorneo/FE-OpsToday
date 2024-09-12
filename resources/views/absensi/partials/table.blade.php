<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            function fetchAbsensiData() {
                $.ajax({
                    url: "{{ route('absensi.data') }}", // Route to fetch data
                    method: "GET",
                    success: function(data) {
                        $('#absensi-table-body').html(data); // Replace table content with new data
                    }
                });
            }

            // Refresh data setiap 5 detik (5000 ms)
            setInterval(fetchAbsensiData, 1);

            // Memuat data pertama kali saat halaman dimuat
            fetchAbsensiData();
        });
    </script>
    <table class="table table-striped">
        <thead>
            <tr>

            </tr>
        </thead>
        <tbody id="absensi-table-body">
            @foreach ($data as $item)
            <tr>
                <td>{{ $item['fsIdNo'] }}</td>
                <td>{{ $item['fsName'] }}</td>
                <td>{{ \Carbon\Carbon::parse($item['fdDate'])->format('d-m-Y') }}</td>
                <td>{{ $item['fsDiv'] }}</td>
                <td>{{ \Carbon\Carbon::parse($item['CI'])->format('H:i:s') }}</td>
                <td>
                    @php
                    $color = '';
                    $icon = '';
                    if ($item['status1'] === 'Hadir') {
                    $color = 'green';
                    $icon = '🟢'; // Icon bulat hijau
                    } elseif ($item['status1'] === 'Keluar') {
                    $color = 'red';
                    $icon = '🔴'; // Icon bulat merah
                    } elseif ($item['status1'] === 'Absen') {
                    $color = 'black';
                    $icon = '🟡'; // Icon bulat kuning
                    }
                    @endphp
                    <span style="color: {{ $color }};">
                        {!! $icon !!} {{ $item['status1'] }}
                    </span>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>



</body>

</html>