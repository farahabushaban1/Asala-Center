<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../config/database.php';

try {
    $db = Database::getInstance();
    
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    
    if (empty($product_id) || $product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    // Get approved comments for the product
    $stmt = $db->query("
        SELECT * FROM comments 
        WHERE product_id = :product_id AND status = 'approved' 
        ORDER BY created_at DESC
    ", ['product_id' => $product_id]);
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

