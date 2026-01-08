<?php
// Load language file
function loadLanguage($lang = 'en') {
    $langFile = "languages/{$lang}.php";
    if (file_exists($langFile)) {
        return include $langFile;
    }
    return include 'languages/en.php';
}

// Get translation
function t($key, $lang = 'en') {
    static $translations = null;
    
    if ($translations === null) {
        $translations = loadLanguage($lang);
    }
    
    return isset($translations[$key]) ? $translations[$key] : $key;
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Format price
function formatPrice($price) {
    return number_format($price, 0) . ' ₪';
}

// Get current page
function getCurrentPage() {
    // Check if we're on a 404 page
    if (isset($_GET['page']) && $_GET['page'] === '404') {
        return 'home'; // Return home for 404 pages
    }
    return isset($_GET['page']) ? $_GET['page'] : 'home';
}

// Check if page is active
function isActivePage($page) {
    return getCurrentPage() === $page;
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Show alert message
function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Get and clear alert
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate slug
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

// Generate unique slug
function createUniqueSlug($text, $table = 'products', $excludeId = null) {
    $baseSlug = generateSlug($text);
    $slug = $baseSlug;
    $counter = 1;
    
    try {
        $db = Database::getInstance();
        
        while (true) {
            if ($excludeId) {
                $sql = "SELECT COUNT(*) as count FROM {$table} WHERE slug = :slug AND id != :exclude_id";
                $params = ['slug' => $slug, 'exclude_id' => $excludeId];
            } else {
                $sql = "SELECT COUNT(*) as count FROM {$table} WHERE slug = :slug";
                $params = ['slug' => $slug];
            }
            
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                break;
            }
            
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    } catch (Exception $e) {
        // Fallback to base slug with timestamp if database error
        return $baseSlug . '-' . time();
    }
}

// Get cart count
function getCartCount() {
    if (isset($_SESSION['cart'])) {
        return array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
    return 0;
}

// Get current language with session support
function getCurrentLanguage() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Get current language from GET parameter or session
    if (isset($_GET['lang'])) {
        $currentLang = $_GET['lang'];
        $_SESSION['lang'] = $currentLang; // Save to session
    } else {
        $currentLang = $_SESSION['lang'] ?? 'en'; // Get from session or default
    }
    
    return $currentLang;
}

// Initialize language system
function initLanguage() {
    $currentLang = getCurrentLanguage();
    return loadLanguage($currentLang);
}

// Create URL with current language
function createUrl($page = '', $params = []) {
    // If lang is specified in params, use it, otherwise get current language
    if (!isset($params['lang'])) {
        $params['lang'] = getCurrentLanguage();
    }
    
    if ($page) {
        $params['page'] = $page;
    }
    
    $query = http_build_query($params);
    return $query ? "?$query" : '';
}

// Add to cart
function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'quantity' => $quantity
        ];
    }
}

// Remove from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Clear cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Get cart total
function getCartTotal() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        // In real app, get price from database
        $total += $item['quantity'] * 100; // Placeholder price
    }
    return $total;
}

// Log activity
function logActivity($action, $details = '') {
    $logFile = 'logs/activity.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $action: $details\n";
    
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Get site settings
function getSiteSettings() {
    try {
        $db = Database::getInstance();
        $settings = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    } catch (Exception $e) {
        // Return default settings if database error
        return [
            'site_name' => 'أصالة سنتر للتطريز الشرقي',
            'site_description' => 'يتخصص أصالة سنتر في التطريز الشرقي والثوب الفلسطيني. نحافظ على التراث ونقدم أجمل القطع المطرزة يدوياً.',
            'contact_email' => 'FarahAbuShaban@gmail.com',
            'contact_phone' => '0592310435',
            'contact_address' => 'Palestine - Gaza',
            'whatsapp_number' => '0592310435',
            'facebook_url' => 'https://facebook.com/asalacenter',
            'instagram_url' => 'https://instagram.com/asalacenter',
            'working_hours' => 'الأحد - الخميس: 9:00 ص - 6:00 م',
            'currency' => 'ILS',
            'currency_symbol' => '₪'
        ];
    }
}

// Get specific setting
function getSetting($key, $default = '') {
    $settings = getSiteSettings();
    return isset($settings[$key]) ? $settings[$key] : $default;
}

// Resize and optimize image
function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 600, $quality = 85) {
    // Check if GD extension is available
    if (!extension_loaded('gd')) {
        // If GD is not available, just copy the file
        return copy($sourcePath, $destinationPath);
    }
    
    // Get image info
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    $originalWidth = $imageInfo[0];
    $originalHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    
    // If image is already smaller than max dimensions, just copy it
    if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
        return copy($sourcePath, $destinationPath);
    }
    
    // Calculate new dimensions while maintaining aspect ratio
    $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
    $newWidth = round($originalWidth * $ratio);
    $newHeight = round($originalHeight * $ratio);
    
    // Create image resource based on mime type
    switch ($mimeType) {
        case 'image/jpeg':
            if (!function_exists('imagecreatefromjpeg')) {
                return copy($sourcePath, $destinationPath);
            }
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            if (!function_exists('imagecreatefrompng')) {
                return copy($sourcePath, $destinationPath);
            }
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            if (!function_exists('imagecreatefromgif')) {
                return copy($sourcePath, $destinationPath);
            }
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            if (!function_exists('imagecreatefromwebp')) {
                return copy($sourcePath, $destinationPath);
            }
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return copy($sourcePath, $destinationPath);
    }
    
    if (!$sourceImage) {
        return copy($sourcePath, $destinationPath);
    }
    
    // Create new image with new dimensions
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG and GIF
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefill($newImage, 0, 0, $transparent);
    }
    
    // Resize image
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
    // Save resized image
    $success = false;
    switch ($mimeType) {
        case 'image/jpeg':
            if (function_exists('imagejpeg')) {
                $success = imagejpeg($newImage, $destinationPath, $quality);
            }
            break;
        case 'image/png':
            if (function_exists('imagepng')) {
                $success = imagepng($newImage, $destinationPath, round($quality / 10));
            }
            break;
        case 'image/gif':
            if (function_exists('imagegif')) {
                $success = imagegif($newImage, $destinationPath);
            }
            break;
        case 'image/webp':
            if (function_exists('imagewebp')) {
                $success = imagewebp($newImage, $destinationPath, $quality);
            }
            break;
    }
    
    // Clean up
    if (function_exists('imagedestroy')) {
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }
    
    return $success ? $success : copy($sourcePath, $destinationPath);
}

// Generate thumbnail for product image
function generateProductThumbnail($sourcePath, $destinationPath, $size = 300) {
    return resizeImage($sourcePath, $destinationPath, $size, $size, 85);
}
?>


