<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AKTU Notes Student Dashboard - Access high-quality notes, previous year question papers, short notes, and mini projects for B.Tech, M.Tech, and BCA courses.">
    <meta name="keywords" content="AKTU Notes, student dashboard, B.Tech notes, M.Tech notes, BCA notes, PYQs, short notes, mini projects">
    <meta name="author" content="AKTU Rankers Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="AKTU Notes - Student Dashboard">
    <meta property="og:description" content="Your one-stop platform for academic resources, including notes, PYQs, and projects for AKTU students.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://yourdomain.com/student_dashboard.php">
    <meta property="og:image" content="http://yourdomain.com/assets/og-image.jpg">
    <title>Student Dashboard - AKTU Notes</title>
    <link rel="canonical" href="http://yourdomain.com/student_dashboard.php" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e2e8f0, #bfdbfe);
            background-attachment: fixed;
            color: #1e293b;
            line-height: 1.6;
            overflow-x: hidden;
        }

        header {
            background: #1e293b;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .header-title {
            font-family: 'Poppins', sans-serif;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(96, 165, 250, 0.1));
            padding: 8px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header-title:hover {
            transform: scale(1.03);
        }

        .aktu {
            color: #ffffff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .rankers {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-toggle {
            display: none;
            font-size: 32px;
            cursor: pointer;
            color: #14b8a6;
            transition: transform 0.3s ease;
        }

        .menu-toggle:hover {
            transform: rotate(180deg);
        }

        nav {
            display: flex;
            gap: 30px;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            font-size: 17px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            position: relative;
            transition: all 0.3s ease;
        }

        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: #14b8a6;
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        nav a:hover {
            background: rgba(20, 184, 166, 0.2);
            color: #14b8a6;
        }

        nav a.logout {
            color: #f87171;
        }

        nav a.logout:hover {
            background: rgba(248, 113, 113, 0.2);
            color: #f87171;
        }

        nav a.logout::after {
            background: #f87171;
        }

        main {
            max-width: 1300px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 60px;
            animation: fadeIn 1.2s ease-out;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(59, 130, 246, 0.2));
            padding: 40px;
            border-radius: 16px;
            border: 2px solid #14b8a6;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 42px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .welcome-section p {
            font-size: 20px;
            color: #475569;
            max-width: 700px;
            margin: 0 auto;
        }

        .content-form {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            margin-bottom: 60px;
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .content-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #14b8a6, #3b82f6);
        }

        .content-form h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 15px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 10px;
            text-align: left;
        }

        select, button {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        select {
            background: #f8fafc;
            color: #1e293b;
            border: 1px solid #d1d5db;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%231e293b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        select:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 10px rgba(20, 184, 166, 0.4);
        }

        button {
            background: linear-gradient(135deg, #14b8a6, #10b981);
            color: #ffffff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            background: linear-gradient(135deg, #0d9488, #059669);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(20, 184, 166, 0.4);
        }

        .resources-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .resource-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .resource-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.1) 0%, transparent 70%);
            transition: transform 0.5s ease;
            transform: scale(0);
        }

        .resource-card:hover::before {
            transform: scale(1);
        }

        .resource-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
            border-color: #60a5fa;
            background: linear-gradient(135deg, #ffffff, #eff6ff);
        }

        .resource-card i {
            font-size: 40px;
            color: #14b8a6;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .resource-card:hover i {
            transform: scale(1.2);
        }

        .resource-card h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #14b8a6;
            margin-bottom: 15px;
        }

        .resource-card p {
            font-size: 16px;
            color: #475569;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
            padding: 0 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }

        .stat-card i {
            font-size: 32px;
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .stat-card h4 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 14px;
            color: #64748b;
        }

        footer {
            background: linear-gradient(135deg, #1e293b, #2d3748);
            padding: 60px 20px;
            color: #f1f5f9;
            border-top: 3px solid #14b8a6;
        }

        .footer-content {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
        }

        .footer-section {
            flex: 1;
            min-width: 220px;
        }

        .footer-section h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .footer-section p {
            font-size: 16px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .social-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 20px;
        }

        .social-icon:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .youtube { background: #ff0000; color: #ffffff; }
        .x { background: #000000; color: #ffffff; }
        .instagram { background: linear-gradient(45deg, #f58529, #dd2a7b, #8134af); color: #ffffff; }
        .facebook { background: #3b5998; color: #ffffff; }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #14b8a6;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #60a5fa;
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 30px;
            font-size: 14px;
            color: #d1d5db;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            header {
                padding: 15px 25px;
            }

            .header-title {
                font-size: 28px;
            }

            .menu-toggle {
                display: block;
            }

            nav {
                display: none;
                flex-direction: column;
                gap: 15px;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background: #1e293b;
                padding: 20px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }

            nav.active {
                display: flex;
            }

            .content-form {
                padding: 30px;
            }

            .welcome-section {
                padding: 30px;
            }

            .welcome-section h2 {
                font-size: 32px;
            }

            .welcome-section p {
                font-size: 18px;
            }

            .footer-content {
                flex-direction: column;
                align-items: center;
            }

            .footer-section {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .resources-section, .stats-section {
                grid-template-columns: 1fr;
            }

            .social-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .content-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title"><span class="aktu">Aktu</span><span class="rankers">Rankers</span></div>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <nav id="navbar">
        <a href="student_dashboard.php">Home</a>
            <a href="notes.php">Notes</a>
            <a href="pyqs.php">PYQs</a>
            <a href="short_notes.php">Short Notes</a>
            <a href="important_topics.php">Important Topics</a>
            <a href="mini_projects.php">Mini Projects</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <h2>Your Path to Academic Excellence</h2>
            <p>Unlock a world of curated study materials, previous year papers, concise notes, and hands-on projects designed for AKTU students to thrive.</p>
        </section>

        <section class="content-form">
            <h3>Discover Your Study Resources</h3>
            <form method="get" action="view_content.php">
                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" required aria-label="Select Course">
                        <option value="" disabled selected>Select Course</option>
                        <option value="B.Tech">B.Tech</option>
                        <option value="M.Tech">M.Tech</option>
                        <option value="BCA">BCA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <select id="branch" name="branch" required aria-label="Select Branch">
                        <option value="" disabled selected>Select Branch</option>
                        <option value="CSE">CSE</option>
                        <option value="ECE">ECE</option>
                        <option value="Mechanical">Mechanical</option>
                        <option value="Civil">Civil</option>
                        <option value="Electrical">Electrical</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select id="semester" name="semester" required aria-label="Select Semester">
                        <option value="" disabled selected>Select Semester</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                        <option value="3">3rd Semester</option>
                        <option value="4">4th Semester</option>
                        <option value="5">5th Semester</option>
                        <option value="6">6th Semester</option>
                        <option value="7">7th Semester</option>
                        <option value="8">8th Semester</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="content_type">Content Type</label>
                    <select id="content_type" name="content_type" required aria-label="Select Content Type">
                        <option value="" disabled selected>Select Content Type</option>
                        <option value="Notes">Notes</option>
                        <option value="PYQs">Previous Year Questions</option>
                        <option value="Short Notes">Short Notes</option>
                        <option value="Important Topics">Important Topics</option>
                    </select>
                </div>
                <button type="submit">Explore Now</button>
            </form>
        </section>

        <section class="resources-section">
            <div class="resource-card">
                <i class="fas fa-book"></i>
                <h4>Comprehensive Notes</h4>
                <p>In-depth, structured notes covering the entire AKTU syllabus for B.Tech, M.Tech, and BCA courses.</p>
            </div>
            <div class="resource-card">
                <i class="fas fa-file-alt"></i>
                <h4>Previous Year Papers</h4>
                <p>Practice with a vast collection of PYQs to master exam patterns and boost your confidence.</p>
            </div>
            <div class="resource-card">
                <i class="fas fa-sticky-note"></i>
                <h4>Short Notes</h4>
                <p>Quick, concise revision notes for efficient study sessions before exams or quizzes.</p>
            </div>
            <div class="resource-card">
                <i class="fas fa-laptop-code"></i>
                <h4>Mini Projects</h4>
                <p>Hands-on projects to sharpen your skills and enhance your portfolio with practical experience.</p>
            </div>
        </section>

        <section class="stats-section">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h4>50K+</h4>
                <p>Active Students</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-file-download"></i>
                <h4>100K+</h4>
                <p>Resources Downloaded</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-star"></i>
                <h4>4.8/5</h4>
                <p>Student Ratings</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-book-open"></i>
                <h4>500+</h4>
                <p>Study Materials</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About AKTU Rankers</h3>
                <p>AKTU Rankers is dedicated to empowering AKTU students with premium academic resources, including notes, question papers, and project ideas to excel in their studies.</p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="notes.php">Notes</a></li>
                    <li><a href="pyqs.php">PYQs</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            <div class="footer-section social">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="http://youtube.com/@akturankers" class="social-icon youtube" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="http://x.com/akturankers" class="social-icon x" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    <a href="http://instagram.com/akturankers" class="social-icon instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="http://facebook.com/akturankers" class="social-icon facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            © 2025 AKTU Rankers | All Rights Reserved
        </div>
    </footer>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            navbar.classList.toggle("active");
        }

        document.addEventListener("DOMContentLoaded", function () {
            function loadSelectedValues() {
                let selects = document.querySelectorAll("select");
                selects.forEach(select => {
                    let savedValue = localStorage.getItem(select.name);
                    if (savedValue) {
                        select.value = savedValue;
                    }
                });
            }

            function saveSelectedValue(event) {
                localStorage.setItem(event.target.name, event.target.value);
            }

            document.querySelectorAll("select").forEach(select => {
                select.addEventListener("change", saveSelectedValue);
            });

            loadSelectedValues();

            // Smooth scroll for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "EducationalOrganization",
        "name": "AKTU Rankers",
        "url": "http://yourdomain.com/",
        "description": "AKTU Rankers provides high-quality academic resources for AKTU students, including notes, previous year question papers, and mini projects.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "IN"
        },
        "sameAs": [
            "http://youtube.com/@akturankers",
            "http://x.com/akturankers",
            "http://instagram.com/akturankers",
            "http://facebook.com/akturankers"
        ]
    }
    </script>
</body>
</html>