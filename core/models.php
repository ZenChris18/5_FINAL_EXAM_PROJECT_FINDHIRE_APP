<?php
require_once 'dbconfig.php';

function getDbConnection() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "FINDHIRE";  
    $dsn = "mysql:host={$host};dbname={$dbname}";
    
    try {
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit();
    }
}

// Registration function
function registerUser($username, $password, $role) {
    $conn = getDbConnection();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $query = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $passwordHash, PDO::PARAM_STR);
    $query->bindParam(':role', $role, PDO::PARAM_STR);
    
    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Login function
function loginUser($username, $password) {
    $conn = getDbConnection();
    $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    
    $user = $query->fetch(PDO::FETCH_ASSOC); // Use fetch() with PDO
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;  // Return user data on successful login
    }
    return false;
}

// Job Posts
function createJobPost($title, $description, $createdBy) {
    $conn = getDbConnection();
    $query = $conn->prepare("INSERT INTO job_posts (title, description, created_by) VALUES (:title, :description, :createdBy)");
    $query->bindParam(':title', $title, PDO::PARAM_STR);
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->bindParam(':createdBy', $createdBy, PDO::PARAM_INT);
    
    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getJobPosts() {
    $conn = getDbConnection();
    $query = $conn->query("SELECT * FROM job_posts");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Applications
function applyForJob($jobId, $applicantId, $resumePath) {
    $conn = getDbConnection();
    $query = $conn->prepare("INSERT INTO applications (job_id, applicant_id, resume_path) VALUES (:jobId, :applicantId, :resumePath)");
    $query->bindParam(':jobId', $jobId, PDO::PARAM_INT);
    $query->bindParam(':applicantId', $applicantId, PDO::PARAM_INT);
    $query->bindParam(':resumePath', $resumePath, PDO::PARAM_STR);
    
    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateApplicationStatus($appId, $status, $message) {
    $conn = getDbConnection();
    $query = $conn->prepare("UPDATE applications SET status = :status, message = :message WHERE id = :appId");
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':appId', $appId, PDO::PARAM_INT);
    
    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// message function
function sendMessage($senderId, $receiverId, $message) {
    $conn = getDbConnection();
    $query = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:senderId, :receiverId, :message)");
    $query->bindParam(':senderId', $senderId, PDO::PARAM_INT);
    $query->bindParam(':receiverId', $receiverId, PDO::PARAM_INT);
    $query->bindParam(':message', $message, PDO::PARAM_STR);

    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


function getMessages($userId) {
    $conn = getDbConnection();
    $query = $conn->prepare("SELECT * FROM messages WHERE receiver_id = :userId OR sender_id = :userId");
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch job posts specific to HR
function getJobPostsByHR($hrId) {
    $conn = getDbConnection();
    $query = $conn->prepare("SELECT * FROM job_posts WHERE created_by = :hrId");
    $query->bindParam(':hrId', $hrId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJobPostsForApplicants() {
    $conn = getDbConnection();
    $query = $conn->query("SELECT * FROM job_posts");
    $result = $query->fetchAll(PDO::FETCH_ASSOC);  // Use PDO's fetchAll method
    $conn = null;  // Close the connection (recommended practice)
    return $result;
}

function deleteJobPost($jobId) {
    $conn = getDbConnection();
    $query = $conn->prepare("DELETE FROM job_posts WHERE id = :jobId");
    $query->bindParam(':jobId', $jobId, PDO::PARAM_INT);

    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Fetch HR users for the applicants to message
function getHrUsers() {
    $conn = getDbConnection();
    $query = $conn->query("SELECT id, username FROM users WHERE role = 'hr'");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch messages for HR from the database
function getMessagesForHR($hrId) {
    $conn = getDbConnection();
    $query = $conn->prepare("SELECT * FROM messages WHERE receiver_id = :hrId");
    $query->bindParam(':hrId', $hrId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


function getApplicationsForJob($jobId) {
    global $pdo; // Assuming you are using PDO for database connection
    
    // Sample query (adjust as per your database structure)
    $query = "SELECT * FROM applications WHERE job_id = :job_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch all the applications for the job
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
