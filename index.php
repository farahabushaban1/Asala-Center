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

// Get dashboard statistics
try {
    $db = Database::getInstance();
    
    // Total products
    $stmt = $db->query("SELECT COUNT(*) as total FROM products");
    $totalProducts = $stmt->fetch()['total'];
    
    // Total orders
    $stmt = $db->query("SELECT COUNT(*) as total FROM orders");
    $totalOrders = $stmt->fetch()['total'];
    
    // Pending orders
    $stmt = $db->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
    $pendingOrders = $stmt->fetch()['total'];
    
    // Total revenue
    $stmt = $db->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    $totalRevenue = $stmt->fetch()['total'] ?: 0;
    
    // Recent orders
    $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
    $recentOrders = $stmt->fetchAll();
    
    // Low stock products
    $stmt = $db->query("SELECT * FROM products WHERE stock_quantity <= 5 ORDER BY stock_quantity ASC LIMIT 5");
    $lowStockProducts = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Asala Center</title>
    
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
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-success-gradient {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .bg-warning-gradient {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .bg-info-gradient {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
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
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                        <a class="nav-link" href="orders.php">
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
                        <span class="navbar-brand">Dashboard</span>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!
                            </span>
                        </div>
                    </div>
                </nav>
                
                <div class="main-content">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-primary-gradient me-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?= number_format($totalProducts) ?></h3>
                                        <p class="text-muted mb-0">Total Products</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-success-gradient me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?= number_format($totalOrders) ?></h3>
                                        <p class="text-muted mb-0">Total Orders</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-warning-gradient me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?= number_format($pendingOrders) ?></h3>
                                        <p class="text-muted mb-0">Pending Orders</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-info-gradient me-3">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?= number_format($totalRevenue) ?> ₪</h3>
                                        <p class="text-muted mb-0">Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Recent Orders -->
                        <div class="col-lg-8">
                            <div class="content-card">
                                <h5 class="mb-3">
                                    <i class="fas fa-clock me-2"></i>Recent Orders
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentOrders as $order): ?>
                                            <tr>
                                                <td>#<?= $order['id'] ?></td>
                                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                <td><?= number_format($order['total_amount']) ?> ₪</td>
                                                <td>
                                                    <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                                        <?= ucfirst($order['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="orders.php" class="btn btn-primary btn-sm">
                                        View All Orders
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Low Stock Products -->
                        <div class="col-lg-4">
                            <div class="content-card">
                                <h5 class="mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Products
                                </h5>
                                <?php if (empty($lowStockProducts)): ?>
                                    <p class="text-muted">No low stock products</p>
                                <?php else: ?>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                    <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                                        <div class="me-3">
                                            <i class="fas fa-box text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                                            <small class="text-muted">Stock: <?= $product['stock_quantity'] ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="text-center mt-3">
                                    <a href="products.php" class="btn btn-warning btn-sm">
                                        Manage Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

