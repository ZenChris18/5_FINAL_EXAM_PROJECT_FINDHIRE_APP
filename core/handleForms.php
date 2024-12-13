<?php
session_start();
require_once 'models.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (registerUser($username, $password, $role)) {
        $_SESSION['message'] = "Registration successful!";
        $_SESSION['status'] = "200";
        header("Location: ../login.php");
        exit(); 
    } else {
        $_SESSION['message'] = "Error: Could not register user.";
        $_SESSION['status'] = "500";
        header("Location: ../register.php");
        exit(); 
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
        exit(); 
    } else {
        $_SESSION['message'] = "Invalid username or password.";
        $_SESSION['status'] = "500";
        header("Location: ../login.php");
        exit();
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
if (isset($_POST['applyJob'])) {
    $jobId = $_POST['job_id'];
    $applicantId = $_POST['applicant_id'];
    $experience = $_POST['experience'];
    $college = $_POST['college'];

    if (applyForJob($jobId, $applicantId, $experience, $college)) {
        header("Location: ../views/applicant_dashboard.php"); 
        exit();
    } else {
        echo "There was an issue applying for the job.";
    }
}




if (isset($_POST['updateApplicationStatus'])) {
    $applicationId = $_POST['application_id'];
    $status = $_POST['updateApplicationStatus'];
    $message = $_POST['message'] ?? '';

    if ($status === 'accepted' || $status === 'rejected') {
        $updateResult = updateApplicationStatus($applicationId, $status, $message);

        if ($updateResult) {
            header("Location: ../views/view_applications.php?job_id=" . $_POST['job_id']);
            exit();
        } else {
            echo "Error updating status.";
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['getHRPosts'])) {
    $hrId = $_POST['hr_id'];
    $jobPosts = getJobPostsByHR($hrId);
    echo json_encode($jobPosts);
}

if (isset($_POST['sendMessage'])) {
    $senderId = $_SESSION['user_id'];  
    $receiverId = $_POST['receiver_id'];  
    $message = $_POST['message'];  

    $result = sendMessage($senderId, $receiverId, $message);

    if ($result) {
        header("Location: ../views/applicant_dashboard.php");
        exit();
    } else {
        echo "Failed to send the message.";
    }
}

if (isset($_POST['sendMessage'])) {
    $senderId = $_SESSION['user_id'];  
    $receiverId = $_POST['receiver_id'];  
    $message = $_POST['message'];  

    $result = sendMessage($senderId, $receiverId, $message);

    if ($result) {
        header("Location: ../views/hr_dashboard.php");
        exit();
    } else {
        echo "Failed to send the message.";
    }
}

?>
