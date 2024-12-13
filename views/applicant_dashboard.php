<?php
session_start();
require_once '../core/models.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: ../index.php");
    exit();
}

$jobPosts = getJobPostsForApplicants();

if (empty($jobPosts)) {
    echo "No job posts found.";
}

$hrUsers = getHrUsers(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Applicant Dashboard</title>
</head>
<body>
    <h1>Applicant Dashboard</h1>
    <h2>Available Jobs</h2>
    <ul>
        <?php foreach ($jobPosts as $post): ?>
            <li>
                <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                <strong><?= htmlspecialchars($post['description']) ?></strong>
                <form action="../core/handleForms.php" method="POST">
                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="applicant_id" value="<?= $_SESSION['user_id'] ?>">

                    <!-- Input for years of experience -->
                    <label for="experience">Years of Experience:</label>
                    <input type="number" name="experience" min="0" required><br><br>

                    <!-- Input for college -->
                    <label for="college">College Degree:</label>
                    <input type="text" name="college" required><br><br>

                    <button type="submit" name="applyJob">Apply</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h1>Send a Message to HR</h1>
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
        <a href="view_my_applications.php">View My Applications</a>
        <a href="../logout.php">Logout</a>
    </form>

</body>
</html>
