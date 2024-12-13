<?php
session_start();
require_once '../core/models.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['job_id'])) {
    echo "Job ID is missing.";
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
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Applications for Job #<?= $jobId ?></title>
</head>
<body>
    <h1>Applications for Job #<?= $jobId ?></h1>

    <?php if (empty($applications)): ?>
        <p>No applications have been received for this job post yet.</p>
    <?php else: ?>
        <?php foreach ($applications as $application): ?>
            <div>
                <h2>Application #<?= $application['id'] ?> - <?= htmlspecialchars($application['applicant_name'] ?? 'N/A') ?></h2>
                <strong><p>Status: <?= htmlspecialchars($application['status'] ?? 'N/A') ?></p></strong>


                <form action="../core/handleForms.php" method="POST">
                    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                    <input type="hidden" name="job_id" value="<?= $jobId ?>">
                    <textarea name="message" placeholder="Leave a message for the applicant (optional)"></textarea><br><br>
                    <button type="submit" name="updateApplicationStatus" value="accepted">Accept</button>
                    <button type="submit" name="updateApplicationStatus" value="rejected">Reject</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="hr_dashboard.php">Back to Dashboard</a>
</body>
</html>
