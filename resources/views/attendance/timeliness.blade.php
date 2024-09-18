<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeliness Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Timeliness Report</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Engineer ID</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeliness as $engineer_id => $status)
                    <tr>
                        <td>{{ $engineer_id }}</td>
                        <td>{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>