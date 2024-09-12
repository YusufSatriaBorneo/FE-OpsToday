<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Duration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Task Duration</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Engineer Number</th>
                    <th>Ticket No</th>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->engineerNumber }}</td>
                        <td>{{ $task->ticketNo }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignedTo }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>