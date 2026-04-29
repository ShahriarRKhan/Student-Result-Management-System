<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Management System</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Georgia, "Times New Roman", serif;
            color: #1f2937;
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.45), transparent 32%),
                linear-gradient(135deg, #f7f3e8 0%, #d6e4f0 52%, #b8d8c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .panel {
            width: 100%;
            max-width: 960px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            box-shadow: 0 24px 70px rgba(31, 41, 55, 0.16);
            overflow: hidden;
            backdrop-filter: blur(12px);
        }

        .hero {
            padding: 56px 32px;
            text-align: center;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 18px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #183153;
            color: #f8fafc;
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 1.05;
            color: #102542;
            margin-bottom: 16px;
        }

        .subtitle {
            max-width: 640px;
            margin: 0 auto 40px;
            font-size: 1.05rem;
            line-height: 1.7;
            color: #425466;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            max-width: 700px;
            margin: 0 auto;
        }

        .action-card {
            text-decoration: none;
            background: #ffffff;
            color: inherit;
            border-radius: 22px;
            padding: 28px;
            text-align: left;
            border: 1px solid #d8e2ea;
            box-shadow: 0 14px 34px rgba(16, 37, 66, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(16, 37, 66, 0.14);
            border-color: #9db8cf;
        }

        .action-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            margin-bottom: 20px;
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
        }

        .student .action-label {
            background: linear-gradient(135deg, #1d4ed8, #0f766e);
        }

        .teacher .action-label {
            background: linear-gradient(135deg, #9a3412, #be123c);
        }

        .action-card h2 {
            font-size: 1.6rem;
            margin-bottom: 10px;
            color: #102542;
        }

        .action-card p {
            color: #526273;
            line-height: 1.65;
            margin-bottom: 22px;
        }

        .button-text {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 999px;
            background: #102542;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .footer-note {
            padding: 0 32px 32px;
            text-align: center;
            color: #5b6b7b;
            font-size: 0.95rem;
        }

        @media (max-width: 700px) {
            .hero {
                padding: 40px 22px;
            }

            .actions {
                grid-template-columns: 1fr;
            }

            .footer-note {
                padding: 0 22px 24px;
            }
        }
    </style>
</head>
<body>
    <main class="panel">
        <section class="hero">
            <span class="eyebrow">Welcome</span>
            <h1>Student Result Management System</h1>
            <p class="subtitle">
                Choose your portal to continue. Students can view their results, and teachers can manage records from their own area.
            </p>

            <div class="actions">
                <a href="{{ route('student.login') }}" class="action-card student">
                    <span class="action-label">S</span>
                    <h2>Student</h2>
                    <p>Open the student portal to sign in and check published results.</p>
                    <span class="button-text">Go to Student Portal</span>
                </a>

                <a href="{{ route('teacher.login') }}" class="action-card teacher">
                    <span class="action-label">T</span>
                    <h2>Teacher</h2>
                    <p>Open the teacher portal to sign in and manage subjects and results.</p>
                    <span class="button-text">Go to Teacher Portal</span>
                </a>
            </div>
        </section>

        <div class="footer-note">
            Start from the correct portal to access your dashboard quickly.
        </div>
    </main>
</body>
</html>
