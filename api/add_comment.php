<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $db = Database::getInstance();
    
    // Get and validate input
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    
    // Validate required fields
    if (empty($product_id) || $product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    if (empty($name) || strlen($name) < 2) {
        echo json_encode(['success' => false, 'message' => 'Name must be at least 2 characters']);
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Valid email is required']);
        exit;
    }
    
    if (empty($comment) || strlen($comment) < 5) {
        echo json_encode(['success' => false, 'message' => 'Comment must be at least 5 characters']);
        exit;
    }
    
    // Validate rating if provided (1-5)
    if ($rating !== null && ($rating < 1 || $rating > 5)) {
        $rating = null;
    }
    
    // Check if product exists
    $product = $db->fetch("SELECT id FROM products WHERE id = :id", ['id' => $product_id]);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    
    // Insert comment
    $data = [
        'product_id' => $product_id,
        'name' => $name,
        'email' => $email,
        'comment' => $comment,
        'status' => 'approved'
    ];
    
    if ($rating !== null) {
        $data['rating'] = $rating;
    }
    
    $comment_id = $db->insert('comments', $data);
    
    // Get the created comment
    $newComment = $db->fetch("SELECT * FROM comments WHERE id = :id", ['id' => $comment_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Comment added successfully',
        'comment' => $newComment
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

