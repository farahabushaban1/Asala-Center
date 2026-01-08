<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Re-throw exception instead of die, so calling code can handle it
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function insert($table, $data) {
        // IMPORTANT: Remove 'id' from data if present - it's AUTO_INCREMENT
        // This prevents "Duplicate entry '0' for key 'PRIMARY'" error
        if (isset($data['id'])) {
            unset($data['id']);
        }
        
        // Filter out null values for columns that don't accept them
        // But keep null values for columns that do (like category_id)
        $filteredData = [];
        foreach ($data as $key => $value) {
            // Skip 'id' field completely - it's AUTO_INCREMENT
            if ($key === 'id') {
                continue;
            }
            // Keep null values, they are valid for nullable columns
            $filteredData[$key] = $value;
        }
        
        if (empty($filteredData)) {
            throw new Exception("No data provided for insert");
        }
        
        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $this->query($sql, $filteredData);
            $insertId = $this->connection->lastInsertId();
            
            // If lastInsertId returns 0, it means AUTO_INCREMENT is broken
            // We need to check if a row was actually inserted with id=0 and fix it
            if ($insertId == 0 || $insertId === false) {
                // Check if there's a row with id=0 that was just inserted
                $checkStmt = $this->connection->query("SELECT id FROM $table WHERE id = 0 LIMIT 1");
                $checkRow = $checkStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($checkRow) {
                    // There's a row with id=0, we need to fix it
                    // First get max ID
                    $maxStmt = $this->connection->query("SELECT MAX(id) as max_id FROM $table WHERE id > 0");
                    $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);
                    $maxId = intval($maxRow['max_id'] ?? 0);
                    $newId = max(1, $maxId + 1);
                    
                    // Update the row with id=0 to have the correct ID
                    $this->connection->exec("UPDATE $table SET id = $newId WHERE id = 0 LIMIT 1");
                    
                    // Fix AUTO_INCREMENT
                    $this->connection->exec("ALTER TABLE $table AUTO_INCREMENT = " . ($newId + 1));
                    
                    return $newId;
                } else {
                    // No row with id=0, but lastInsertId() returned 0
                    // Check if a row was actually inserted by checking max ID
                    $maxStmt = $this->connection->query("SELECT MAX(id) as max_id FROM $table WHERE id > 0");
                    $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);
                    $newMaxId = intval($maxRow['max_id'] ?? 0);
                    
                    if ($newMaxId > 0) {
                        // A row was inserted, return the max ID
                        // But we still need to fix AUTO_INCREMENT
                        $this->connection->exec("ALTER TABLE $table AUTO_INCREMENT = " . ($newMaxId + 1));
                        return $newMaxId;
                    } else {
                        // No rows found - something went wrong
                        throw new Exception("AUTO_INCREMENT is broken. Please run admin/fix_auto_increment.php to fix it.");
                    }
                }
            }
            
            return $insertId;
        } catch (Exception $e) {
            // If error is about duplicate entry for PRIMARY key with '0'
            // It might be that AUTO_INCREMENT is broken or there's a row with id=0
            if (strpos($e->getMessage(), "Duplicate entry '0' for key 'PRIMARY'") !== false) {
                // Try to fix AUTO_INCREMENT
                try {
                    // Delete any rows with id=0
                    $this->connection->exec("DELETE FROM $table WHERE id = 0");
                    
                    // Get max ID (excluding 0)
                    $maxStmt = $this->connection->query("SELECT MAX(id) as max_id FROM $table WHERE id > 0");
                    $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);
                    $maxId = intval($maxRow['max_id'] ?? 0);
                    
                    // Reset AUTO_INCREMENT
                    $newAutoIncrement = max(1, $maxId + 1);
                    $this->connection->exec("ALTER TABLE $table AUTO_INCREMENT = $newAutoIncrement");
                    
                    // Retry the insert
                    $this->query($sql, $filteredData);
                    $retryId = $this->connection->lastInsertId();
                    
                    // If still 0, try one more time with a fresh query
                    if ($retryId == 0 || $retryId === false) {
                        $maxStmt3 = $this->connection->query("SELECT MAX(id) as max_id FROM $table WHERE id > 0");
                        $maxRow3 = $maxStmt3->fetch(PDO::FETCH_ASSOC);
                        $retryId = intval($maxRow3['max_id'] ?? 0);
                    }
                    
                    return $retryId;
                } catch (Exception $fixException) {
                    // If fix fails, throw original error
                    throw $e;
                }
            } else {
                // Re-throw other errors
                throw $e;
            }
        }
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $setClause = [];
        $params = [];
        
        // Build SET clause with unique parameter names
        foreach ($data as $column => $value) {
            $paramName = "set_" . $column;
            $setClause[] = "$column = :$paramName";
            $params[$paramName] = $value;
        }
        
        $setClause = implode(', ', $setClause);
        $sql = "UPDATE $table SET $setClause WHERE $where";
        
        // Add WHERE parameters with unique names
        foreach ($whereParams as $key => $value) {
            $params[$key] = $value;
        }
        
        return $this->query($sql, $params)->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql, $params)->rowCount();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollback();
    }
}

// Helper function to get database instance
function db() {
    return Database::getInstance();
}
?>


