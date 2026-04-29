<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-top: 16px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
        .btn { background: #2563eb; color: #fff; border: 0; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc2626; }
        .btn-secondary { background: #4b5563; }
        .actions { display: flex; gap: 6px; }
        .alert { padding: 10px; border-radius: 6px; margin-top: 12px; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-error { background: #fef2f2; color: #991b1b; }
        .inline-form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .inline-form input { min-width: 110px; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>{{ $title }}</h1>
        <div class="actions">
            <a href="{{ route('teacher.results.index') }}"><button class="btn btn-secondary" type="button">Result Page</button></a>
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
        <h2>Add New Subject</h2>
        <form method="POST" action="{{ route('teacher.subjects.store') }}">
            @csrf
            <div class="grid">
                <div>
                    <label for="subject_code">Subject Code</label>
                    <input type="text" id="subject_code" name="subject_code" value="{{ old('subject_code') }}" required>
                </div>
                <div>
                    <label for="subject_name">Subject Name</label>
                    <input type="text" id="subject_name" name="subject_name" value="{{ old('subject_name') }}" required>
                </div>
                <div>
                    <label for="credit">Credit</label>
                    <input type="number" id="credit" name="credit" min="1" max="10" value="{{ old('credit') }}" required>
                </div>
            </div>
            <button class="btn" style="margin-top:10px;" type="submit">Add Subject</button>
        </form>
    </div>

    <div class="card">
        <h2>All Subjects</h2>
        @if($subjects->isEmpty())
            <p>No subjects found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Credit</th>
                        <th>Used In Results</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $subject->subject_code }}</td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->credit }}</td>
                            <td>{{ $subject->results_count }}</td>
                            <td>
                                <form class="inline-form" method="POST" action="{{ route('teacher.subjects.update', $subject->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="subject_code" value="{{ $subject->subject_code }}" required>
                                    <input type="text" name="subject_name" value="{{ $subject->subject_name }}" required>
                                    <input type="number" name="credit" min="1" max="10" value="{{ $subject->credit }}" required>
                                    <button class="btn" type="submit">Update</button>
                                </form>

                                <form style="margin-top:8px;" method="POST" action="{{ route('teacher.subjects.destroy', $subject->id) }}" onsubmit="return confirm('Delete this subject?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
