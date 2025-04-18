<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $grievance_id = clean_input($_POST["grievance_id"]);
    $status = clean_input($_POST["status"]);
    
    // Validate input
    if (empty($grievance_id) || empty($status)) {
        $_SESSION['error'] = "Please fill in all fields";
        redirect("../grievance_detail.php?id=" . $grievance_id);
    }
    
    // Validate status
    $valid_statuses = ['pending', 'in_progress', 'resolved', 'rejected'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status";
        redirect("../grievance_detail.php?id=" . $grievance_id);
    }
    
    // Check if grievance exists
    $sql = "SELECT id FROM grievances WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $grievance_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 0) {
        $_SESSION['error'] = "Grievance not found";
        redirect("dashboard.php");
    }
    
    // Prepare an update statement
    $sql = "UPDATE grievances SET status = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $status, $grievance_id);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Status updated successfully
            $_SESSION['success'] = "Grievance status updated successfully!";
            
            // Add a comment about the status change
            $user_id = $_SESSION["user_id"];
            $comment = "Status changed to " . get_formatted_status($status) . " by admin.";
            
            $sql = "INSERT INTO comments (grievance_id, user_id, comment) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iis", $grievance_id, $user_id, $comment);
            mysqli_stmt_execute($stmt);
            
            redirect("../grievance_detail.php?id=" . $grievance_id);
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
            redirect("../grievance_detail.php?id=" . $grievance_id);
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
