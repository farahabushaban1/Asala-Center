<?php
// Quick fix script for products table AUTO_INCREMENT issue
require_once '../config/config.php';
require_once '../config/database.php';

session_start();
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied. Please log in first.");
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<html><head><title>Fix Products Table</title></head><body style='font-family: Arial; padding: 20px;'>";
    echo "<h2>Fixing Products Table AUTO_INCREMENT...</h2>";
    
    // First, show current status
    echo "<h3>Current Status:</h3>";
    $stmt = $conn->query("SHOW TABLE STATUS LIKE 'products'");
    $statusBefore = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Current AUTO_INCREMENT:</strong> " . ($statusBefore['Auto_increment'] ?? 'NULL') . "</p>";
    
    // Check for rows with id = 0
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE id = 0");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    $rowsWithZeroId = $count['count'];
    
    if ($rowsWithZeroId > 0) {
        echo "<p style='color: orange;'>⚠ Found $rowsWithZeroId row(s) with ID = 0. Deleting them...</p>";
        $conn->exec("DELETE FROM products WHERE id = 0");
        echo "<p style='color: green;'>✓ Deleted $rowsWithZeroId row(s) with ID = 0</p>";
    } else {
        echo "<p style='color: green;'>✓ No rows with ID = 0 found</p>";
    }
    
    // Get max ID (excluding 0)
    $stmt = $conn->query("SELECT MAX(id) as max_id FROM products WHERE id > 0");
    $max = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxId = intval($max['max_id'] ?? 0);
    
    echo "<p><strong>Maximum ID in table:</strong> " . ($maxId > 0 ? $maxId : 'None') . "</p>";
    
    // Set AUTO_INCREMENT to max_id + 1 (minimum 1)
    $newAutoIncrement = max(1, $maxId + 1);
    
    echo "<p style='color: blue;'>Setting AUTO_INCREMENT to: $newAutoIncrement</p>";
    $conn->exec("ALTER TABLE products AUTO_INCREMENT = $newAutoIncrement");
    echo "<p style='color: green;'>✓ Set AUTO_INCREMENT to $newAutoIncrement</p>";
    
    // Verify
    $stmt = $conn->query("SHOW TABLE STATUS LIKE 'products'");
    $statusAfter = $stmt->fetch(PDO::FETCH_ASSOC);
    $finalAutoIncrement = $statusAfter['Auto_increment'] ?? 'NULL';
    echo "<p style='color: green; font-size: 16px;'><strong>✓ New AUTO_INCREMENT value: $finalAutoIncrement</strong></p>";
    
    if ($finalAutoIncrement == 'NULL' || $finalAutoIncrement == 0) {
        echo "<p style='color: red;'><strong>⚠ WARNING: AUTO_INCREMENT is still NULL or 0! This indicates a table structure problem.</strong></p>";
    }
    
    // Also check if there are any products with id=0 and fix them
    $stmt = $conn->query("SELECT id, name FROM products WHERE id = 0");
    $zeroIdProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($zeroIdProducts)) {
        echo "<h3>Fixing Products with ID = 0:</h3>";
        foreach ($zeroIdProducts as $product) {
            $updateId = $maxId + 1;
            $conn->exec("UPDATE products SET id = $updateId WHERE id = 0 AND name = " . $conn->quote($product['name']) . " LIMIT 1");
            echo "<p style='color: green;'>✓ Fixed product '{$product['name']}' - assigned ID: $updateId</p>";
            $maxId = $updateId;
        }
        
        // Update AUTO_INCREMENT again after fixing
        $newAutoIncrement = max(1, $maxId + 1);
        $conn->exec("ALTER TABLE products AUTO_INCREMENT = $newAutoIncrement");
        echo "<p style='color: green;'>✓ Updated AUTO_INCREMENT to $newAutoIncrement</p>";
    }
    
    echo "<hr>";
    echo "<p style='color: green; font-size: 18px;'><strong>✓ FIX COMPLETE!</strong></p>";
    echo "<p><a href='products.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>← Back to Products</a></p>";
    echo "<p><small>You can now try adding a product again.</small></p>";
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='products.php'>← Back to Products</a></p>";
}
?>

