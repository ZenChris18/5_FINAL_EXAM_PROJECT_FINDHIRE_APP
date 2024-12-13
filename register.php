<?php
session_start();
require_once 'core/models.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Register - FindHire</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>Register Now!</h1>

    <?php  
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        if ($_SESSION['status'] == "200") {
            echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
        } else {
            echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";    
        }
    }
    unset($_SESSION['message']);
    unset($_SESSION['status']);
    ?>

    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" required>
        </p>
        <p>
            <br>
            <label for="password">Password</label>
            </br>
            <input type="password" name="password" required>
        </p>
        <p>
            <label for="role">Role</label>
            <select name="role" required>
                <option value="applicant">Applicant</option>
                <option value="hr">HR</option>
            </select>
        </p>
        <p>
            <input type="submit" name="registerUserBtn" value="Register">
        </p>
    </form>
    <h2>Already have an account? Login <a href="login.php">here</a></h2>
</body>
</html>
