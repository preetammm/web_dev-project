<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login
require_login();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $grievance_id = clean_input($_POST["grievance_id"]);
    $comment = clean_input($_POST["comment"]);
    $user_id = $_SESSION["user_id"];
    
    // Validate input
    if (empty($grievance_id) || empty($comment)) {
        $_SESSION['error'] = "Please fill in all fields";
        redirect("grievance_detail.php?id=" . $grievance_id);
    }
    
    // Check if grievance exists and user has access
    $sql = "SELECT id FROM grievances WHERE id = ?";
    
    if (!is_admin()) {
        // If not admin, only allow comments on user's own grievances
        $sql .= " AND user_id = ?";
    }
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!is_admin()) {
        mysqli_stmt_bind_param($stmt, "ii", $grievance_id, $user_id);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $grievance_id);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 0) {
        $_SESSION['error'] = "Grievance not found or you don't have permission to comment";
        redirect("dashboard.php");
    }
    
    // Prepare an insert statement
    $sql = "INSERT INTO comments (grievance_id, user_id, comment) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iis", $grievance_id, $user_id, $comment);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Comment added successfully
            $_SESSION['success'] = "Comment added successfully!";
            redirect("grievance_detail.php?id=" . $grievance_id);
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
            redirect("grievance_detail.php?id=" . $grievance_id);
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

// If we get here, something went wrong
$_SESSION['error'] = "Invalid request";
redirect("dashboard.php");
?>
