<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'hr') {
        header("Location: views/hr_dashboard.php");
    } else {
        header("Location: views/applicant_dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>FindHire</title>
</head>
<body>
    <h1>Welcome to FindHire</h1>
    <h2>JOB APPLICATION SYSTEM 
        <br>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        </br>
    </h2>

</body>
</html>
