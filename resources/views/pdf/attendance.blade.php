<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Total Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record['date'] ?? 'N/A' }}</td>
                <td>{{ $record['check_in'] ?? '-' }}</td>
                <td>{{ $record['check_out'] ?? '-' }}</td>
                <td>{{ $record['total_hours'] ?? '-' }}</td>
                <td>{{ ucfirst($record['status'] ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
