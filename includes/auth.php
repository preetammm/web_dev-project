<?php
require_once 'functions.php';

// Check if user is logged in, if not redirect to login page
function require_login() {
    start_session_if_not_started();
    if (!is_logged_in()) {
        $_SESSION['error'] = "You must be logged in to access this page";
        redirect("index.php");
    }
}

// Check if user is admin, if not redirect to dashboard
function require_admin() {
    start_session_if_not_started();
    require_login();
    if (!is_admin()) {
        $_SESSION['error'] = "You don't have permission to access this page";
        redirect("dashboard.php");
    }
}
?>
