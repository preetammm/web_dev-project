<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = clean_input($_POST["username"]);
    $email = clean_input($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please fill in all fields";
        redirect("register.php");
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        redirect("register.php");
    }
    
    // Check if passwords match
    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        redirect("register.php");
    }
    
    // Check password length
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long";
        redirect("register.php");
    }
    
    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error'] = "Username already exists";
        redirect("register.php");
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error'] = "Email already exists";
        redirect("register.php");
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare an insert statement
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Registration successful
            $_SESSION['success'] = "Registration successful! You can now login.";
            redirect("index.php");
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
            redirect("register.php");
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

// If we get here, something went wrong
$_SESSION['error'] = "Invalid request";
redirect("register.php");
?>
