<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .btn { background: #2563eb; color: #fff; border: 0; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc2626; }
        .btn-link { color: #2563eb; text-decoration: none; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 10px; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-error { background: #fef2f2; color: #991b1b; }
        input, select { padding: 8px; width: 100%; box-sizing: border-box; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>{{ $title }}</h1>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('teacher.subjects.index') }}"><button class="btn" type="button">Manage Subjects</button></a>
            <form method="POST" action="{{ route('teacher.logout') }}">
                @csrf
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </div>
    </div>

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

    <div class="card">
        <h2>Add/Update Student Marks (All Subjects)</h2>

        @if($students->isEmpty())
            <p>No students found. Please register students first.</p>
        @elseif($subjects->isEmpty())
            <p>No subjects found. Please add subjects first.</p>
        @else
            <form method="POST" action="{{ route('teacher.results.store') }}">
                @csrf

                <label for="student_id">Student ID</label>
                <select name="student_id" id="student_id" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->student_id }}" @selected(old('student_id') === $student->student_id)>
                            {{ $student->student_id }} ({{ $student->department }}, Semester {{ $student->semester }})
                        </option>
                    @endforeach
                </select>

                <h3 style="margin-top:16px;">Subject-wise Marks</h3>
                <div class="grid">
                    @foreach($subjects as $subject)
                        <div>
                            <label for="marks_{{ $subject->subject_code }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                id="marks_{{ $subject->subject_code }}"
                                name="marks[{{ $subject->subject_code }}]"
                                value="{{ old('marks.' . $subject->subject_code) }}"
                                placeholder="0 - 100"
                            >
                        </div>
                    @endforeach
                </div>

                <label style="display:block;margin-top:12px;">
                    <input type="checkbox" name="publish" value="1" @checked(old('publish'))>
                    Publish immediately so student can view
                </label>

                <button class="btn" type="submit" style="margin-top:12px;">Save Marks</button>
            </form>
        @endif
    </div>

    <div class="card">
        <h2>Saved Results By Student</h2>

        @if($resultsByStudent->isEmpty())
            <p>No results added yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Total Subjects</th>
                        <th>Published Subjects</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultsByStudent as $studentId => $results)
                        <tr>
                            <td>{{ $studentId }}</td>
                            <td>{{ $results->count() }}</td>
                            <td>{{ $results->where('is_published', true)->count() }}</td>
                            <td>
                                <a class="btn-link" href="{{ route('teacher.results.show', $studentId) }}">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
