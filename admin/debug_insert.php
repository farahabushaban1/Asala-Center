<?php
// Debug script to check products table and test insert
require_once '../config/config.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied");
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<html><head><title>Debug Products Insert</title></head><body style='font-family: Arial; padding: 20px;'>";
    echo "<h2>Products Table Debug Information</h2>";
    
    // Check table structure
    echo "<h3>1. Table Structure:</h3>";
    $stmt = $conn->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        $highlight = ($col['Field'] == 'id') ? "style='background: #ffffcc;'" : "";
        echo "<tr $highlight>";
        echo "<td><strong>{$col['Field']}</strong></td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check AUTO_INCREMENT status
    echo "<h3>2. AUTO_INCREMENT Status:</h3>";
    $stmt = $conn->query("SHOW TABLE STATUS LIKE 'products'");
    $status = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Current AUTO_INCREMENT value:</strong> " . ($status['Auto_increment'] ?? 'NULL') . "</p>";
    
    // List all products
    echo "<h3>3. All Products in Database:</h3>";
    $stmt = $conn->query("SELECT id, name, category_id, created_at FROM products ORDER BY id");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($products)) {
        echo "<p>No products found.</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category ID</th><th>Created At</th></tr>";
        foreach ($products as $product) {
            $highlight = ($product['id'] == 0) ? "style='background: #ffcccc;'" : "";
            echo "<tr $highlight>";
            echo "<td><strong>{$product['id']}</strong></td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . ($product['category_id'] ?? 'NULL') . "</td>";
            echo "<td>" . ($product['created_at'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check for duplicate IDs
    echo "<h3>4. Check for Duplicate IDs:</h3>";
    $stmt = $conn->query("SELECT id, COUNT(*) as count FROM products GROUP BY id HAVING COUNT(*) > 1");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($duplicates)) {
        echo "<p style='color: green;'>✓ No duplicate IDs found.</p>";
    } else {
        echo "<p style='color: red;'>✗ Found duplicate IDs:</p>";
        echo "<pre>" . print_r($duplicates, true) . "</pre>";
    }
    
    // Test insert (if button clicked)
    if (isset($_GET['test_insert'])) {
        echo "<h3>5. Testing Insert:</h3>";
        
        try {
            $testData = [
                'name' => 'Test Product ' . time(),
                'slug' => 'test-product-' . time(),
                'description' => 'Test description',
                'price' => 99.99,
                'stock_quantity' => 10,
                'category_id' => null
            ];
            
            echo "<p>Attempting to insert with data:</p>";
            echo "<pre>" . print_r($testData, true) . "</pre>";
            
            // Check which columns exist
            $stmt = $conn->query("DESCRIBE products");
            $existingColumns = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $existingColumns[] = $row['Field'];
            }
            
            // Build insert data
            $insertData = [];
            foreach ($testData as $key => $value) {
                if (in_array($key, $existingColumns)) {
                    $insertData[$key] = $value;
                }
            }
            
            echo "<p>Insert data (after filtering):</p>";
            echo "<pre>" . print_r($insertData, true) . "</pre>";
            
            // Make sure 'id' is not in insertData
            if (isset($insertData['id'])) {
                unset($insertData['id']);
                echo "<p style='color: orange;'>⚠ Removed 'id' from insert data</p>";
            }
            
            $columns = implode(', ', array_keys($insertData));
            $placeholders = ':' . implode(', :', array_keys($insertData));
            $sql = "INSERT INTO products ($columns) VALUES ($placeholders)";
            
            echo "<p>SQL Query:</p>";
            echo "<pre>" . htmlspecialchars($sql) . "</pre>";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($insertData);
            
            $newId = $conn->lastInsertId();
            echo "<p style='color: green;'><strong>✓ Insert successful! New ID: $newId</strong></p>";
            
            if ($newId == 0) {
                echo "<p style='color: red;'><strong>✗ PROBLEM: lastInsertId() returned 0!</strong></p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'><strong>✗ Error: " . htmlspecialchars($e->getMessage()) . "</strong></p>";
        }
    } else {
        echo "<h3>5. Test Insert:</h3>";
        echo "<p><a href='?test_insert=1' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Insert</a></p>";
    }
    
    // Fix button
    echo "<hr>";
    echo "<h3>Fix AUTO_INCREMENT:</h3>";
    if (isset($_GET['fix'])) {
        // Delete rows with id = 0
        $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE id = 0");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $rowsWithZeroId = $count['count'];
        
        if ($rowsWithZeroId > 0) {
            $conn->exec("DELETE FROM products WHERE id = 0");
            echo "<p style='color: green;'>✓ Deleted $rowsWithZeroId row(s) with ID = 0</p>";
        }
        
        // Get max ID
        $stmt = $conn->query("SELECT MAX(id) as max_id FROM products");
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxId = intval($max['max_id'] ?? 0);
        
        // Set AUTO_INCREMENT
        $newAutoIncrement = max(1, $maxId + 1);
        $conn->exec("ALTER TABLE products AUTO_INCREMENT = $newAutoIncrement");
        echo "<p style='color: green;'>✓ Set AUTO_INCREMENT to $newAutoIncrement</p>";
        
        echo "<p style='color: green; font-size: 18px;'><strong>✓ FIX COMPLETE! Refresh page to see changes.</strong></p>";
    } else {
        echo "<p><a href='?fix=1' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Fix AUTO_INCREMENT Now</a></p>";
    }
    
    echo "<p><a href='products.php'>← Back to Products</a></p>";
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

