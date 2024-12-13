<?php
session_start();
require_once '../core/models.php'; // Ensure this is included

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: ../index.php");
    exit();
}

// Get job posts for applicants
$jobPosts = getJobPostsForApplicants();

// Debugging: Check if the jobPosts array is empty
if (empty($jobPosts)) {
    echo "No job posts found.";
}

// Get HR representatives to message
$hrUsers = getHrUsers();  // A new function to get HR users (implement this in your models)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
</head>
<body>
    <h1>Applicant Dashboard</h1>
    <h2>Available Jobs</h2>
    <ul>
        <?php foreach ($jobPosts as $post): ?>
            <li>
                <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                <?= htmlspecialchars($post['description']) ?>
                <form action="../core/handleForms.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="applicant_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="file" name="resume" required>
                    <button type="submit" name="applyJob">Apply</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Send a Message to HR</h2>
    <form action="../core/handleForms.php" method="POST">
        <label>Choose HR Representative:</label>
        <select name="receiver_id" required>
            <?php foreach ($hrUsers as $hr): ?>
                <option value="<?= $hr['id'] ?>"><?= htmlspecialchars($hr['username']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Message:</label><br>
        <textarea name="message" required></textarea><br><br>
        <button type="submit" name="sendMessage">Send</button>
    </form>

    <a href="../logout.php">Logout</a>
</body>
</html>
