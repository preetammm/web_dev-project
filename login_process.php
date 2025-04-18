<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username/email and password
    $username = clean_input($_POST["username"]);
    $password = $_POST["password"];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields";
        redirect("index.php");
    }

    // Check if username is email or username
    $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Prepare a select statement
    $sql = "SELECT id, username, email, password, role FROM users WHERE $field = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $username);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $role);

                if (mysqli_stmt_fetch($stmt)) {
                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        session_regenerate_id();

                        // Store data in session variables
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["email"] = $email;
                        $_SESSION["role"] = $role;
                        $_SESSION["logged_in"] = true;

                        // Redirect based on role
                        $_SESSION['success'] = "Welcome back, $username!";

                        // If admin, redirect to admin dashboard
                        if ($role == 'admin') {
                            redirect("admin/dashboard.php");
                        } else {
                            redirect("dashboard.php");
                        }
                    } else {
                        // Password is not valid
                        $_SESSION['error'] = "Invalid password";
                        redirect("index.php");
                    }
                }
            } else {
                // Username doesn't exist
                $_SESSION['error'] = "No account found with that username/email";
                redirect("index.php");
            }
        } else {
            $_SESSION['error'] = "Oops! Something went wrong. Please try again later.";
            redirect("index.php");
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

// If we get here, something went wrong
$_SESSION['error'] = "Invalid request";
redirect("index.php");
?>
