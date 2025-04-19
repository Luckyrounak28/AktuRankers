<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AKTU Notes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        header {
            background-color: #5a67d8;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav {
            display: flex;
            gap: 15px;
        }
        nav a, header form button {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            background-color: #4c51bf;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        nav a:hover, header form button:hover {
            background-color: #434190;
        }
        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }
        nav.collapsed {
            display: none;
        }
        nav.expanded {
            display: flex;
            flex-direction: column;
            background-color: #5a67d8;
            position: absolute;
            top: 60px;
            right: 0;
            width: 100%;
            padding: 10px 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        nav.expanded a {
            padding: 12px 15px;
            margin: 5px 0;
            text-align: center;
            width: 100%;
        }

        .container {
            max-width: 900px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 150px;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        a.dashboard-link {
            display: block;
            text-decoration: none;
            color: white;
            background-color: #5a67d8;
            padding: 15px 20px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        a.dashboard-link:hover {
            background-color: #4c51bf;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            nav {
                display: none;
            }
        }
    </style>
    <script>
        function toggleMenu() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('expanded');
        }
    </script>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <span class="menu-toggle" onclick="toggleMenu()">☰</span>
    <nav>
        <a href="admin_dashboard.php">Home</a>
        <a href="upload_content.php">Upload Content</a>
        <form method="post" action="admin_logout.php" style="display: inline;">
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="container">
    <img src="aktu_logo.png" alt="AKTU Notes Logo" class="logo">
    <h1>Welcome, Admin</h1>
    <div class="links">
        <a href="upload_content.php?content_type=notes" class="dashboard-link">Upload Notes</a>
        <a href="upload_content.php?content_type=pyqs" class="dashboard-link">Upload PYQs</a>
        <a href="upload_content.php?content_type=short_notes" class="dashboard-link">Upload Short Notes</a>
        <a href="upload_content.php?content_type=important_topics" class="dashboard-link">Upload Important Topics</a>
        <a href="upload_content.php?content_type=mini_projects" class="dashboard-link">Upload Mini Projects</a>
    </div>
</div>

</body>
</html>
