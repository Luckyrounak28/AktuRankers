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
    <title>Upload Content</title>
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
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .back-button {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            background-color: #4c51bf;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #434190;
        }
        form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #5a67d8;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #4c51bf;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="aktu_logo.png" alt="AKTU Notes Logo">
            AKTU Notes
        </div>
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </header>

    <h2 style="text-align: center; margin-top: 30px;">Upload Content</h2>
    <form method="post" enctype="multipart/form-data" action="upload_content_handler.php">
        <label for="course">Course:</label>
        <select name="course" id="course" required>
            <option value="B.Tech">B.Tech</option>
            <option value="M.Tech">M.Tech</option>
            <option value="BCA">BCA</option>
        </select>

        <label for="branch">Branch:</label>
        <select name="branch" id="branch" required>
            <option value="CSE">CSE</option>
            <option value="ECE">ECE</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Civil">Civil</option>
            <option value="Electrical">Electrical</option>
        </select>

        <label for="semester">Semester:</label>
        <select name="semester" id="semester" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>

        <label for="content_type">Content Type:</label>
        <select name="content_type" id="content_type" required>
            <option value="notes">Notes</option>
            <option value="pyqs">PYQs</option>
            <option value="short_notes">Short Notes</option>
            <option value="important_topics">Important Topics</option>
            <option value="mini_projects">Mini Projects</option>
        </select>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" placeholder="Enter subject" required>

        <label for="file">Upload File:</label>
        <input type="file" name="file" id="file" required>

        <button type="submit">Upload</button>
    </form>
</body>
</html>
