<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid grievance ID";
    redirect("grievances.php");
}

$grievance_id = clean_input($_GET['id']);

// Check if grievance exists
$sql = "SELECT id FROM grievances WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $grievance_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 0) {
    $_SESSION['error'] = "Grievance not found";
    redirect("grievances.php");
}

// Delete comments first
$sql = "DELETE FROM comments WHERE grievance_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $grievance_id);
mysqli_stmt_execute($stmt);

// Delete grievance
$sql = "DELETE FROM grievances WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $grievance_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Grievance deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete grievance";
}

// Check if the referrer is from the grievance detail page
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'grievance_detail.php') !== false) {
    redirect("../dashboard.php");
} else {
    // Default to grievances.php
    redirect("grievances.php");
}
?>
