<?php
include 'db.php';

// Retrieve form data with fallback
$course = $_GET['course'] ?? '';
$branch = $_GET['branch'] ?? '';
$semester = $_GET['semester'] ?? '';
$content_type = $_GET['content_type'] ?? '';

// Table mapping
$table_map = [
    'Notes' => 'notes',
    'Short Notes' => 'short_notes',
    'Important Topics' => 'important_topics',
    'PYQs' => 'pyqs',
    'Mini Projects' => 'mini_projects'
];

$table_name = $table_map[$content_type] ?? '';
$upload_directory = "Uploads/" . strtolower(str_replace(' ', '_', $content_type));

$result = false;
$error_message = '';

if ($table_name && $course && $branch && $semester) {
    try {
        $query = "SELECT subject, file_name FROM $table_name WHERE course = ? AND branch = ? AND semester = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare query.");
        }
        $stmt->bind_param("ssi", $course, $branch, $semester);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } catch (Exception $e) {
        $error_message = "An error occurred while fetching content. Please try again later.";
    }
} elseif (!$table_name) {
    $error_message = "Invalid content type selected.";
} else {
    $error_message = "Please provide all required parameters.";
}

// Store content in an array for iteration
$contents = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contents[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View academic resources on AKTU Rankers, including notes, previous year question papers, short notes, and mini projects for B.Tech, M.Tech, and BCA students.">
    <meta name="keywords" content="AKTU Rankers, view content, B.Tech notes, M.Tech notes, BCA notes, PYQs, short notes, mini projects">
    <meta name="author" content="AKTU Rankers Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="View Content - AKTU Rankers">
    <meta property="og:description" content="Access curated academic resources like notes, PYQs, and projects tailored for AKTU students.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://yourdomain.com/view_content.php">
    <meta property="og:image" content="http://yourdomain.com/assets/og-image.jpg">
    <title>View Content - AKTU Rankers</title>
    <link rel="canonical" href="http://yourdomain.com/view_content.php" />
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

        .content-section {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.8s ease-out;
            position: relative;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #14b8a6, #3b82f6);
        }

        h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
            margin-bottom: 30px;
        }

        .content-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .content-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            background: #ffffff;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .content-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .content-item::before {
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

        .content-subject {
            font-size: 18px;
            font-weight: 500;
            color: #1e293b;
        }

        .content-actions {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-view {
            background: linear-gradient(135deg, #14b8a6, #10b981);
            color: #ffffff;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #0d9488, #059669);
            transform: translateY(-2px);
        }

        .btn-download {
            background: #ffffff;
            color: #14b8a6;
            border: 2px solid #14b8a6;
        }

        .btn-download:hover {
            background: linear-gradient(135deg, #14b8a6, #10b981);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .error-message, .no-content {
            text-align: center;
            font-size: 18px;
            color: #f87171;
            margin: 30px 0;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: #ffffff;
            padding: 32px;
            width: 90%;
            max-width: 1000px;
            height: 80vh;
            border-radius: 16px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.5s ease-out;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #f87171;
            color: #ffffff;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .modal-close:hover {
            background: #f15959;
        }

        .modal iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
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

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
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

            .content-section {
                padding: 30px;
            }

            h2 {
                font-size: 28px;
            }

            .content-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 12px;
            }

            .content-actions {
                width: 100%;
                justify-content: space-between;
            }

            .modal-content {
                width: 95%;
                height: 85vh;
                padding: 24px;
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

            .content-section {
                padding: 20px;
            }

            .content-subject {
                font-size: 16px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 14px;
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
        <section class="content-section">
            <h2><?php echo htmlspecialchars($content_type ? $content_type : 'Resources'); ?></h2>
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif (!empty($contents)): ?>
                <div class="content-list">
                    <?php foreach ($contents as $content): ?>
                        <div class="content-item">
                            <span class="content-subject"><?php echo htmlspecialchars($content['subject']); ?></span>
                            <div class="content-actions">
                                <button class="btn btn-view" onclick="openViewer('<?php echo htmlspecialchars($upload_directory . '/' . $content['file_name']); ?>')">View</button>
                                <a href="<?php echo htmlspecialchars($upload_directory . '/' . $content['file_name']); ?>" class="btn btn-download" download>Download</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-content">No content available for the selected options.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About AKTU Rankers</h3>
                <p>AKTU Rankers is your go-to platform for high-quality academic resources, empowering AKTU students with notes, question papers, and project ideas to succeed.</p>
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

    <div id="viewerModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeViewer()">Close</button>
            <iframe id="viewerFrame"></iframe>
        </div>
    </div>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            navbar.classList.toggle("active");
        }

        function openViewer(file) {
            const viewerFrame = document.getElementById("viewerFrame");
            viewerFrame.src = file;
            document.getElementById("viewerModal").style.display = "flex";
        }

        function closeViewer() {
            const modal = document.getElementById("viewerModal");
            modal.style.display = "none";
            document.getElementById("viewerFrame").src = "";
        }

        // Close modal when clicking outside
        document.getElementById("viewerModal").addEventListener("click", function(e) {
            if (e.target === this) {
                closeViewer();
            }
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