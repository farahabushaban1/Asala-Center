<?php
// Include required files
require_once 'config/config.php';

// Session configuration (must be before session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', APP_ENV === 'prod');
session_name(SESSION_NAME);

// Start session
session_start();

// Include helper functions
require_once 'includes/functions.php';

// Check for invalid path info (e.g., /index.php/dds)
if (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.html");
    exit;
}

// Set default language if not set
if (!isset($_GET['lang']) || empty($_GET['lang'])) {
    $_GET['lang'] = 'en';
}

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Define allowed pages
$allowed_pages = ['home', 'about', 'heritage', 'product', 'cart', 'contact'];

// Validate page
if (!in_array($page, $allowed_pages)) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.html");
    exit;
}

// Include the requested page
$page_file = "pages/{$page}.php";

if (file_exists($page_file)) {
    include $page_file;
} else {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.html");
    exit;
}
?>


