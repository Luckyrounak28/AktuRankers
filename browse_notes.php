<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include 'db.php'; // Include database connection

// Get course and semester from query parameters
$course = isset($_GET['course']) ? $_GET['course'] : '';
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';

// Fetch notes from the database
$stmt = $conn->prepare("SELECT id, subject, file_name FROM notes WHERE course = ? AND semester = ?");
$stmt->bind_param("ss", $course, $semester);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Notes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .notes-table th, .notes-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .notes-table th {
            background-color: #5a67d8;
            color: white;
        }
        .download-link {
            color: #5a67d8;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Notes for <?php echo $course; ?> - Semester <?php echo $semester; ?></h2>

    <table class="notes-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                        <td>
                            <a href="uploads/<?php echo htmlspecialchars($row["file_name"]); ?>" download class="download-link">
                                Download
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No notes available for the selected course and semester.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button onclick="window.history.back()">Go Back</button>
</body>
</html>

<?php
$stmt->close();
?>
