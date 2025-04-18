<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login
require_login();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = clean_input($_POST["title"]);
    $category_id = clean_input($_POST["category_id"]);
    $description = clean_input($_POST["description"]);
    $user_id = $_SESSION["user_id"];
    
    // Validate input
    if (empty($title) || empty($category_id) || empty($description)) {
        $_SESSION['error'] = "Please fill in all fields";
        redirect("submit_grievance.php");
    }
    
    // Validate category exists
    $sql = "SELECT id FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 0) {
        $_SESSION['error'] = "Invalid category";
        redirect("submit_grievance.php");
    }
    
    // Prepare an insert statement
    $sql = "INSERT INTO grievances (user_id, title, description, category_id, status) VALUES (?, ?, ?, ?, 'pending')";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "issi", $user_id, $title, $description, $category_id);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the ID of the new grievance
            $grievance_id = mysqli_insert_id($conn);
            
            // Submission successful
            $_SESSION['success'] = "Grievance submitted successfully!";
            redirect("grievance_detail.php?id=" . $grievance_id);
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
            redirect("submit_grievance.php");
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

// If we get here, something went wrong
$_SESSION['error'] = "Invalid request";
redirect("submit_grievance.php");
?>
