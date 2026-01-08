<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'Asala Center');
define('APP_URL', 'http://localhost/Asala-Center');
define('APP_ENV', 'dev'); // 'dev' or 'prod'

// Security configuration
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_NAME', 'asala_session');

// File upload configuration
define('UPLOAD_DIR', 'assets/images/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Pagination
define('ITEMS_PER_PAGE', 12);

// WhatsApp configuration
define('WHATSAPP_NUMBER', '0592310435');
define('WHATSAPP_COUNTRY_CODE', '972');

// Error reporting
if (APP_ENV === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Gaza');
?>

