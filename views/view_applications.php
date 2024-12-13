<?php
session_start();
require_once '../core/models.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    <?php if (empty($applications)): ?>
        <p>No applications have been received for this job post yet.</p>
    <?php else: ?>
        <?php foreach ($applications as $application): ?>
            <div>
                <h3>Application #<?= $application['id'] ?> - <?= htmlspecialchars($application['applicant_name']) ?></h3>
                <p><strong>Resume:</strong> <a href="../<?= $application['resume_path'] ?>" download>Download Resume</a></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($application['status']) ?></p>

                <form action="../core/handleForms.php" method="POST">
                    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
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
