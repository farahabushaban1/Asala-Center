<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get JSON data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    // Fallback to POST data
    $input = $_POST;
}

// Get cart data
$cart = isset($input['cart']) ? $input['cart'] : [];
$paymentMethod = isset($input['payment_method']) ? sanitizeInput($input['payment_method']) : 'whatsapp';
$customerName = isset($input['customer_name']) ? sanitizeInput($input['customer_name']) : 'Guest';
$customerEmail = isset($input['customer_email']) ? sanitizeInput($input['customer_email']) : 'guest@example.com';
$customerPhone = isset($input['customer_phone']) ? sanitizeInput($input['customer_phone']) : '';
$customerAddress = isset($input['customer_address']) ? sanitizeInput($input['customer_address']) : '';
$whatsappMessage = isset($input['whatsapp_message']) ? $input['whatsapp_message'] : '';

if (empty($cart)) {
    $response['message'] = 'Cart is empty';
    echo json_encode($response);
    exit;
}

try {
    $db = Database::getInstance();
    
    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += floatval($item['price']) * intval($item['quantity']);
    }
    
    // Create order message with payment method
    $orderMessage = $whatsappMessage;
    if (empty($orderMessage)) {
        $orderMessage = "Payment Method: " . ($paymentMethod === 'whatsapp' ? 'WhatsApp' : 'Credit Card');
    } else {
        $orderMessage .= "\n\nPayment Method: " . ($paymentMethod === 'whatsapp' ? 'WhatsApp' : 'Credit Card');
    }
    
    // Begin transaction
    $db->beginTransaction();
    
    // Insert order
    $orderId = $db->insert('orders', [
        'customer_name' => $customerName,
        'customer_email' => $customerEmail,
        'customer_phone' => $customerPhone,
        'customer_address' => $customerAddress,
        'total_amount' => $total,
        'status' => 'pending',
        'whatsapp_message' => $orderMessage
    ]);
    
    // Insert order items
    foreach ($cart as $item) {
        $db->insert('order_items', [
            'order_id' => $orderId,
            'product_id' => intval($item['product_id']),
            'product_name' => sanitizeInput($item['product_name']),
            'quantity' => intval($item['quantity']),
            'price' => floatval($item['price'])
        ]);
    }
    
    // Commit transaction
    $db->commit();
    
    $response['success'] = true;
    $response['message'] = 'Order created successfully';
    $response['order_id'] = $orderId;
    
} catch (Exception $e) {
    try {
        $db->rollback();
    } catch (Exception $rollbackException) {
        // Transaction might not have started
    }
    $response['message'] = 'Error creating order: ' . $e->getMessage();
}

echo json_encode($response);
?>

