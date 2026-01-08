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
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $password = $_POST['password'];
                $role = sanitizeInput($_POST['role']);
                
                // Check if username or email already exists
                try {
                    $db = Database::getInstance();
                    $existingUser = $db->fetch("SELECT id FROM users WHERE username = :username OR email = :email", [
                        'username' => $username,
                        'email' => $email
                    ]);
                    
                    if ($existingUser) {
                        $_SESSION['error_message'] = 'Username or email already exists!';
                        header('Location: users.php');
                        exit;
                    }
                    
                    // Hash password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $db->insert('users', [
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashedPassword,
                        'role' => $role,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    $_SESSION['success_message'] = 'User added successfully!';
                    header('Location: users.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error adding user: ' . $e->getMessage();
                    header('Location: users.php');
                    exit;
                }
                break;
                
            case 'update':
                $id = intval($_POST['id']);
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $role = sanitizeInput($_POST['role']);
                $password = $_POST['password'];
                
                try {
                    $db = Database::getInstance();
                    
                    // Check if username or email already exists (excluding current user)
                    $existingUser = $db->fetch("SELECT id FROM users WHERE (username = :username OR email = :email) AND id != :id", [
                        'username' => $username,
                        'email' => $email,
                        'id' => $id
                    ]);
                    
                    if ($existingUser) {
                        $_SESSION['error_message'] = 'Username or email already exists!';
                        header('Location: users.php');
                        exit;
                    }
                    
                    $updateData = [
                        'username' => $username,
                        'email' => $email,
                        'role' => $role
                    ];
                    
                    // Update password only if provided
                    if (!empty($password)) {
                        $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
                    }
                    
                    $db->update('users', $updateData, "id = :id", ['id' => $id]);
                    
                    $_SESSION['success_message'] = 'User updated successfully!';
                    header('Location: users.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error updating user: ' . $e->getMessage();
                    header('Location: users.php');
                    exit;
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                
                try {
                    $db = Database::getInstance();
                    
                    // Don't allow admin to delete themselves
                    if ($id == $_SESSION['admin_id']) {
                        $_SESSION['error_message'] = 'You cannot delete your own account!';
                        header('Location: users.php');
                        exit;
                    }
                    
                    $db->delete('users', "id = :id", ['id' => $id]);
                    
                    $_SESSION['success_message'] = 'User deleted successfully!';
                    header('Location: users.php');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error deleting user: ' . $e->getMessage();
                    header('Location: users.php');
                    exit;
                }
                break;
        }
    }
}

// Get users from database
try {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
    
    // Initialize empty array if no data
    if (!$users) $users = [];
    
} catch (Exception $e) {
    $error = 'Database error: ' . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    
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
        
        .role-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .role-admin { background-color: #dc3545; color: white; }
        .role-user { background-color: #6c757d; color: white; }
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
                        <a class="nav-link" href="categories.php">
                            <i class="fas fa-tags me-2"></i>Categories
                        </a>
                        <a class="nav-link active" href="users.php">
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
                        <span class="navbar-brand">Manage Users</span>
                        <div class="navbar-nav ms-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-2"></i>Add User
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
                    
                    <!-- Users Table -->
                    <div class="content-card">
                        <h5 class="mb-3">
                            <i class="fas fa-users me-2"></i>Users List
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">No users found</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                                                                 <td>#<?= $user['id'] ?? 'N/A' ?></td>
                                                 <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
                                                 <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                                                                                                 <td>
                                                     <span class="role-badge role-<?= $user['role'] ?? 'user' ?>">
                                                         <?= ucfirst($user['role'] ?? 'user') ?>
                                                     </span>
                                                 </td>
                                                 <td><?= $user['created_at'] ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                                                                         <?php if (($user['id'] ?? 0) != ($_SESSION['admin_id'] ?? 0)): ?>
                                                         <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?= $user['id'] ?? 0 ?>, '<?= htmlspecialchars($user['username'] ?? 'N/A') ?>')">
                                                             <i class="fas fa-trash"></i>
                                                         </button>
                                                     <?php endif; ?>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-control" id="edit_role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <form method="POST">
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete User</button>
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
        
        function editUser(user) {
            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_role').value = user.role;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }
        
        function deleteUser(id, username) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteMessage').innerHTML = `Are you sure you want to delete the user "${username}"? This action cannot be undone.`;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }
    </script>
</body>
</html>
