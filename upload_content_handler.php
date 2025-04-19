<?php
session_start();
include 'db.php';

// Ensure the admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}

// Variable to hold the success message
$successMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $course = $_POST["course"];
    $branch = $_POST["branch"];
    $semester = $_POST["semester"];
    $content_type = $_POST["content_type"];
    $subject = $_POST["subject"];
    $file = $_FILES["file"];

    // Check if the file is valid
    if ($file["error"] === 0) {
        // Define upload directory based on content type
        $uploadDir = "uploads/" . $content_type . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique file name
        $fileName = uniqid() . "_" . basename($file["name"]);
        $uploadPath = $uploadDir . $fileName;

        // Move uploaded file to the directory
        if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
            // Insert data into the respective table
            $stmt = $conn->prepare("INSERT INTO " . $content_type . " (course, branch, semester, subject, file_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $course, $branch, $semester, $subject, $fileName);

            if ($stmt->execute()) {
                $successMessage = ucfirst($content_type) . " uploaded successfully!";
            } else {
                $successMessage = "Error uploading " . $content_type . "!";
            }

            $stmt->close();
        } else {
            $successMessage = "Error moving uploaded file!";
        }
    } else {
        $successMessage = "Error uploading file!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Content - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .back-button {
            padding: 8px 16px;
            background-color: #5a67d8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #4c51bf;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #5a67d8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #4c51bf;
        }
        .success-message {
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Upload Content</h2>
            <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        </div>

        <!-- Form for file upload -->
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" id="course" name="course" required>
            </div>
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" required>
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number" id="semester" name="semester" required min="1" max="8">
            </div>
            <div class="form-group">
                <label for="content_type">Content Type</label>
                <select id="content_type" name="content_type" required>
                    <option value="">Select Content Type</option>
                    <option value="notes">Notes</option>
                    <option value="short_notes">Short Notes</option>
                    <option value="important_topics">Important Topics</option>
                    <option value="pyqs">PYQs</option>
                    <option value="mini_projects">Mini Projects</option>
                </select>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" id="file" name="file" required>
            </div>
            <button type="submit">Upload</button>
        </form>

        <!-- Display success message if available -->
        <?php if ($successMessage): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
