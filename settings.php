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
            case 'update_settings':
                try {
                    $db = Database::getInstance();
                    
                    // Get current settings
                    $currentSettings = $db->fetchAll("SELECT * FROM settings");
                    $settingsMap = [];
                    foreach ($currentSettings as $setting) {
                        $settingsMap[$setting['setting_key']] = $setting['id'];
                    }
                    
                    // Update or insert settings
                    $settingsToUpdate = [
                        'site_name' => sanitizeInput($_POST['site_name']),
                        'site_description' => sanitizeInput($_POST['site_description']),
                        'contact_email' => sanitizeInput($_POST['contact_email']),
                        'contact_phone' => sanitizeInput($_POST['contact_phone']),
                        'contact_address' => sanitizeInput($_POST['contact_address']),
                        'whatsapp_number' => sanitizeInput($_POST['whatsapp_number']),
                        'facebook_url' => sanitizeInput($_POST['facebook_url']),
                        'instagram_url' => sanitizeInput($_POST['instagram_url']),
                        'working_hours' => sanitizeInput($_POST['working_hours']),
                        'currency' => sanitizeInput($_POST['currency']),
                        'currency_symbol' => sanitizeInput($_POST['currency_symbol'])
                    ];
                    
                    foreach ($settingsToUpdate as $key => $value) {
                        if (isset($settingsMap[$key])) {
                            // Update existing setting
                            $db->update('settings', [
                                'setting_value' => $value,
                                'updated_at' => date('Y-m-d H:i:s')
                            ], "id = :id", ['id' => $settingsMap[$key]]);
                        } else {
                            // Insert new setting
                            $db->insert('settings', [
                                'setting_key' => $key,
                                'setting_value' => $value,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                    
                                         $_SESSION['success_message'] = 'Settings updated successfully!';
                    header('Location: settings.php');
                    exit;
                } catch (Exception $e) {
                                         $_SESSION['error_message'] = 'Error updating settings: ' . $e->getMessage();
                    header('Location: settings.php');
                    exit;
                }
                break;
        }
    }
}

// Get current settings from database
try {
    $db = Database::getInstance();
    $settings = $db->fetchAll("SELECT * FROM settings");
    
    // Convert to associative array
    $currentSettings = [];
    foreach ($settings as $setting) {
        $currentSettings[$setting['setting_key']] = $setting['setting_value'];
    }
    
    // Set default values if not exists
    $defaultSettings = [
        'site_name' => 'أصالة سنتر للتطريز الشرقي',
        'site_description' => 'يتخصص أصالة سنتر في التطريز الشرقي والثوب الفلسطيني. نحافظ على التراث ونقدم أجمل القطع المطرزة يدوياً.',
        'contact_email' => 'FarahAbuShaban@gmail.com',
        'contact_phone' => '0592310435',
        'contact_address' => 'Palestine - Gaza',
        'whatsapp_number' => '0592310435',
        'facebook_url' => 'https://facebook.com/asalacenter',
        'instagram_url' => 'https://instagram.com/asalacenter',
        'working_hours' => 'الأحد - الخميس: 9:00 ص - 6:00 م',
        'currency' => 'ILS',
        'currency_symbol' => '₪'
    ];
    
    // Merge current settings with defaults
    $currentSettings = array_merge($defaultSettings, $currentSettings);
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: settings.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin Dashboard</title>
    
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
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #A51E3F;
            box-shadow: 0 0 0 0.2rem rgba(165, 30, 63, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .btn-primary {
            background-color: #A51E3F;
            border-color: #A51E3F;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: #8a1a35;
            border-color: #8a1a35;
        }
        
        .section-title {
            color: #A51E3F;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 0.5rem;
            color: white;
            font-size: 1.2rem;
        }
        
        .facebook { background-color: #1877f2; }
        .instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
        .twitter { background-color: #1da1f2; }
        .youtube { background-color: #ff0000; }
        .tiktok { background-color: #000000; }
        .whatsapp { background-color: #25d366; }
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
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link active" href="settings.php">
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
                                                 <span class="navbar-brand">Site Settings</span>
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
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_settings">
                        
                                                 <!-- General Settings -->
                         <div class="content-card">
                             <h5 class="section-title">
                                 <i class="fas fa-cog me-2"></i>General Settings
                             </h5>
                            <div class="row">
                                                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="site_name" class="form-label">Site Name</label>
                                         <input type="text" class="form-control" id="site_name" name="site_name" 
                                                value="<?= htmlspecialchars($currentSettings['site_name']) ?>" required>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="site_description" class="form-label">Site Description</label>
                                         <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= htmlspecialchars($currentSettings['site_description']) ?></textarea>
                                     </div>
                                 </div>
                            </div>
                            <div class="row">
                                                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="currency" class="form-label">Currency</label>
                                         <input type="text" class="form-control" id="currency" name="currency" 
                                                value="<?= htmlspecialchars($currentSettings['currency']) ?>" required>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                         <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
                                                value="<?= htmlspecialchars($currentSettings['currency_symbol']) ?>" required>
                                     </div>
                                 </div>
                            </div>
                        </div>
                        
                                                 <!-- Contact Information -->
                         <div class="content-card">
                             <h5 class="section-title">
                                 <i class="fas fa-address-book me-2"></i>Contact Information
                             </h5>
                            <div class="row">
                                                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="contact_email" class="form-label">Contact Email</label>
                                         <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                value="<?= htmlspecialchars($currentSettings['contact_email']) ?>" required>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="contact_phone" class="form-label">Contact Phone</label>
                                         <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                value="<?= htmlspecialchars($currentSettings['contact_phone']) ?>" required>
                                     </div>
                                 </div>
                            </div>
                            <div class="row">
                                                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="whatsapp_number" class="form-label">
                                             <i class="fab fa-whatsapp me-2"></i>WhatsApp Number
                                         </label>
                                         <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" 
                                                value="<?= htmlspecialchars($currentSettings['whatsapp_number']) ?>" required>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="working_hours" class="form-label">Working Hours</label>
                                         <input type="text" class="form-control" id="working_hours" name="working_hours" 
                                                value="<?= htmlspecialchars($currentSettings['working_hours']) ?>">
                                     </div>
                                 </div>
                            </div>
                                                         <div class="mb-3">
                                 <label for="contact_address" class="form-label">Contact Address</label>
                                 <textarea class="form-control" id="contact_address" name="contact_address" rows="3"><?= htmlspecialchars($currentSettings['contact_address']) ?></textarea>
                             </div>
                        </div>
                        
                                                 <!-- Social Media -->
                         <div class="content-card">
                             <h5 class="section-title">
                                 <i class="fas fa-share-alt me-2"></i>Social Media Links
                             </h5>
                             <div class="row">
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="facebook_url" class="form-label">
                                             <span class="social-icon facebook"><i class="fab fa-facebook-f"></i></span>Facebook URL
                                         </label>
                                         <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                                value="<?= htmlspecialchars($currentSettings['facebook_url']) ?>">
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="mb-3">
                                         <label for="instagram_url" class="form-label">
                                             <span class="social-icon instagram"><i class="fab fa-instagram"></i></span>Instagram URL
                                         </label>
                                         <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                                value="<?= htmlspecialchars($currentSettings['instagram_url']) ?>">
                                     </div>
                                 </div>
                             </div>
                         </div>
                        
                        <!-- Save Button -->
                        <div class="content-card">
                            <div class="d-flex justify-content-end">
                                                                 <button type="submit" class="btn btn-primary">
                                     <i class="fas fa-save me-2"></i>Save Settings
                                 </button>
                            </div>
                        </div>
                    </form>
                </div>
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
        
        // Auto-format phone numbers (local numbers)
        document.getElementById('contact_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
        
        document.getElementById('whatsapp_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>
