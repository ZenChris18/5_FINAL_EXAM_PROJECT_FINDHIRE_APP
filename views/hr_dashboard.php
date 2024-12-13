<?php
session_start();
require_once '../core/models.php';

// error checking
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../index.php");
    exit();
}

$jobPosts = getJobPostsByHR($_SESSION['user_id']);

// Handle job deletion
if (isset($_GET['delete_job'])) {
    $jobId = $_GET['delete_job'];
    deleteJobPost($jobId);
    header("Location: hr_dashboard.php"); // Redirect after deletion
    exit();
}

$messages = getMessages($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
</head>
<body>
    <h1>HR Dashboard</h1>

    <!-- Job Posts Section -->
    <h2>Job Posts</h2>
    <ul>
        <?php foreach ($jobPosts as $post): ?>
            <li>
                <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                <?= htmlspecialchars($post['description']) ?><br>
                <a href="view_applications.php?job_id=<?= $post['id'] ?>">View Applications</a> | 
                <a href="hr_dashboard.php?delete_job=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this job post?')">Delete Job</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Create Job Post Section -->
    <h2>Create Job Post</h2>
    <form action="../core/handleForms.php" method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br>
        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>
        <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?>">
        <button type="submit" name="createJobPost">Post Job</button>
    </form>

    <!-- Messages Section -->
    <h2>Messages</h2>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div>
                <p><strong>From:</strong> <?= htmlspecialchars($msg['sender_id']) ?></p>
                <p><strong>Message:</strong> <?= htmlspecialchars($msg['message']) ?></p>
                <form action="../core/handleForms.php" method="POST">
                    <input type="hidden" name="sender_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" name="receiver_id" value="<?= $msg['sender_id'] ?>">
                    <textarea name="message" placeholder="Reply..."></textarea><br>
                    <button type="submit" name="sendMessage">Send</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No new messages.</p>
    <?php endif; ?>

    <a href="../logout.php">Logout</a>
</body>
</html>
