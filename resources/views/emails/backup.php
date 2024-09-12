<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Overload Alert</title>
</head>
<body>
    <h1>Task Overload Alert</h1>
    <p>Engineer {{ $engineer }} is handling {{ $ticketCount }} tickets.</p>
    <p>This exceeds the limit of 10 tasks.</p>
    <p>Please review and redistribute tasks accordingly.</p>
    <p>Regards,</p>
    <p>Team DevOps Engineer</p>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ops Today Maximum Tickets</title>
</head>
<body>
    <h3>Ops Today Ticket Alert</h3>

    @foreach ($overloadedEngineers as $engineerData)
        <p>{{ $engineerData['engineer'] }} sedang mengerjakan {{ $engineerData['ticketCount'] }} tiket.</p>
    @endforeach

    <p>Please review and complete the ticket.</p>
    <p>Regards,</p>
    <p><i>Hanya Testing</i></p>
    <p>Team DevOps Engineer</p>
</body>
</html>
 -->
</body>
</html>
