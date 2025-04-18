<?php
require_once 'includes/functions.php';

start_session_if_not_started();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit;
?>
