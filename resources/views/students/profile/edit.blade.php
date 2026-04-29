<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --text: #14213d;
            --muted: #5c6b82;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --border: #dbe4f0;
            --danger: #dc2626;
            --success-bg: #ecfdf5;
            --success-text: #065f46;
            --error-bg: #fef2f2;
            --error-text: #991b1b;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, var(--bg) 100%);
            color: var(--text);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 32px;
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--text);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn,
        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #fff;
            border: 0;
            padding: 10px 16px;
            border-radius: 999px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn,
        .nav-link {
            background: var(--primary);
        }

        .btn:hover,
        .nav-link:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 18px 40px rgba(20, 33, 61, 0.06);
        }

        h1 {
            margin: 0 0 10px;
            font-size: 2rem;
        }

        .subtitle {
            margin: 0 0 24px;
            color: var(--muted);
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
        }

        input[readonly] {
            background: #f8fafc;
            color: var(--muted);
        }

        .helper {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('student.index') }}" class="brand">{{ $student->student_id }}</a>

        <div class="nav-actions">
            <a class="nav-link" href="{{ route('student.index') }}">Home</a>

            <form action="{{ route('student.logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="card">
            <h1>Edit Profile</h1>
            <p class="subtitle">Update your account information here.</p>

            <form action="{{ route('student.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="student_id">Student ID</label>
                    <input id="student_id" name="student_id" type="text" value="{{ $student->student_id }}" readonly>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $student->email) }}" required>
                </div>

                <div class="field">
                    <label for="department">Department</label>
                    <input id="department" name="department" type="text" value="{{ old('department', $student->department) }}" required>
                </div>

                <div class="field">
                    <label for="semester">Semester</label>
                    <input id="semester" name="semester" type="number" min="1" max="8" value="{{ old('semester', $student->semester) }}" required>
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" placeholder="Leave blank to keep current password">
                    <span class="helper">Only fill this in if you want to change your password.</span>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password">
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Save Changes</button>
                    <a class="nav-link" href="{{ route('student.index') }}">Back to Dashboard</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
