<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #eef4ff;
            --text: #14213d;
            --muted: #5c6b82;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --border: #dbe4f0;
            --danger: #dc2626;
            --success-bg: #ecfdf5;
            --success-text: #065f46;
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
            position: sticky;
            top: 0;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link,
        .btn {
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
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .nav-link,
        .btn {
            background: var(--primary);
        }

        .nav-link:hover,
        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        .hero {
            background: linear-gradient(135deg, #dbeafe 0%, #ffffff 62%);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 18px 40px rgba(37, 99, 235, 0.08);
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: clamp(2rem, 4vw, 3rem);
        }

        .hero p {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 1.05rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .summary-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
        }

        .summary-card span {
            display: block;
            color: var(--muted);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .summary-card strong {
            font-size: 2rem;
            line-height: 1;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
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

        @media (max-width: 640px) {
            .navbar {
                padding: 16px 18px;
                flex-direction: column;
                align-items: stretch;
            }

            .nav-actions {
                justify-content: space-between;
                flex-wrap: wrap;
            }

            .brand {
                text-align: center;
            }

            .nav-link,
            .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand">{{ $student->student_id }}</div>

        <div class="nav-actions">
            <a class="nav-link" href="{{ route('student.profile.edit') }}">Profile</a>

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

        <section class="hero">
            <h1>Published Result Summary</h1>
            <p>{{ $student->student_id }} • {{ strtoupper($student->department) }} • Semester {{ $student->semester }}</p>

            <div class="summary-grid">
                <div class="summary-card">
                    <span>Total Subjects Published</span>
                    <strong>{{ $results->count() }}</strong>
                </div>
                <div class="summary-card">
                    <span>Total Credits</span>
                    <strong>{{ $totalCredit }}</strong>
                </div>
                <div class="summary-card">
                    <span>CGPA</span>
                    <strong>{{ number_format($cgpa, 2) }}</strong>
                </div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('student.results.show', $student->student_id) }}" class="btn">View Detailed Result</a>
            </div>
        </section>
    </main>
</body>
</html>
