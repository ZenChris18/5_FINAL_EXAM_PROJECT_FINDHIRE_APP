<?php
session_start();
require_once '../core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../index.php");
    exit();
}

$jobId = $_GET['job_id'];
$applications = getApplicationsForJob($jobId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications for Job #<?= $jobId ?></title>
</head>
<body>
    <h1>Applications for Job #<?= $jobId ?></h1>
    <ul>
        <?php foreach ($applications as $application): ?>
            <li>
                <p>Resume: <a href="../<?= $application['resume_path'] ?>" download>Download</a></p>
                <p>Status: <?= $application['status'] ?></p>
                <form action="../core/handleForms.php" method="POST">
                    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                    <textarea name="message" placeholder="Message"></textarea>
                    <button type="submit" name="updateApplicationStatus" value="accepted">Accept</button>
                    <button type="submit" name="updateApplicationStatus" value="rejected">Reject</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <h2>Contact HR</h2>
    <form action="../core/handleForms.php" method="POST">
        <input type="hidden" name="sender_id" value="<?= $_SESSION['user_id'] ?>">
        <label>HR ID:</label><br>
        <input type="text" name="receiver_id" required><br>
        <label>Message:</label><br>
        <textarea name="message" required></textarea><br><br>
        <button type="submit" name="sendMessage">Send</button>
    </form>
    <a href="hr_dashboard.php">Back to Dashboard</a>
</body>
</html>
