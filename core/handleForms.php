<?php
session_start();
require_once 'models.php';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (registerUser($username, $password, $role)) {
        $_SESSION['message'] = "Registration successful!";
        $_SESSION['status'] = "200";
        header("Location: ../login.php");
        exit(); // Make sure the script doesn't continue
    } else {
        $_SESSION['message'] = "Error: Could not register user.";
        $_SESSION['status'] = "500";
        header("Location: ../register.php");
        exit(); // Make sure the script doesn't continue
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = loginUser($username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on user role
        if ($user['role'] === 'hr') {
            $_SESSION['message'] = "Login successful!";
            $_SESSION['status'] = "200";
            header("Location: ../views/hr_dashboard.php");
        } else {
            $_SESSION['message'] = "Login successful!";
            $_SESSION['status'] = "200";
            header("Location: ../views/applicant_dashboard.php");
        }
        exit(); // Make sure the script doesn't continue
    } else {
        $_SESSION['message'] = "Invalid username or password.";
        $_SESSION['status'] = "500";
        header("Location: ../login.php");
        exit(); // Make sure the script doesn't continue
    }
}

// Handle job post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createJobPost'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $createdBy = $_POST['created_by'];

    if (createJobPost($title, $description, $createdBy)) {
        header("Location: ../views/hr_dashboard.php?msg=Job post created successfully");
        exit();
    } else {
        header("Location: ../views/hr_dashboard.php?error=Failed to create job post");
        exit();
    }
}

// Handle job application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['applyJob'])) {
    $jobId = $_POST['job_id'];
    $applicantId = $_POST['applicant_id'];
    $resumePath = "uploads/" . basename($_FILES['resume']['name']);
    
    if (move_uploaded_file($_FILES['resume']['tmp_name'], "../" . $resumePath)) {
        if (applyForJob($jobId, $applicantId, $resumePath)) {
            header("Location: ../views/hr_dashboard.php?msg=Application submitted successfully");
            exit();
        } else {
            header("Location: ../views/hr_dashboard.php?error=Failed to apply for job");
            exit();
        }
    } else {
        header("Location: ../views/hr_dashboard.php?error=Failed to upload resume");
        exit();
    }
}

// Handle application status update (accept/reject)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateApplicationStatus'])) {
    $appId = $_POST['application_id'];
    $status = $_POST['status'];
    $message = $_POST['message'];

    if (updateApplicationStatus($appId, $status, $message)) {
        header("Location: ../manage_applications.php?msg=Application updated successfully");
        exit();
    } else {
        header("Location: ../manage_applications.php?error=Failed to update application");
        exit();
    }
}


// Handle fetching job posts for HR
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['getHRPosts'])) {
    $hrId = $_POST['hr_id'];
    $jobPosts = getJobPostsByHR($hrId);
    echo json_encode($jobPosts);
}

if (isset($_POST['sendMessage'])) {
    $senderId = $_SESSION['user_id'];  // The applicant's ID
    $receiverId = $_POST['receiver_id'];  // HR's ID
    $message = $_POST['message'];  // Message content

    $result = sendMessage($senderId, $receiverId, $message);

    if ($result) {
        // Successfully sent the message
        header("Location: ../views/applicant_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Failed to send the message.";
    }
}

if (isset($_POST['sendMessage'])) {
    $senderId = $_SESSION['user_id'];  // HR's ID
    $receiverId = $_POST['receiver_id'];  // Applicant's ID
    $message = $_POST['message'];  // Message content

    $result = sendMessage($senderId, $receiverId, $message);

    if ($result) {
        // Successfully sent the message
        header("Location: ../views/hr_dashboard.php"); // Redirect back to the HR dashboard
        exit();
    } else {
        echo "Failed to send the message.";
    }
}

?>
