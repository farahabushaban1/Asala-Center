<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    // Validate required fields
    $required = ['name', 'email', 'subject', 'message'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Validate email
    if (!validateEmail($input['email'])) {
        throw new Exception('Invalid email address');
    }
    
    // Sanitize input
    $name = sanitizeInput($input['name']);
    $email = sanitizeInput($input['email']);
    $phone = isset($input['phone']) ? sanitizeInput($input['phone']) : '';
    $subject = sanitizeInput($input['subject']);
    $message = sanitizeInput($input['message']);
    
    // Save to database (if you have a contact_messages table)
    // For now, we'll just log it
    $logData = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    
    logActivity('contact_form_submitted', json_encode($logData));
    
    // Send email notification (optional)
    $to = 'FarahAbuShaban@gmail.com';
    $emailSubject = "New Contact Form Submission: $subject";
    $emailBody = "
    New contact form submission from Asala Center website:
    
    Name: $name
    Email: $email
    Phone: $phone
    Subject: $subject
    
    Message:
    $message
    
    ---
    Sent from: " . $_SERVER['REMOTE_ADDR'] . "
    User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "
    ";
    
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Uncomment to enable email sending
    // mail($to, $emailSubject, $emailBody, $headers);
    
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully! We will get back to you soon.'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>







