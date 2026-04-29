<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #ffffff;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .navbar .brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: #222;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            gap: 12px;
        }
        .nav-links a,
        .nav-links button {
            border: none;
            background: transparent;
            color: #444;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .nav-links a:hover,
        .nav-links button:hover {
            background: #f0f4ff;
            color: #0f3d91;
        }
        .nav-links .active {
            background: #0f3d91;
            color: #fff;
        }
        .hero {
            max-width: 1080px;
            margin: 28px auto;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(15, 61, 145, 0.08);
            overflow: hidden;
        }
        .card-body {
            padding: 28px;
        }
        .card-body h1 {
            margin: 0 0 12px;
            font-size: 2rem;
        }
        .card-body p {
            margin: 0;
            color: #555;
            line-height: 1.65;
        }
        .hero-placeholder {
            min-height: 260px;
            margin-top: 22px;
            border: 2px dashed #c7d0ea;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
        }
        .hero-placeholder h2 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 500;
        }
        .content-section {
            margin: 16px auto;
            max-width: 1080px;
            display: none;
            background: #fff;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(60, 72, 88, 0.08);
        }
        .content-section.active {
            display: block;
        }
        .content-section h3 {
            margin-top: 0;
            font-size: 1.25rem;
        }
        .logout-message {
            display: none;
            margin-top: 14px;
            padding: 14px 18px;
            border-radius: 12px;
            background: #ffe8e8;
            color: #a31919;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="#" class="brand">Teacher Dashboard</a>
        <nav class="nav-links">
            <a href="#add-result" class="nav-item active" data-section="add-result">Add Student Result</a>
            <a href="#student-list" class="nav-item" data-section="student-list">Student List</a>
            <a href="#profile" class="nav-item" data-section="profile">Profile</a>
            <button id="logoutBtn">Logout</button>
        </nav>
    </header>

    <main class="hero">
        <div class="card">
            <div class="card-body">
                <h1>Welcome, Teacher</h1>
                <p>This is your dashboard. Use the navbar to access student result entry, view the student list, check your profile, or log out.</p>
            </div>
        </div>

        <div class="hero-placeholder">
            <h2>Hero section placeholder - update later</h2>
        </div>

        <section id="add-result" class="content-section active">
            <h3>Add Student Result</h3>
            <p>This section is reserved for the Add Student Result form. You can build the form here later.</p>
        </section>

        <section id="student-list" class="content-section">
            <h3>Student List</h3>
            <p>Student list content will appear here once the feature is added.</p>
        </section>

        <section id="profile" class="content-section">
            <h3>Profile</h3>
            <p>Teacher profile details and update controls can be added to this area.</p>
        </section>

        <div id="logoutMessage" class="logout-message">
            You are now logged out. Reload the page to return.
        </div>
    </main>

    <script>
        const navItems = document.querySelectorAll('.nav-item');
        const sections = document.querySelectorAll('.content-section');
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutMessage = document.getElementById('logoutMessage');

        navItems.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const target = item.dataset.section;

                navItems.forEach(link => link.classList.remove('active'));
                item.classList.add('active');

                sections.forEach(section => {
                    section.classList.toggle('active', section.id === target);
                });

                logoutMessage.style.display = 'none';
            });
        });

        logoutBtn.addEventListener('click', () => {
            sections.forEach(section => section.classList.remove('active'));
            navItems.forEach(link => link.classList.remove('active'));
            logoutMessage.style.display = 'block';
        });
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <div class="btn-container">
        <button class="btn" id="add-result-btn">Add Result</button>
        <button class="btn" id="student-list-btn">Student List</button>
        <button class="btn" id="logout-btn">Logout</button>
    </div>
    <style>
        .btn {
            background-color: #6495ED;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

    
</body>
</html>
