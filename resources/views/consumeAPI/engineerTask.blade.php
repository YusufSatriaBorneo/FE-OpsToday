<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engineer Task List</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Engineer Task List</h1>

    <h2>Ticket Count per Engineer</h2>
    <table>
        <thead>
            <tr>
                <th>Engineer Name</th>
                <th>Ticket Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($engineerTicketCount as $engineer => $count)
            <tr>
                <td>{{ $engineer }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @foreach ($responses as $id => $response)
    <h2>Ticket Details for ID: {{ $id }}</h2>

    @if (isset($response['error']))
    <p>Error: {{ $response['error'] }}</p>
    @elseif (isset($response['statusCode']) && $response['statusCode'] == 200)
    <p>Status: {{ $response['statusMessage'] }}</p>
    <table>
        <thead>
            <tr>
                <th>Ticket No</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Title</th>
                <th>Computer Name</th>
                <th>Requested For</th>
                <th>Requested By</th>
                <th>Status</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($response['listTicket'] as $ticket)
            <tr>
                <td>{{ $ticket['ticketNo'] }}</td>
                <td>{{ $ticket['category'] }}</td>
                <td>{{ $ticket['subCategory'] }}</td>
                <td>{{ $ticket['title'] }}</td>
                <td>{{ $ticket['computerName'] }}</td>
                <td>{{ $ticket['requestedFor'] }}</td>
                <td>{{ $ticket['requestedBy'] }}</td>
                <td>{{ $ticket['status'] }}</td>
                <td>{{ $ticket['assignedTo'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No data found or unable to fetch data.</p>
    @endif
    @endforeach
    <div class="container px-4 mx-auto">
        <div class="p-6 m-20 bg-white rounded shadow">
            {!! $chart->container() !!}
        </div>
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}
    </div>
</body>

</html>