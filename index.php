<?php
ob_start(); // Start output buffering to prevent header issues
session_start();
include 'db.php';
$error = "";

// Insert Admin Credentials if Not Exist
try {
    $email = "luckyrounak2895@gmail.com";
    $password = "@Vishu28";
    $name = "Lucky Rounak";

    // Check if admin exists
    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare query for checking admin.");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Admin doesn't exist, insert new admin
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare query for inserting admin.");
        }
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert admin account.");
        }
        error_log("Admin account created for $email");
    }
    $stmt->close();
} catch (Exception $e) {
    $error = "Failed to initialize admin account: " . $e->getMessage();
    error_log($error);
}

// Handle Admin Login
if (isset($_POST["admin_login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT id, name, password FROM admin WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare query.");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $stored_password);
            $stmt->fetch();

            if (password_verify($password, $stored_password)) {
                $_SESSION["admin_id"] = $id;
                $_SESSION["admin_name"] = $name;
                error_log("Login successful for $email");
                header("Location: admin_dashboard.php");
                exit();
            } else {
                error_log("Invalid password for $email");
                $error = "Invalid admin password!";
            }
        } else {
            error_log("No admin account found for $email");
            $error = "No admin account found with this email!";
        }
    } catch (Exception $e) {
        $error = "An error occurred: " . $e->getMessage();
        error_log($error);
    } finally {
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AKTU Rankers - Your ultimate platform for academic resources and admin management. Log in to access student and admin dashboards.">
    <meta name="keywords" content="AKTU Rankers, academic platform, student dashboard, admin login, educational resources, B.Tech notes, M.Tech notes, BCA notes">
    <meta name="author" content="AKTU Rankers Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="AKTU Rankers - Empower Your Academic Journey">
    <meta property="og:description" content="AKTU Rankers is your go-to platform for high-quality notes, PYQs, short notes, and mini projects for AKTU students.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://yourdomain.com/">
    <meta property="og:image" content="http://yourdomain.com/assets/og-image.jpg">
    <title>AKTU Rankers - Empower Your Academic Journey</title>
    <link rel="canonical" href="http://yourdomain.com/" />
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
            position: relative;
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

        .hero-section {
            position: relative;
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(180deg, rgba(20, 184, 166, 0.1), rgba(96, 165, 250, 0.1));
            overflow: hidden;
            animation: fadeIn 1s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%2314b8a6" fill-opacity="0.2" d="M0,128L48,144C96,160,192,192,288,186.7C384,181,480,139,576,122.7C672,107,768,117,864,138.7C960,160,1056,192,1152,186.7C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            z-index: -1;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 48px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-content p {
            font-size: 20px;
            color: #475569;
            margin-bottom: 30px;
        }

        .cta-btn {
            padding: 16px 32px;
            font-size: 18px;
            font-weight: 600;
            background: linear-gradient(135deg, #14b8a6, #10b981);
            border: none;
            color: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .cta-btn:hover::before {
            left: 100%;
        }

        .cta-btn:hover {
            background: linear-gradient(135deg, #0d9488, #059669);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(20, 184, 166, 0.4);
        }

        .features-section {
            max-width: 1300px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: center;
        }

        .features-section h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 36px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: slideUp 0.8s ease-out;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top left, rgba(0, 0, 0, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at top right, rgba(0, 0, 0, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at bottom left, rgba(0, 0, 0, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        .feature-card i {
            font-size: 40px;
            color: #14b8a6;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .feature-card p {
            font-size: 16px;
            color: #475569;
        }

        .admin-login {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            width: 360px;
            text-align: center;
            animation: slideUp 0.7s ease-out;
            z-index: 2000;
        }

        .admin-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #14b8a6, #3b82f6);
        }

        .admin-login h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 26px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .admin-login input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            color: #1e293b;
            font-size: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .admin-login input:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 10px rgba(20, 184, 166, 0.4);
        }

        .admin-login button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #14b8a6, #10b981);
            border: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .admin-login button:hover {
            background: linear-gradient(135deg, #0d9488, #059669);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(20, 184, 166, 0.4);
        }

        .close-btn {
            background: transparent;
            border: 2px solid #f87171;
            color: #f87171;
            margin-top: 12px;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #f87171;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(248, 113, 113, 0.4);
        }

        .error {
            color: #f87171;
            text-align: center;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 1999;
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

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: linear-gradient(45deg, #14b8a6, #60a5fa);
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(20, 184, 166, 0.3);
            animation: parallaxFloat 20s infinite linear;
        }

        @keyframes parallaxFloat {
            0% { transform: translateY(100vh) translateX(0) scale(0.2); opacity: 0.5; }
            100% { transform: translateY(-100vh) translateX(20px) scale(0.4); opacity: 0.2; }
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

            .hero-content h1 {
                font-size: 36px;
            }

            .hero-content p {
                font-size: 18px;
            }

            .admin-login {
                width: 90%;
                padding: 30px;
            }

            .features-section h2 {
                font-size: 30px;
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
            .header-title {
                font-size: 24px;
            }

            .hero-content h1 {
                font-size: 28px;
            }

            .hero-content p {
                font-size: 16px;
            }

            .cta-btn {
                padding: 12px 24px;
                font-size: 16px;
            }

            .admin-login {
                padding: 20px;
            }

            .social-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="particles"></div>
    <header>
        <div class="header-title"><span class="aktu">Aktu</span><span class="rankers">Rankers</span></div>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <nav id="navbar">
            <a href="#" onclick="showAdminLogin()">Admin Login</a>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1>Empower Your AKTU Journey</h1>
                <p>Access high-quality notes, PYQs, short notes, and mini projects to excel in your academic pursuits.</p>
                <button class="cta-btn" onclick="location.href='student_dashboard.php'">Get Started</button>
            </div>
        </section>

        <section class="features-section">
            <h2>Why Choose AKTU Rankers?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-book"></i>
                    <h3>Comprehensive Notes</h3>
                    <p>Access detailed, semester-wise notes for B.Tech, M.Tech, and BCA courses.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>Previous Year Questions</h3>
                    <p>Practice with PYQs to boost your exam preparation and confidence.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Short Notes & Topics</h3>
                    <p>Quick revision with concise short notes and important topics.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-project-diagram"></i>
                    <h3>Mini Projects</h3>
                    <p>Explore innovative mini projects to enhance your practical skills.</p>
                </div>
            </div>
        </section>
    </main>

    <div class="modal-overlay"></div>
    <section class="admin-login">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required aria-label="Admin Email">
            <input type="password" name="password" placeholder="Password" required aria-label="Admin Password">
            <button type="submit" name="admin_login">Login</button>
            <button type="button" class="close-btn" onclick="closeAdminLogin()">Close</button>
        </form>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About AKTU Rankers</h3>
                <p>AKTU Rankers is your go-to platform for high-quality academic resources, empowering AKTU students with notes, question papers, and project ideas to succeed.</p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            <div class="footer-section social">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="https://youtube.com/@akturankers" class="social-icon youtube" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://x.com/akturankers" class="social-icon x" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    <a href="https://instagram.com/akturankers" class="social-icon instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/akturankers" class="social-icon facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
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

        function showAdminLogin() {
            document.querySelector('.admin-login').style.display = 'block';
            document.querySelector('.modal-overlay').style.display = 'block';
        }

        function closeAdminLogin() {
            document.querySelector('.admin-login').style.display = 'none';
            document.querySelector('.modal-overlay').style.display = 'none';
        }

        // Close modal when clicking outside
        document.querySelector('.modal-overlay').addEventListener('click', closeAdminLogin);

        // Particle system
        window.onload = function() {
            const particlesContainer = document.querySelector('.particles');
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 5 + 3;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.animationDelay = `${Math.random() * 10}s`;
                particle.style.animationDuration = `${Math.random() * 15 + 10}s`;
                particle.style.background = Math.random() > 0.5 ? 
                    'linear-gradient(45deg, #14b8a6, #10b981)' : 
                    'linear-gradient(45deg, #60a5fa, #3b82f6)';
                particlesContainer.appendChild(particle);
            }
        };

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "AKTU Rankers",
        "url": "http://yourdomain.com/",
        "description": "AKTU Rankers provides high-quality academic resources for AKTU students, including notes, previous year question papers, and mini projects.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "IN"
        },
        "sameAs": [
            "https://youtube.com/@akturankers",
            "https://x.com/akturankers",
            "https://instagram.com/akturankers",
            "https://facebook.com/akturankers"
        ]
    }
    </script>
</body>
</html>