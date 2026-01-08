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
            case 'add':
                $name = sanitizeInput($_POST['name']);
                $name_ar = sanitizeInput($_POST['name_ar'] ?? '');
                $description = sanitizeInput($_POST['description'] ?? '');
                $description_ar = sanitizeInput($_POST['description_ar'] ?? '');
                $price = floatval($_POST['price']);
                $stock_quantity = intval($_POST['stock_quantity']);
                // Fix: category_id should be NULL if empty, not 0
                $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
                
                // Generate unique slug from English name
                $slug = createUniqueSlug($name);
                
                // Handle image upload
                $image = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/products/';
                    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $fileName = 'product_' . time() . '_' . uniqid() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $fileName;
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                            // Resize image to optimal size (800x600 max)
                            $resizedPath = $uploadPath;
                            if (resizeImage($uploadPath, $resizedPath, 800, 600, 85)) {
                                $image = $fileName;
                            } else {
                                // If resize fails, use original
                                $image = $fileName;
                            }
                        }
                    }
                }
                
                try {
                    $db = Database::getInstance();
                    
                    // First, check which columns actually exist in the products table
                    $columnsStmt = $db->query("DESCRIBE products");
                    $existingColumns = [];
                    while ($row = $columnsStmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingColumns[] = $row['Field'];
                    }
                    
                    // Build insert data array with only existing columns
                    // Note: Do NOT include 'id' - it's AUTO_INCREMENT
                    // Note: 'created_at' will be set automatically if column has DEFAULT CURRENT_TIMESTAMP
                    $insertData = [];
                    
                    // Always include these required fields (they should exist)
                    if (in_array('name', $existingColumns)) {
                        $insertData['name'] = $name;
                    }
                    if (in_array('slug', $existingColumns)) {
                        $insertData['slug'] = $slug;
                    }
                    if (in_array('description', $existingColumns)) {
                        $insertData['description'] = $description;
                    }
                    if (in_array('price', $existingColumns)) {
                        $insertData['price'] = $price;
                    }
                    if (in_array('stock_quantity', $existingColumns)) {
                        $insertData['stock_quantity'] = $stock_quantity;
                    }
                    if (in_array('category_id', $existingColumns)) {
                        $insertData['category_id'] = $category_id; // Can be NULL
                    }
                    
                    // Add optional fields only if they exist in the table
                    if (in_array('name_ar', $existingColumns) && !empty($name_ar)) {
                        $insertData['name_ar'] = $name_ar;
                    }
                    if (in_array('description_ar', $existingColumns) && !empty($description_ar)) {
                        $insertData['description_ar'] = $description_ar;
                    }
                    if (in_array('image', $existingColumns) && !empty($image)) {
                        $insertData['image'] = $image;
                    }
                    
                    // Make sure we're not including 'id'
                    if (isset($insertData['id'])) {
                        unset($insertData['id']);
                    }
                    
                    $db->insert('products', $insertData);
                    $_SESSION['success_message'] = 'Product added successfully!';
                    header('Location: products.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error adding product: ' . $e->getMessage();
                    header('Location: products.php');
                    exit;
                }
                break;
                
            case 'update':
                $id = intval($_POST['id']);
                $name = sanitizeInput($_POST['name']);
                $name_ar = sanitizeInput($_POST['name_ar'] ?? '');
                $description = sanitizeInput($_POST['description'] ?? '');
                $description_ar = sanitizeInput($_POST['description_ar'] ?? '');
                $price = floatval($_POST['price']);
                $stock_quantity = intval($_POST['stock_quantity']);
                // Fix: category_id should be NULL if empty, not 0
                $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
                
                // Generate unique slug from English name
                $slug = createUniqueSlug($name, 'products', $id);
                
                // Handle image upload
                $image = $_POST['current_image'] ?? '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/products/';
                    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $fileName = 'product_' . time() . '_' . uniqid() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $fileName;
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                            // Delete old image if exists
                            if (!empty($_POST['current_image'])) {
                                $oldImagePath = $uploadDir . $_POST['current_image'];
                                if (file_exists($oldImagePath)) {
                                    unlink($oldImagePath);
                                }
                            }
                            
                            // Resize image to optimal size (800x600 max)
                            $resizedPath = $uploadPath;
                            if (resizeImage($uploadPath, $resizedPath, 800, 600, 85)) {
                                $image = $fileName;
                            } else {
                                // If resize fails, use original
                                $image = $fileName;
                            }
                        }
                    }
                }
                
                try {
                    $db = Database::getInstance();
                    
                    // Build update data array
                    $updateData = [
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $description,
                        'price' => $price,
                        'stock_quantity' => $stock_quantity,
                        'category_id' => $category_id
                    ];
                    
                    // Only add these fields if they have values or are being updated
                    if (!empty($name_ar) || isset($_POST['name_ar'])) {
                        $updateData['name_ar'] = $name_ar;
                    }
                    if (!empty($description_ar) || isset($_POST['description_ar'])) {
                        $updateData['description_ar'] = $description_ar;
                    }
                    if (!empty($image) || isset($_POST['current_image'])) {
                        $updateData['image'] = $image;
                    }
                    
                    $db->update('products', $updateData, "id = :id", ['id' => $id]);
                    $_SESSION['success_message'] = 'Product updated successfully!';
                    header('Location: products.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error updating product: ' . $e->getMessage();
                    header('Location: products.php');
                    exit;
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                try {
                    $db = Database::getInstance();
                    
                    // Get product image before deletion
                    $product = $db->fetch("SELECT image FROM products WHERE id = :id", ['id' => $id]);
                    
                    // Delete the product
                    $db->delete('products', "id = :id", ['id' => $id]);
                    
                    // Delete the image file if exists
                    if ($product && !empty($product['image'])) {
                        $imagePath = '../assets/images/products/' . $product['image'];
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    
                    $_SESSION['success_message'] = 'Product deleted successfully!';
                    header('Location: products.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error deleting product: ' . $e->getMessage();
                    header('Location: products.php');
                    exit;
                }
                break;
        }
    }
}

// Get products and categories
try {
    $db = Database::getInstance();
    
    // Get products with category names
    // Order by id DESC to ensure all products are shown (most recent first)
    $stmt = $db->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for dropdown
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Initialize empty arrays if no data
    if (!$products) $products = [];
    if (!$categories) $categories = [];
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Dashboard</title>
    
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
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: transform 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.1);
            border-color: #A51E3F;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            margin-top: 10px;
        }
        
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .image-upload-area:hover {
            border-color: #A51E3F;
            background: #fff;
        }
        
        .image-upload-area.dragover {
            border-color: #A51E3F;
            background: #fff5f5;
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
                        <a class="nav-link active" href="products.php">
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
                        <span class="navbar-brand">Manage Products</span>
                        <div class="navbar-nav ms-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </button>
                        </div>
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
                    
                    <!-- Products Table -->
                    <div class="content-card">
                        <h5 class="mb-3">
                            <i class="fas fa-box me-2"></i>Products List
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                                                 <thead>
                                     <tr>
                                         <th>ID</th>
                                         <th>Image</th>
                                         <th>Name (EN)</th>
                                         <th>Name (AR)</th>
                                         <th>Category</th>
                                         <th>Price</th>
                                         <th>Stock</th>
                                         <th>Created</th>
                                         <th>Actions</th>
                                     </tr>
                                 </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                                                         <tr>
                                         <td>#<?= $product['id'] ?></td>
                                         <td>
                                             <?php if (!empty($product['image'])): ?>
                                                 <img src="../assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                                                      alt="<?= htmlspecialchars($product['name']) ?>" 
                                                      class="product-image">
                                             <?php else: ?>
                                                 <div style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d; border: 2px solid #e9ecef;">
                                                     <i class="fas fa-image fa-2x"></i>
                                                 </div>
                                             <?php endif; ?>
                                         </td>
                                         <td><?= htmlspecialchars($product['name']) ?></td>
                                         <td><?= htmlspecialchars($product['name_ar'] ?? '') ?></td>
                                         <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                         <td><?= number_format($product['price']) ?> ₪</td>
                                         <td>
                                             <span class="badge bg-<?= $product['stock_quantity'] <= 5 ? 'danger' : 'success' ?>">
                                                 <?= $product['stock_quantity'] ?>
                                             </span>
                                         </td>
                                         <td><?= date('M d, Y', strtotime($product['created_at'])) ?></td>
                                         <td>
                                             <button class="btn btn-sm btn-outline-primary" onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)">
                                                 <i class="fas fa-edit"></i>
                                             </button>
                                             <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?= $product['id'] ?>)">
                                                 <i class="fas fa-trash"></i>
                                             </button>
                                         </td>
                                     </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name (English)</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_ar" class="form-label">اسم المنتج (العربية)</label>
                                    <input type="text" class="form-control" id="name_ar" name="name_ar" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description (English)</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description_ar" class="form-label">الوصف (العربية)</label>
                                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <div class="image-upload-area">
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="form-text text-muted">Supported formats: JPG, JPEG, PNG, GIF, WEBP. Drag & drop supported.</small>
                            </div>
                            <div id="image_preview"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₪)</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="hidden" name="current_image" id="edit_current_image">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Product Name (English)</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name_ar" class="form-label">اسم المنتج (العربية)</label>
                                    <input type="text" class="form-control" id="edit_name_ar" name="name_ar" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description (English)</label>
                                    <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_description_ar" class="form-label">الوصف (العربية)</label>
                                    <textarea class="form-control" id="edit_description_ar" name="description_ar" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Product Image</label>
                            <div id="edit_current_image_display" class="mb-2"></div>
                            <div class="image-upload-area">
                                <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                                <small class="form-text text-muted">Leave empty to keep current image. Supported formats: JPG, JPEG, PNG, GIF, WEBP. Drag & drop supported.</small>
                            </div>
                            <div id="edit_image_preview"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price (₪)</label>
                                    <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-control" id="edit_category_id" name="category_id">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                </div>
                <form method="POST">
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Product</button>
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
        
        function editProduct(product) {
             document.getElementById('edit_id').value = product.id;
             document.getElementById('edit_name').value = product.name;
             document.getElementById('edit_name_ar').value = product.name_ar || '';
             document.getElementById('edit_description').value = product.description;
             document.getElementById('edit_description_ar').value = product.description_ar || '';
             document.getElementById('edit_price').value = product.price;
             document.getElementById('edit_stock_quantity').value = product.stock_quantity;
             document.getElementById('edit_category_id').value = product.category_id;
             document.getElementById('edit_current_image').value = product.image || '';
             
             // Display current image if exists
             const imageDisplay = document.getElementById('edit_current_image_display');
             if (product.image) {
                 imageDisplay.innerHTML = `<img src="../assets/images/products/${product.image}" alt="Current Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">`;
             } else {
                 imageDisplay.innerHTML = '<div style="width: 100px; height: 100px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #6c757d;"><i class="fas fa-image"></i></div>';
             }
             
             new bootstrap.Modal(document.getElementById('editProductModal')).show();
         }
        
        function deleteProduct(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
        }
        
        // Image upload preview functionality
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input && preview) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="image-preview">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.innerHTML = '<div class="alert alert-danger">Please select a valid image file.</div>';
                        }
                    } else {
                        preview.innerHTML = '';
                    }
                });
            }
        }
        
        // Initialize image previews when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupImagePreview('image', 'image_preview');
            setupImagePreview('edit_image', 'edit_image_preview');
        });
        
        // Drag and drop functionality
        function setupDragAndDrop(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input && preview) {
                const dropZone = input.parentElement;
                
                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropZone.classList.add('dragover');
                });
                
                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('dragover');
                });
                
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('dragover');
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        if (file.type.startsWith('image/')) {
                            input.files = files;
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="image-preview">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.innerHTML = '<div class="alert alert-danger">Please select a valid image file.</div>';
                        }
                    }
                });
            }
        }
        
        // Initialize drag and drop
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop('image', 'image_preview');
            setupDragAndDrop('edit_image', 'edit_image_preview');
        });
    </script>
</body>
</html>
