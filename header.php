<?php
$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>" dir="<?= $currentLang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getSetting('site_name', 'Asala Center') ?> - <?= getSetting('site_description', 'For oriental embroidery') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="assets/images/mts.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="assets/images/mts.jpg">
    <link rel="apple-touch-icon" href="assets/images/mts.jpg">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Product Images Fix CSS -->
    <link rel="stylesheet" href="assets/css/product-images-fix.css">
    <?php if ($currentLang === 'ar'): ?>
    <!-- RTL CSS for Arabic -->
    <link rel="stylesheet" href="assets/css/rtl.css">
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="./index.php?lang=<?= $currentLang ?>">
                    <div class="logo">
                        <img src="assets/images/logo.png" alt="Asala Center Logo" class="logo-img">
                    </div>
                </a>

                <!-- Mobile Right Side (Cart & Language) - Visible on mobile only -->
                <div class="mobile-top-actions d-lg-none">
                    <!-- Language Selector -->
                    <div class="language-selector">
                        <?php if ($currentLang === 'en'): ?>
                            <a href="./index.php?lang=ar" class="nav-link">Ar</a>
                        <?php else: ?>
                            <a href="./index.php?lang=en" class="nav-link">En</a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Shopping Cart -->
                    <a class="nav-link cart-icon" href="./index.php?page=cart&lang=<?= $currentLang ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                </div>

                <!-- Mobile Menu Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav mx-auto">
                        <a class="nav-link <?= isActivePage('home') ? 'active' : '' ?>" href="./index.php?lang=<?= $currentLang ?>"><?= $lang['home'] ?></a>
                        <a class="nav-link <?= isActivePage('about') ? 'active' : '' ?>" href="./index.php?page=about&lang=<?= $currentLang ?>"><?= $lang['about'] ?></a>
                        <a class="nav-link <?= isActivePage('heritage') ? 'active' : '' ?>" href="./index.php?page=heritage&lang=<?= $currentLang ?>"><?= $lang['heritage'] ?></a>
                        <a class="nav-link <?= isActivePage('product') ? 'active' : '' ?>" href="./index.php?page=product&lang=<?= $currentLang ?>"><?= $lang['products'] ?></a>
                        <a class="nav-link" href="#footer"><?= $lang['contact'] ?></a>
                    </div>

                    <!-- Desktop Right Side -->
                    <div class="navbar-nav ms-auto desktop-nav-right d-none d-lg-flex">
                        <!-- Language Selector -->
                        <div class="language-selector">
                            <?php if ($currentLang === 'en'): ?>
                                <a href="./index.php?lang=ar" class="nav-link">Ar</a>
                            <?php else: ?>
                                <a href="./index.php?lang=en" class="nav-link">En</a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Shopping Cart -->
                        <a class="nav-link cart-icon" href="./index.php?page=cart&lang=<?= $currentLang ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>


