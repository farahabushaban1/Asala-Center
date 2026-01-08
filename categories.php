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
                $name_ar = sanitizeInput($_POST['name_ar']);
                $description = sanitizeInput($_POST['description']);
                $description_ar = sanitizeInput($_POST['description_ar']);
                $slug = createUniqueSlug($name, 'categories');
                
                try {
                    $db = Database::getInstance();
                    $db->insert('categories', [
                        'name' => $name,
                        'name_ar' => $name_ar,
                        'slug' => $slug,
                        'description' => $description,
                        'description_ar' => $description_ar,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $_SESSION['success_message'] = 'Category added successfully!';
                    header('Location: categories.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error adding category: ' . $e->getMessage();
                    header('Location: categories.php');
                    exit;
                }
                break;
                
            case 'update':
                $id = intval($_POST['id']);
                $name = sanitizeInput($_POST['name']);
                $name_ar = sanitizeInput($_POST['name_ar']);
                $description = sanitizeInput($_POST['description']);
                $description_ar = sanitizeInput($_POST['description_ar']);
                $slug = createUniqueSlug($name, 'categories', $id);
                
                try {
                    $db = Database::getInstance();
                    $db->update('categories', [
                        'name' => $name,
                        'name_ar' => $name_ar,
                        'slug' => $slug,
                        'description' => $description,
                        'description_ar' => $description_ar
                    ], "id = :id", ['id' => $id]);
                    $_SESSION['success_message'] = 'Category updated successfully!';
                    header('Location: categories.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error updating category: ' . $e->getMessage();
                    header('Location: categories.php');
                    exit;
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                
                try {
                    $db = Database::getInstance();
                    
                    // Check if category has products
                    $productCount = $db->fetch("SELECT COUNT(*) as count FROM products WHERE category_id = :id", ['id' => $id]);
                    
                    if ($productCount['count'] > 0) {
                        $_SESSION['error_message'] = 'Cannot delete category: It has ' . $productCount['count'] . ' product(s) associated with it.';
                        header('Location: categories.php');
                        exit;
                    }
                    
                    // Delete the category
                    $db->delete('categories', "id = :id", ['id' => $id]);
                    
                    $_SESSION['success_message'] = 'Category deleted successfully!';
                    header('Location: categories.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error deleting category: ' . $e->getMessage();
                    header('Location: categories.php');
                    exit;
                }
                break;
        }
    }
}

// Get categories from database
try {
    $db = Database::getInstance();
    
    // Get categories with product count
    $stmt = $db->query("
        SELECT c.*, COUNT(p.id) as product_count
        FROM categories c 
        LEFT JOIN products p ON c.id = p.category_id 
        GROUP BY c.id 
        ORDER BY c.name ASC
    ");
    $categories = $stmt->fetchAll();
    
    // Initialize empty array if no data
    if (!$categories) $categories = [];
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: categories.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin Dashboard</title>
    
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
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a class="nav-link active" href="categories.php">
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
                        <span class="navbar-brand">Manage Categories</span>
                        <div class="navbar-nav ms-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-2"></i>Add Category
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
                    
                    <!-- Categories Table -->
                    <div class="content-card">
                        <h5 class="mb-3">
                            <i class="fas fa-tags me-2"></i>Categories List
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name (English)</th>
                                        <th>Name (Arabic)</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Products</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($categories)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">No categories found</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td>#<?= $category['id'] ?></td>
                                                <td><?= htmlspecialchars($category['name']) ?></td>
                                                <td><?= htmlspecialchars($category['name_ar'] ?? '') ?></td>
                                                <td><code><?= htmlspecialchars($category['slug']) ?></code></td>
                                                <td><?= htmlspecialchars($category['description'] ?? 'No description') ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?= $category['product_count'] ?> products</span>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($category['created_at'])) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editCategory(<?= htmlspecialchars(json_encode($category)) ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>', <?= $category['product_count'] ?>)">
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name (English)</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">Category Name (Arabic)</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (English)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description_ar" class="form-label">Description (Arabic)</label>
                            <textarea class="form-control" id="description_ar" name="description_ar" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name (English)</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_name_ar" class="form-label">Category Name (Arabic)</label>
                            <input type="text" class="form-control" id="edit_name_ar" name="name_ar">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description (English)</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description_ar" class="form-label">Description (Arabic)</label>
                            <textarea class="form-control" id="edit_description_ar" name="description_ar" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Are you sure you want to delete this category? This action cannot be undone.</p>
                </div>
                <form method="POST">
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Category</button>
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
        
        function editCategory(category) {
            document.getElementById('edit_id').value = category.id;
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_name_ar').value = category.name_ar || '';
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('edit_description_ar').value = category.description_ar || '';
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
        
        function deleteCategory(id, name, productCount) {
            document.getElementById('delete_id').value = id;
            
            let message = `Are you sure you want to delete the category "${name}"?`;
            if (productCount > 0) {
                message += `\n\nThis category has ${productCount} product(s) associated with it. Deleting it will affect those products.`;
            }
            message += '\n\nThis action cannot be undone.';
            
            document.getElementById('deleteMessage').innerHTML = message.replace(/\n/g, '<br>');
            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        }
    </script>
</body>
</html>
