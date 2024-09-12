<div class="container">
        <h1>Absensi IT</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID No</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Division</th>
                    <th>Check-In</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item['fsIdNo'] }}</td>
                        <td>{{ $item['fsName'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['fdDate'])->format('d-m-Y') }}</td>
                        <td>{{ $item['fsDiv'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['CI'])->format('H:i:s') }}</td>
                        <td>{{ $item['status1'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>