<?php
require_once 'dbconfig.php';


// Registration function
function registerUser($username, $password, $role) {
    global $pdo;
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
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
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    
    $user = $query->fetch(PDO::FETCH_ASSOC); // Use fetch() with PDO
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;  
    }
    return false;
}

// Job Posts
function createJobPost($title, $description, $createdBy) {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO job_posts (title, description, created_by) VALUES (:title, :description, :createdBy)");
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
    global $pdo;
    $query = $pdo->query("SELECT * FROM job_posts");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


function applyForJob($jobId, $applicantId, $experience, $college) {
    global $pdo;  
    $query = "INSERT INTO applications (job_id, applicant_id, years_of_experience, college) 
              VALUES (:job_id, :applicant_id, :experience, :college)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $jobId);
    $stmt->bindParam(':applicant_id', $applicantId);
    $stmt->bindParam(':experience', $experience);
    $stmt->bindParam(':college', $college);

    return $stmt->execute(); 
}




function updateApplicationStatus($appId, $status, $message) {
    global $pdo;
    $query = $pdo->prepare("UPDATE applications SET status = :status, message = :message WHERE id = :appId");
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
    global $pdo; 
    $query = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:senderId, :receiverId, :message)");
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
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM messages WHERE receiver_id = :userId OR sender_id = :userId");
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJobPostsByHR($hrId) {
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM job_posts WHERE created_by = :hrId");
    $query->bindParam(':hrId', $hrId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJobPostsForApplicants() {
    global $pdo;
    $query = $pdo->query("SELECT * FROM job_posts");
    return $query->fetchAll(PDO::FETCH_ASSOC);  
}


function deleteJobPost($jobId) {
    global $pdo;
    $query = $pdo->prepare("DELETE FROM job_posts WHERE id = :jobId");
    $query->bindParam(':jobId', $jobId, PDO::PARAM_INT);

    try {
        $query->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getHrUsers() {
    global $pdo;
    $query = $pdo->query("SELECT id, username FROM users WHERE role = 'hr'");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


function getMessagesForHR($hrId) {
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM messages WHERE receiver_id = :hrId");
    $query->bindParam(':hrId', $hrId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


function getApplicationsForJob($jobId) {
    global $pdo;
    
    $query = "
        SELECT a.id, a.job_id, a.applicant_id, a.status, u.username AS applicant_name
        FROM applications a
        LEFT JOIN users u ON a.applicant_id = u.id
        WHERE a.job_id = :job_id
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getApplicationsForApplicant($applicantId) {
    global $pdo; 

    $sql = "SELECT a.*, j.title as job_title 
            FROM applications a 
            JOIN job_posts j ON a.job_id = j.id
            WHERE a.applicant_id = :applicant_id";
    
    $stmt = $pdo->prepare($sql); 
    $stmt->bindParam(':applicant_id', $applicantId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
