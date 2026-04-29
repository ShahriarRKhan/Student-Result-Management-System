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
        .btn { background: #2563eb; color: #fff; border: 0; padding: 7px 12px; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc2626; }
        .btn-secondary { background: #4b5563; }
        .actions { display: flex; gap: 8px; align-items: center; }
        input[type="number"] { width: 100px; padding: 6px; }
        .alert { padding: 10px; border-radius: 6px; margin: 10px 0; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-error { background: #fef2f2; color: #991b1b; }
        .top-actions { display: flex; gap: 8px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>Result Details For Student: {{ $student->student_id }}</h1>
    <p>Department: {{ $student->department }} | Semester: {{ $student->semester }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="top-actions">
        <a href="{{ route('teacher.results.index') }}"><button class="btn btn-secondary" type="button">Back</button></a>

        <form action="{{ route('teacher.results.publish', $student->student_id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button class="btn" type="submit">Publish All Subjects</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Published</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
                @php
                    $row = $results->get($subject->subject_code);
                @endphp
                <tr>
                    <td>{{ $subject->subject_code }} - {{ $subject->subject_name }}</td>
                    @if($row)
                        <td>{{ $row->marks }}</td>
                        <td>{{ $row->grade }}</td>
                        <td>{{ $row->is_published ? 'Yes' : 'No' }}</td>
                        <td>
                            <form class="actions" action="{{ route('teacher.results.update', $row->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="number" step="0.01" min="0" max="100" name="marks" value="{{ $row->marks }}" required>
                                <label>
                                    <input type="checkbox" name="publish" value="1" @checked($row->is_published)> Publish
                                </label>
                                <button class="btn" type="submit">Save</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('teacher.results.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this subject result?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    @else
                        <td colspan="5">No marks added for this subject yet.</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
