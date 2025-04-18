<?php
// Start session if not already started
function start_session_if_not_started() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Clean input data
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = mysqli_real_escape_string($conn, $data);
    }
    return $data;
}

// Check if user is logged in
function is_logged_in() {
    start_session_if_not_started();
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    start_session_if_not_started();
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Redirect to a specific page
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get user by ID
function get_user_by_id($user_id) {
    global $conn;
    $sql = "SELECT id, username, email, role FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Get all categories
function get_all_categories() {
    global $conn;
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

// Get category name by ID
function get_category_name($category_id) {
    global $conn;
    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['name'] : 'Unknown';
}

// Format date
function format_date($date) {
    return date("F j, Y, g:i a", strtotime($date));
}

// Get status badge class
function get_status_badge_class($status) {
    switch ($status) {
        case 'pending':
            return 'badge bg-warning';
        case 'in_progress':
            return 'badge bg-info';
        case 'resolved':
            return 'badge bg-success';
        case 'rejected':
            return 'badge bg-danger';
        default:
            return 'badge bg-secondary';
    }
}

// Get formatted status
function get_formatted_status($status) {
    switch ($status) {
        case 'pending':
            return 'Pending';
        case 'in_progress':
            return 'In Progress';
        case 'resolved':
            return 'Resolved';
        case 'rejected':
            return 'Rejected';
        default:
            return 'Unknown';
    }
}
?>
