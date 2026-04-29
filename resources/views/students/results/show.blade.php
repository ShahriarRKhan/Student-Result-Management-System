<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .btn { background: #2563eb; color: #fff; border: 0; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Published Result</h1>
    <p><strong>Student ID:</strong> {{ $student->student_id }}</p>

    @if($results->isEmpty())
        <p>No published result found yet. Please wait for your teacher to publish.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Credit</th>
                    <th>Marks</th>
                    <th>Grade</th>
                    <th>Published By</th>
                    <th>Published At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{ $result->subject->subject_code ?? $result->subject_id }}</td>
                        <td>{{ $result->subject->subject_name ?? '-' }}</td>
                        <td>{{ $result->subject->credit ?? '-' }}</td>
                        <td>{{ $result->marks }}</td>
                        <td>{{ $result->grade }}</td>
                        <td>{{ $result->teacher->name ?? 'Teacher' }}</td>
                        <td>{{ optional($result->published_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p style="margin-top: 12px;">
        <a href="{{ route('student.index') }}"><button class="btn" type="button">Back to Dashboard</button></a>
    </p>
</body>
</html>
