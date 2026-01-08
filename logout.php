<?php
// Start session
session_start();

// Log activity before logout
if (isset($_SESSION['admin_username'])) {
    require_once '../includes/functions.php';
    logActivity('Admin logout', "Admin {$_SESSION['admin_username']} logged out");
}

// Destroy session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
?>

