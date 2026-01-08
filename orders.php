<?php
// Include required files
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// Get success message from session
if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Get error message from session
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_status':
                $order_id = intval($_POST['order_id']);
                $status = sanitizeInput($_POST['status']);
                
                try {
                    $db = Database::getInstance();
                    $db->update('orders', [
                        'status' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], "id = :id", ['id' => $order_id]);
                    
                    $_SESSION['success_message'] = 'Order status updated successfully!';
                    header('Location: orders.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error updating order status: ' . $e->getMessage();
                    header('Location: orders.php');
                    exit;
                }
                break;
                
            case 'delete':
                $order_id = intval($_POST['order_id']);
                
                try {
                    $db = Database::getInstance();
                    
                    // Delete order items first
                    $db->delete('order_items', "order_id = :order_id", ['order_id' => $order_id]);
                    
                    // Delete the order
                    $db->delete('orders', "id = :id", ['id' => $order_id]);
                    
                    $_SESSION['success_message'] = 'Order deleted successfully!';
                    header('Location: orders.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error deleting order: ' . $e->getMessage();
                    header('Location: orders.php');
                    exit;
                }
                break;
        }
    }
}

// Get orders from database
try {
    $db = Database::getInstance();
    
    // Get orders with customer details
    $stmt = $db->query("
        SELECT o.*, 
               COUNT(oi.id) as item_count
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $orders = $stmt->fetchAll();
    
    // Initialize empty array if no data
    if (!$orders) $orders = [];
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: orders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="../assets/images/mts.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/images/mts.jpg">
    <link rel="apple-touch-icon" href="../assets/images/mts.jpg">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: #A51E3F;
            min-height: 100vh;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
        }
        
        .sidebar-header {
            text-align: center;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-subtitle {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
        }
        
        .btn-sm {
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
        }
        
        .modal-content {
            border-radius: 15px;
        }
        
        .form-control {
            border-radius: 8px;
        }
        
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #c3e6cb; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="sidebar-header">
                        <div class="sidebar-logo">
                            <img src="../assets/images/logo.png" alt="Asala Center" onerror="this.style.display='none'; this.parentNode.innerHTML='<div style=\'width:100%;height:100%;background:#A51E3F;border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:1.2rem;\'>AC</div>'">
                        </div>
                        <div class="sidebar-title">Admin Panel</div>
                        <div class="sidebar-subtitle">Asala Center</div>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                        <a class="nav-link active" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a class="nav-link" href="categories.php">
                            <i class="fas fa-tags me-2"></i>Categories
                        </a>
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-brand">Manage Orders</span>
                    </div>
                </nav>
                
                <div class="main-content">
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Orders Table -->
                    <div class="content-card">
                        <h5 class="mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>Orders List
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">No orders found</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#<?= $order['id'] ?></td>
                                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                <td><?= htmlspecialchars($order['customer_email']) ?></td>
                                                <td><?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?></td>
                                                <td><?= $order['item_count'] ?? 0 ?> items</td>
                                                <td><?= number_format($order['total_amount'], 2) ?> ₪</td>
                                                <td>
                                                    <?php
                                                    $paymentMethod = 'N/A';
                                                    if (!empty($order['whatsapp_message'])) {
                                                        if (stripos($order['whatsapp_message'], 'Credit Card') !== false) {
                                                            $paymentMethod = '<span class="badge bg-info"><i class="fas fa-credit-card me-1"></i>Credit Card</span>';
                                                        } elseif (stripos($order['whatsapp_message'], 'WhatsApp') !== false) {
                                                            $paymentMethod = '<span class="badge bg-success"><i class="fab fa-whatsapp me-1"></i>WhatsApp</span>';
                                                        }
                                                    }
                                                    echo $paymentMethod;
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="status-badge status-<?= $order['status'] ?>">
                                                        <?= ucfirst($order['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(<?= htmlspecialchars(json_encode($order)) ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" onclick="updateStatus(<?= $order['id'] ?>, '<?= $order['status'] ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteOrder(<?= $order['id'] ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Order Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetails">
                    <!-- Order details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" id="update_order_id">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this order? This action cannot be undone.</p>
                </div>
                <form method="POST">
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="order_id" id="delete_order_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        function viewOrder(order) {
            // Determine payment method
            let paymentMethodHtml = 'N/A';
            if (order.whatsapp_message) {
                if (order.whatsapp_message.includes('Credit Card')) {
                    paymentMethodHtml = '<span class="badge bg-info"><i class="fas fa-credit-card me-1"></i>Credit Card</span>';
                } else if (order.whatsapp_message.includes('WhatsApp')) {
                    paymentMethodHtml = '<span class="badge bg-success"><i class="fab fa-whatsapp me-1"></i>WhatsApp</span>';
                }
            }
            
            // Load order details via AJAX or populate modal
            document.getElementById('orderDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> ${order.customer_name || 'N/A'}</p>
                        <p><strong>Email:</strong> ${order.customer_email || 'N/A'}</p>
                        <p><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</p>
                        <p><strong>Address:</strong> ${order.customer_address || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Order ID:</strong> #${order.id}</p>
                        <p><strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></p>
                        <p><strong>Payment Method:</strong> ${paymentMethodHtml}</p>
                        <p><strong>Total:</strong> ${order.total_amount} ₪</p>
                        <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Order Items</h6>
                        <p class="text-muted">Order items will be loaded here...</p>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('viewOrderModal')).show();
        }
        
        function updateStatus(orderId, currentStatus) {
            document.getElementById('update_order_id').value = orderId;
            document.getElementById('status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
        
        function deleteOrder(orderId) {
            document.getElementById('delete_order_id').value = orderId;
            new bootstrap.Modal(document.getElementById('deleteOrderModal')).show();
        }
    </script>
</body>
</html>
