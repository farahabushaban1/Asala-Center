<?php
// Include required files
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Start session
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(sanitizeInput($_POST['username']));
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password';
    } else {
        try {
            $db = Database::getInstance();
            $user = $db->fetch("SELECT * FROM users WHERE username = :username AND role = 'admin' LIMIT 1", ['username' => $username]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];
                
                // Log activity
                logActivity('Admin login', "Admin {$user['username']} logged in successfully");
                
                // Redirect to prevent form resubmission
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password';
                logActivity('Admin login failed', "Failed login attempt for username: {$username}");
                
                // Clear password from session to prevent resubmission
                unset($_POST['password']);
            }
        } catch (Exception $e) {
            $error = 'System error occurred, please try again';
            logActivity('Admin login error', $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Asala Center</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="../assets/images/mts.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/images/mts.jpg">
    <link rel="apple-touch-icon" href="../assets/images/mts.jpg">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            position: relative;
            border: 1px solid #f1f5f9;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .brand-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .login-header h1 {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #fff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #A51E3F;
            box-shadow: 0 0 0 3px rgba(165, 30, 63, 0.1);
        }
        
        .btn-login {
            width: 100%;
            background: #A51E3F;
            color: white;
            border: none;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.75rem;
        }
        
        .btn-login:hover {
            background: #8a1a35;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(165, 30, 63, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            border: none;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 3px solid #dc2626;
        }
        
        .demo-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .demo-info h6 {
            color: #475569;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .demo-credentials {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #374151;
        }
        
        .demo-credentials div {
            margin-bottom: 0.25rem;
        }
        
        .demo-credentials div:last-child {
            margin-bottom: 0;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }
        
        .back-link a {
            color: #A51E3F;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .back-link a:hover {
            color: #8a1a35;
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 1.5rem;
                margin: 15px;
                max-width: 350px;
            }
            
            .login-header h1 {
                font-size: 1.25rem;
            }
            
            .brand-logo {
                width: 50px;
                height: 50px;
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1.25rem;
                margin: 10px;
                max-width: 300px;
            }
            
            .login-header h1 {
                font-size: 1.1rem;
            }
            
            .brand-logo {
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="brand-logo">
                <img src="../assets/images/logo.png" alt="Asala Center" onerror="this.style.display='none'; this.parentNode.innerHTML='<div style=\'width:100%;height:100%;background:#A51E3F;border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:1.2rem;\'>AC</div>'">
            </div>
            <h1>Admin Login</h1>
            <p>Asala Center Dashboard</p>
        </div>
        
        <!-- Demo Credentials -->
        <div class="demo-info">
            <h6>Demo Credentials</h6>
            <div class="demo-credentials">
                <div><strong>Username:</strong> Admin</div>
                <div><strong>Password:</strong> password</div>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Enter your username" required 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Enter your password" required 
                       autocomplete="new-password">
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>Sign In
            </button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">
                <i class="fas fa-arrow-left" style="margin-right: 0.25rem;"></i>Back to Website
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Clear form on page load to prevent resubmission
        window.addEventListener('load', function() {
            // Clear password field
            document.getElementById('password').value = '';
            
            // Clear any error messages after 5 seconds
            const errorAlert = document.querySelector('.alert-danger');
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 5000);
            }
        });
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please enter username and password');
                return false;
            }
        });
        
        // Auto-fill demo credentials on demo info click
        document.querySelector('.demo-info').addEventListener('click', function() {
            document.getElementById('username').value = 'Admin';
            document.getElementById('password').value = 'password';
        });
        
        // Add loading state to button
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.querySelector('.btn-login');
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Signing In...';
            button.disabled = true;
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
