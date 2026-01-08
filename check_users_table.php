<?php
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    // Check if users table exists
    $result = $db->query("SHOW TABLES LIKE 'users'");
    $tableExists = $result->rowCount() > 0;
    
    echo "Users table exists: " . ($tableExists ? "YES" : "NO") . "<br><br>";
    
    if ($tableExists) {
        // Get table structure
        $structure = $db->fetchAll("DESCRIBE users");
        echo "<h3>Table Structure:</h3>";
        echo "<pre>";
        print_r($structure);
        echo "</pre><br>";
        
        // Get table data
        $users = $db->fetchAll("SELECT * FROM users");
        echo "<h3>Table Data:</h3>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>






