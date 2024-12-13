<?php
session_start();
require_once '../core/models.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: ../index.php");
    exit();
}

$applicantId = $_SESSION['user_id'];
$applications = getApplicationsForApplicant($applicantId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Applications</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>My Job Applications</h1>
        
        <?php if (empty($applications)): ?>
            <p>You haven't applied to any jobs yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Years of Experience</th>
                        <th>College</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?= htmlspecialchars($application['job_title']) ?></td>
                                <td><?= htmlspecialchars($application['status']) ?></td>
                                <td><?= htmlspecialchars($application['years_of_experience']) ?></td>
                                <td><?= htmlspecialchars($application['college']) ?></td>
                                <td>
                                    <?= htmlspecialchars($application['message']) ?: 'No message provided.' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        <?php endif; ?>
        
        <a href="applicant_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
