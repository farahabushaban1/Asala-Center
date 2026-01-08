<?php
// Script to create comments table
require_once '../config/config.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied. Please log in first.");
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $sql = "CREATE TABLE IF NOT EXISTS `comments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `product_id` int(11) NOT NULL,
      `name` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `comment` text NOT NULL,
      `rating` tinyint(1) DEFAULT NULL COMMENT 'Rating from 1 to 5',
      `status` enum('pending','approved','rejected') DEFAULT 'approved',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `product_id` (`product_id`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $conn->exec($sql);
    
    // Check if foreign key exists, if not add it
    try {
        $fkCheck = $conn->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'comments' AND CONSTRAINT_NAME = 'comments_ibfk_1'");
        if ($fkCheck->rowCount() == 0) {
            $conn->exec("ALTER TABLE `comments` ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE");
        }
    } catch (Exception $e) {
        // Foreign key might already exist or products table doesn't exist, ignore
    }
    
    echo "<html><head><title>Create Comments Table</title></head><body style='font-family: Arial; padding: 20px;'>";
    echo "<h2 style='color: green;'>✓ Comments table created successfully!</h2>";
    echo "<p><a href='products.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>← Back to Products</a></p>";
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<html><head><title>Error</title></head><body style='font-family: Arial; padding: 20px;'>";
    echo "<h2 style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
    echo "<p><a href='products.php'>← Back to Products</a></p>";
    echo "</body></html>";
}
?>

