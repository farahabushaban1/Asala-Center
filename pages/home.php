<?php 
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);

include 'includes/header.php'; 
?>

<!-- Hero Carousel Section -->
<section class="hero-carousel-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        
        <!-- Carousel Items -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="hero-slide">
                    <div class="hero-background">
                        <img src="assets/images/background.png" alt="Background Pattern" class="bg-pattern">
                        <img src="assets/images/Group.png" alt="Group Pattern" class="group-pattern">
                    </div>
                    <div class="container">
                        <div class="row align-items-end" style="min-height: 100%;">
                            <div class="col-lg-6">
                                <div class="hero-text">
                                    <h1 class="hero-title"><?= $lang['asala_center'] ?></h1>
                                    <h2 class="hero-subtitle"><?= $lang['for_oriental_embroidery'] ?></h2>
                                    <p class="hero-description">
                                        <?= $lang['hero_description'] ?>
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#footer" class="btn btn-primary"><?= $lang['contact_us'] ?></a>
                                        <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-outline-secondary"><?= $lang['browse_products'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="assets/images/girls.png" alt="Palestinian Girls" class="hero-main-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="hero-slide">
                    <div class="hero-background">
                        <img src="assets/images/background.png" alt="Background Pattern" class="bg-pattern">
                        <img src="assets/images/Group.png" alt="Group Pattern" class="group-pattern">
                    </div>
                    <div class="container">
                        <div class="row align-items-end" style="min-height: 100%;">
                            <div class="col-lg-6">
                                <div class="hero-text">
                                    <h1 class="hero-title"><?= $lang['palestinian_heritage'] ?></h1>
                                    <h2 class="hero-subtitle"><?= $lang['oriental_embroidery'] ?></h2>
                                    <p class="hero-description">
                                        <?= $lang['discover_beauty'] ?>
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="?page=heritage&lang=<?= $currentLang ?>" class="btn btn-primary"><?= $lang['heritage'] ?></a>
                                        <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-outline-secondary"><?= $lang['browse_products'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="assets/images/WhatsApp.png" alt="Palestinian Heritage" class="hero-main-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="hero-slide">
                    <div class="hero-background">
                        <img src="assets/images/background.png" alt="Background Pattern" class="bg-pattern">
                        <img src="assets/images/Group.png" alt="Group Pattern" class="group-pattern">
                    </div>
                    <div class="container">
                        <div class="row align-items-end" style="min-height: 100%;">
                            <div class="col-lg-6">
                                <div class="hero-text">
                                    <h1 class="hero-title"><?= $lang['handcrafted'] ?></h1>
                                    <h2 class="hero-subtitle"><?= $lang['traditional'] ?></h2>
                                    <p class="hero-description">
                                        <?= $lang['start_journey'] ?>
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="?page=about&lang=<?= $currentLang ?>" class="btn btn-primary"><?= $lang['about_us'] ?></a>
                                        <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-outline-secondary"><?= $lang['shop_now'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="assets/images/WhatsApp1.png" alt="Handcrafted Embroidery" class="hero-main-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 4 -->
            <div class="carousel-item">
                <div class="hero-slide">
                    <div class="hero-background">
                        <img src="assets/images/background.png" alt="Background Pattern" class="bg-pattern">
                        <img src="assets/images/Group.png" alt="Group Pattern" class="group-pattern">
                    </div>
                    <div class="container">
                        <div class="row align-items-end" style="min-height: 100%;">
                            <div class="col-lg-6">
                                <div class="hero-text">
                                    <h1 class="hero-title"><?= $lang['authentic'] ?></h1>
                                    <h2 class="hero-subtitle"><?= $lang['quality'] ?></h2>
                                    <p class="hero-description">
                                        <?= $lang['unique_pieces'] ?>
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-primary"><?= $lang['view_all_products'] ?></a>
                                        <a href="#footer" class="btn btn-outline-secondary"><?= $lang['contact_us'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="assets/images/WhatsApp2.png" alt="Authentic Quality" class="hero-main-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
</section>

<!-- Categories Promotional Banner -->
<section class="categories-banner-section">
    <div class="categories-banner-container">
        <div class="categories-banner-content">
            <div class="categories-banner-title">
                <i class="fas fa-tags"></i>
                <span><?= $lang['browse_categories'] ?? 'تصفح الأقسام' ?></span>
            </div>
            <div class="categories-scroll-wrapper">
                <div class="categories-scroll-track">
                    <?php 
                    // Get categories from database
                    try {
                        $db = Database::getInstance();
                        $categoriesStmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
                        $allCategories = $categoriesStmt->fetchAll();
                        
                        // Duplicate categories for seamless loop
                        $categories = array_merge($allCategories, $allCategories, $allCategories);
                    } catch (Exception $e) {
                        $categories = [];
                    }
                    
                    if (!empty($categories)):
                        foreach ($categories as $category): 
                            $categoryName = $currentLang === 'ar' ? (isset($category['name_ar']) && $category['name_ar'] ? $category['name_ar'] : $category['name']) : $category['name'];
                    ?>
                        <a href="?page=product&lang=<?= $currentLang ?>&category=<?= $category['id'] ?>" 
                           class="category-badge">
                            <span class="category-icon">
                                <i class="fas fa-star"></i>
                            </span>
                            <span class="category-name"><?= htmlspecialchars($categoryName) ?></span>
                            <span class="category-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                        </a>
                    <?php 
                        endforeach;
                    else:
                        // Default categories if database is empty
                        $defaultCategories = [
                            ['id' => 1, 'name' => 'Traditional Dresses', 'name_ar' => 'فساتين تقليدية'],
                            ['id' => 2, 'name' => 'Accessories', 'name_ar' => 'إكسسوارات'],
                            ['id' => 3, 'name' => 'Home Decor', 'name_ar' => 'ديكور المنزل'],
                            ['id' => 4, 'name' => 'Gifts', 'name_ar' => 'هدايا'],
                            ['id' => 5, 'name' => 'Special Collections', 'name_ar' => 'مجموعات خاصة'],
                        ];
                        $categories = array_merge($defaultCategories, $defaultCategories, $defaultCategories);
                        foreach ($categories as $category): 
                            $categoryName = $currentLang === 'ar' ? $category['name_ar'] : $category['name'];
                    ?>
                        <a href="?page=product&lang=<?= $currentLang ?>&category=<?= $category['id'] ?>" 
                           class="category-badge">
                            <span class="category-icon">
                                <i class="fas fa-star"></i>
                            </span>
                            <span class="category-name"><?= htmlspecialchars($categoryName) ?></span>
                            <span class="category-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                        </a>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <p class="section-subtitle"><?= $lang['about_us'] ?></p>
            <h2 class="section-title"><?= $lang['our_story'] ?></h2>
        </div>
        
        <!-- About Content with Images -->
        <div class="row align-items-center">
            <!-- Left Side - Text Content -->
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="about-items">
                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="about-text">
                                <p><?= $lang['about_journey'] ?></p>
                            </div>
                        </div>
                        
                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="about-text">
                                <p><?= $lang['about_beginning'] ?></p>
                            </div>
                        </div>
                        
                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="about-text">
                                <p><?= $lang['about_aim'] ?></p>
                            </div>
                        </div>
                        
                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="about-text">
                                <p><?= $lang['about_vision'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                                         <div class="about-button">
                         <a href="?page=about&lang=<?= $currentLang ?>" class="btn btn-primary">
                             <?= $lang['more_about_us'] ?> <i class="fas fa-arrow-right"></i>
                         </a>
                     </div>
                </div>
            </div>
            
            <!-- Right Side - Images -->
            <div class="col-lg-6">
                <div class="about-images">
                    <div class="image-stack">
                        <div class="image-item">
                            <img src="assets/images/about2.png" alt="Palestinian Model 1" class="about-image">
                        </div>
                        <div class="image-item">
                            <img src="assets/images/about1.png" alt="Palestinian Model 2" class="about-image">
                        </div>
                    </div>
                    <div class="decorative-stars">
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-subtitle"><?= $lang['product'] ?></p>
            <h2 class="section-title"><?= $lang['our_distinctive_products'] ?></h2>
        </div>
        
        <div class="row">
            <?php 
            // Get one product from each category (limit to 5 categories)
            try {
                $db = Database::getInstance();
                
                // First, get first 5 categories
                $categoriesStmt = $db->query("SELECT id FROM categories ORDER BY name ASC LIMIT 5");
                $categories = $categoriesStmt->fetchAll();
                
                $featuredProducts = [];
                
                // Get one product from each category
                foreach ($categories as $category) {
                    $productStmt = $db->query("
                    SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.category_id = :category_id
                    ORDER BY p.created_at DESC 
                        LIMIT 1
                    ", ['category_id' => $category['id']]);
                    $product = $productStmt->fetch();
                    
                    if ($product) {
                        $featuredProducts[] = $product;
                    }
                }
            } catch (Exception $e) {
                $featuredProducts = [];
            }
            
            if (empty($featuredProducts)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <?= $lang['no_products_available'] ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-lg-5-cols col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>">
                                                            <?php else: ?>
                                <div style="width: 100%; height: 150px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 10px 10px 0 0;">
                                    <i class="fas fa-image" style="font-size: 2rem; color: #6c757d;"></i>
                                </div>
                            <?php endif; ?>
                            </div>
                            <div class="product-content">
                                <h3 class="product-title"><?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?></h3>
                                <p class="product-description"><?= htmlspecialchars($currentLang === 'ar' ? (isset($product['description_ar']) && $product['description_ar'] ? $product['description_ar'] : $product['description']) : $product['description']) ?></p>
                                <div class="product-price"><?= number_format($product['price']) ?> ₪</div>
                                <div class="product-actions">
                                    <button class="btn btn-primary add-to-cart" 
                                            data-product-id="<?= $product['id'] ?>"
                                            data-product-name="<?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?>"
                                            data-product-price="<?= $product['price'] ?>">
                                        <i class="fas fa-shopping-cart"></i> <?= $lang['add_to_cart'] ?>
                                    </button>
                                    <div class="share-dropdown">
                                        <button class="share-btn" 
                                                onclick="toggleShareMenu(event, <?= $product['id'] ?>, '<?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?>', <?= $product['price'] ?>)"
                                                title="<?= $currentLang === 'ar' ? 'مشاركة المنتج' : 'Share Product' ?>">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                        <div class="share-menu">
                                            <div class="share-link-display">
                                                <div class="share-link-label"><?= $currentLang === 'ar' ? 'رابط المنتج' : 'Product Link' ?></div>
                                                <div class="share-link-input-container">
                                                    <input type="text" class="share-link-input" id="share-link-<?= $product['id'] ?>" readonly value="">
                                                    <button class="share-link-copy-btn" onclick="copyProductLink(<?= $product['id'] ?>, event)" title="<?= $currentLang === 'ar' ? 'نسخ' : 'Copy' ?>">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <a href="#" class="share-option" data-share="whatsapp" onclick="shareProduct(event, 'whatsapp', <?= $product['id'] ?>, '<?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?>', <?= $product['price'] ?>)">
                                                <i class="fab fa-whatsapp"></i> WhatsApp
                                            </a>
                                            <a href="#" class="share-option" data-share="facebook" onclick="shareProduct(event, 'facebook', <?= $product['id'] ?>, '<?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?>', <?= $product['price'] ?>)">
                                                <i class="fab fa-facebook"></i> Facebook
                                            </a>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-danger favorite-btn" 
                                            data-product-id="<?= $product['id'] ?>"
                                            title="<?= $lang['add_to_favorites'] ?? 'Add to Favorites' ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
                 <div class="text-center mt-4">
             <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-primary view-all-btn">
                 <?= $lang['view_all_products'] ?> <i class="fas fa-arrow-right ms-2"></i>
             </a>
         </div>
    </div>
</section>

<!-- Journey Section -->
<section class="journey-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="journey-content">
                    <h2 class="journey-title"><?= $lang['start_journey'] ?></h2>
                    <p class="journey-description">
                        <?= $lang['discover_beauty'] ?>
                    </p>
                                         <div class="journey-buttons">
                         <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-primary">
                             <i class="fas fa-shopping-cart"></i> <?= $lang['shop_now'] ?>
                         </a>
                         <a href="#footer" class="btn btn-outline-light">
                             <i class="fas fa-envelope"></i> <?= $lang['contact_us'] ?>
                         </a>
                     </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="journey-image">
                    <img src="assets/images/WhatsAppee.png" alt="Palestinian Heritage">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Categories Promotional Banner Styles */
.categories-banner-section {
    background: #FFFFFF;
    padding: 3.5rem 0;
    position: relative;
    overflow: hidden;
    margin-top: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.categories-banner-container {
    position: relative;
    z-index: 2;
    max-width: 100%;
    overflow: hidden;
}

.categories-banner-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
}

.categories-banner-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #A51E3F;
    font-size: 1.5rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 0;
}

.categories-banner-title i {
    font-size: 1.8rem;
    color: #A51E3F;
}


.categories-scroll-wrapper {
    width: 100%;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 10%,
        black 90%,
        transparent 100%
    );
    -webkit-mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 10%,
        black 90%,
        transparent 100%
    );
    padding: 1rem 0;
}

.categories-scroll-track {
    display: flex;
    gap: 2rem;
    animation: scroll-horizontal 40s linear infinite;
    width: fit-content;
    padding: 0 4rem;
    will-change: transform;
}

[dir="rtl"] .categories-scroll-track {
    animation: scroll-horizontal-rtl 35s linear infinite;
}

@keyframes scroll-horizontal {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-33.333%);
    }
}

@keyframes scroll-horizontal-rtl {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(33.333%);
    }
}

.category-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(165, 30, 63, 0.08);
    border: 2px solid rgba(165, 30, 63, 0.15);
    border-radius: 50px;
    padding: 1.1rem 2rem;
    color: #A51E3F;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    white-space: nowrap;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: fit-content;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(165, 30, 63, 0.08);
}

.category-badge:hover {
    background: rgba(165, 30, 63, 0.12);
    border-color: rgba(165, 30, 63, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(165, 30, 63, 0.15);
}

.category-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(165, 30, 63, 0.1);
    border-radius: 50%;
    color: #A51E3F;
    font-size: 1.1rem;
    position: relative;
    z-index: 2;
}

.category-name {
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: 0.3px;
    position: relative;
    z-index: 2;
    color: #A51E3F;
}

.category-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: rgba(165, 30, 63, 0.1);
    border-radius: 50%;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    color: #A51E3F;
}

.category-badge:hover .category-icon {
    background: rgba(165, 30, 63, 0.15);
    transform: scale(1.05);
}

.category-badge:hover .category-arrow {
    background: rgba(165, 30, 63, 0.15);
    transform: translateX(4px);
}

[dir="rtl"] .category-badge:hover .category-arrow {
    transform: translateX(-4px);
}

/* Responsive Design for Categories Banner */
@media (max-width: 1200px) {
    .categories-banner-title {
        font-size: 1.4rem;
    }
    
    .category-badge {
        padding: 1rem 1.8rem;
        font-size: 1.05rem;
    }
    
    .category-icon {
        width: 38px;
        height: 38px;
        font-size: 1.05rem;
    }
}

@media (max-width: 992px) {
    .categories-banner-section {
        padding: 3rem 0;
    }
    
    .categories-banner-title {
        font-size: 1.3rem;
    }
    
    .categories-scroll-track {
        gap: 1.8rem;
        padding: 0 3rem;
    }
    
    .category-badge {
        padding: 0.95rem 1.7rem;
        font-size: 1rem;
        gap: 0.9rem;
    }
    
    .category-icon {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .categories-banner-section {
        padding: 2.5rem 0;
    }
    
    .categories-banner-title {
        font-size: 1.2rem;
        gap: 0.7rem;
    }
    
    .categories-banner-title i {
        font-size: 1.6rem;
    }
    
    .categories-scroll-track {
        gap: 1.5rem;
        padding: 0 2.5rem;
        animation-duration: 35s;
    }
    
    [dir="rtl"] .categories-scroll-track {
        animation-duration: 35s;
    }
    
    .category-badge {
        padding: 0.9rem 1.5rem;
        font-size: 0.95rem;
        gap: 0.8rem;
    }
    
    .category-icon {
        width: 34px;
        height: 34px;
        font-size: 0.95rem;
    }
    
    .category-name {
        font-size: 0.95rem;
    }
    
    .category-arrow {
        width: 26px;
        height: 26px;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .categories-banner-section {
        padding: 2rem 0;
    }
    
    .categories-banner-title {
        font-size: 1.1rem;
        gap: 0.6rem;
    }
    
    .categories-banner-title i {
        font-size: 1.4rem;
    }
    
    .categories-scroll-wrapper {
        mask-image: linear-gradient(
            to right,
            transparent 0%,
            black 12%,
            black 88%,
            transparent 100%
        );
        -webkit-mask-image: linear-gradient(
            to right,
            transparent 0%,
            black 12%,
            black 88%,
            transparent 100%
        );
    }
    
    .categories-scroll-track {
        gap: 1.2rem;
        padding: 0 2rem;
        animation-duration: 30s;
    }
    
    [dir="rtl"] .categories-scroll-track {
        animation-duration: 30s;
    }
    
    .category-badge {
        padding: 0.85rem 1.3rem;
        font-size: 0.9rem;
        gap: 0.7rem;
    }
    
    .category-icon {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .category-name {
        font-size: 0.9rem;
    }
    
    .category-arrow {
        width: 24px;
        height: 24px;
        font-size: 0.7rem;
    }
}

@media (max-width: 400px) {
    .categories-banner-title {
        font-size: 1rem;
    }
    
    .category-badge {
        padding: 0.8rem 1.2rem;
        font-size: 0.85rem;
    }
    
    .category-icon {
        width: 30px;
        height: 30px;
        font-size: 0.85rem;
    }
    
    .category-name {
        font-size: 0.85rem;
    }
}

/* Pause animation on hover for better UX */
.categories-scroll-wrapper:hover .categories-scroll-track {
    animation-play-state: paused;
}

/* RTL Support */
[dir="rtl"] .category-arrow i {
    transform: scaleX(-1);
}

[dir="rtl"] .categories-banner-title {
    direction: rtl;
}

/* Hero Carousel Styles */
.hero-carousel-section {
    position: relative;
    overflow: hidden;
}

.hero-slide {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: flex-end;
    background: #A51E3F;
    padding: 40px 0 0 0;
    overflow: hidden;
}

.hero-slide .hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-slide .hero-background .bg-pattern {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.05;
}

.hero-slide .hero-background .group-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.15;
    z-index: 1;
}

.hero-slide .container {
    position: relative;
    z-index: 2;
}

/* Carousel Indicators */
.carousel-indicators {
    bottom: 20px;
    z-index: 15;
    margin-bottom: 0;
    display: flex !important;
    justify-content: center;
    align-items: center;
    gap: 8px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    padding: 0;
    margin-left: 0;
    margin-right: 0;
}

.carousel-indicators button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: transparent !important;
    border: 1px solid #FFFFFF !important;
    margin: 0;
    padding: 0;
    transition: all 0.3s ease;
    opacity: 1 !important;
    box-shadow: none;
    filter: none;
    position: relative;
    cursor: pointer;
    flex-shrink: 0;
    display: block !important;
    visibility: visible !important;
}

.carousel-indicators button::before {
    display: none;
}

.carousel-indicators button.active {
    background-color: #FFFFFF !important;
    border-color: #FFFFFF !important;
    transform: scale(1);
    opacity: 1 !important;
    box-shadow: none;
    filter: none;
    border-width: 1px !important;
}

.carousel-indicators button.active::before {
    display: none;
}

.carousel-indicators button:hover {
    background-color: rgba(255, 255, 255, 0.2) !important;
    border-color: #FFFFFF !important;
    transform: scale(1);
    opacity: 1 !important;
    filter: none;
}

/* Force perfect centering */
.carousel-indicators {
    text-align: center !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: 100% !important;
    max-width: 300px !important;
    margin: 0 auto !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
}

/* Ensure buttons are properly spaced */
.carousel-indicators button {
    margin: 0 5px !important;
    flex: 0 0 auto !important;
}

/* Additional centering fixes */
.carousel-indicators {
    position: absolute !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    right: auto !important;
    top: auto !important;
}

/* Ensure proper positioning within carousel */
.hero-carousel-section {
    position: relative !important;
    overflow: hidden !important;
}

.hero-carousel-section .carousel {
    position: relative !important;
}

/* Final centering fix */
.carousel-indicators {
    bottom: 20px !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 8px !important;
    width: auto !important;
    max-width: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Ensure indicators work on all screen sizes */
@media (min-width: 577px) {
    .carousel-indicators {
        gap: 8px !important;
    }
    
    .carousel-indicators button {
        width: 8px !important;
        height: 8px !important;
    }
}

/* Enhance background pattern */
.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    background: #A51E3F;
}

.hero-background .bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.15;
    z-index: 1;
}

.hero-background .group-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.25;
    z-index: 2;
}

/* Ensure text is above background */
.hero-slide .hero-text {
    position: relative;
    z-index: 10;
    width: 50%;
    padding-right: 1rem;
    padding-top: 0;
    margin-top: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    height: 100%;
    padding-bottom: 6rem;
    margin-bottom: 0;
    align-self: flex-end;
}

/* Arabic language support - move text to right side */
[dir="rtl"] .hero-slide .hero-text {
    padding-right: 0;
    padding-left: 1rem;
    align-self: flex-start;
}

/* Ensure container is properly positioned */
.hero-slide .container {
    position: relative;
    z-index: 10;
    height: 100%;
}

.hero-slide .row {
    height: 100%;
    align-items: center;
}

.hero-slide .hero-image {
    position: absolute;
    z-index: 10;
    width: 350px;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    bottom: -30px;
    right: 0;
    margin: 0;
    padding: 0;
}

/* Arabic language support - move image to left side */
[dir="rtl"] .hero-slide .hero-image {
    right: auto;
    left: 0;
}

/* Fix text visibility issues */
.hero-slide .hero-title,
.hero-slide .hero-subtitle,
.hero-slide .hero-description {
    position: relative;
    z-index: 10;
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
    color: #FFFFFF !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
}

/* Force background to show properly */
.hero-background img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
    display: block !important;
    visibility: visible !important;
}

/* Ensure background images load properly */
.hero-background .bg-pattern,
.hero-background .group-pattern {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Fix carousel transition issues */
.carousel-item {
    transition: transform 0.6s ease-in-out !important;
}

.carousel-item.active {
    z-index: 1;
}

.carousel-item:not(.active) {
    z-index: 0;
}

/* Ensure background is visible on all slides */
.hero-slide {
    background: #A51E3F !important;
}

.hero-background {
    background: #A51E3F !important;
}

/* Force background images to show */
.hero-background img {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: rgba(165, 30, 63, 0.8);
    border: none;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    z-index: 15;
    transition: all 0.3s ease;
}

.carousel-control-prev {
    left: 20px;
}

.carousel-control-next {
    right: 20px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background-color: #A51E3F;
    transform: translateY(-50%) scale(1.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 20px;
    height: 20px;
}

/* Hero Text Styles */
    .hero-slide .hero-text {
        padding: 2rem 0;
        padding-bottom: 4rem;
    }
    
    /* Arabic language support for tablets */
    [dir="rtl"] .hero-slide .hero-text {
        padding-right: 0;
        padding-left: 1rem;
    }

.hero-slide .hero-title {
    color: #FFFFFF !important;
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    display: block !important;
    visibility: visible !important;
    line-height: 1.2;
    white-space: nowrap;
    margin-top: 0;
    padding-top: 0;
    text-align: left;
}

.hero-slide .hero-subtitle {
    color: #FFFFFF !important;
    font-size: 1.3rem;
    font-weight: 600;
    font-style: italic;
    margin-bottom: 1rem;
    opacity: 0.9;
    display: block !important;
    visibility: visible !important;
    line-height: 1.3;
    white-space: nowrap;
}

.hero-slide .hero-description {
    color: #FFFFFF !important;
    font-size: 1.1rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    max-width: 350px;
    opacity: 0.9;
    display: block !important;
    visibility: visible !important;
}

.hero-slide .hero-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: nowrap;
    flex-direction: row;
    margin-top: 1rem;
    justify-content: flex-start;
    align-items: center;
}

.hero-slide .hero-buttons .btn {
    padding: 0.8rem 3rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    min-width: 160px;
    text-align: center;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    width: fit-content;
}

.hero-slide .hero-buttons .btn-primary {
    background: #8a1a35;
    border: none;
    box-shadow: 0 3px 10px rgba(165, 30, 63, 0.3);
}

.hero-slide .hero-buttons .btn-primary:hover {
    background: linear-gradient(135deg, #8a1a35 0%, #A51E3F 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(165, 30, 63, 0.4);
}

.hero-slide .hero-buttons .btn-outline-secondary {
    border: 2px solid #FFFFFF;
    color: #FFFFFF;
    background: transparent;
}

.hero-slide .hero-buttons .btn-outline-secondary:hover {
    background: #A51E3F;
    color: white;
    transform: translateY(-3px);
}

/* Hero Image Styles */
.hero-slide .hero-image {
    text-align: center;
    padding: 2rem 0;
}

.hero-slide .hero-main-image {
    max-width: 90%;
    height: auto;
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .hero-slide .hero-main-image {
        max-width: 75%;
    }
    
    .hero-slide .hero-image {
        width: 250px;
        bottom: -20px;
    }
    
    /* Arabic language support for tablets */
    [dir="rtl"] .hero-slide .hero-image {
        right: auto;
        left: 0;
    }
}

@media (max-width: 576px) {
    .hero-slide .hero-main-image {
        max-width: 65%;
    }
    
    .hero-slide .hero-image {
        width: 200px;
        bottom: -15px;
    }
    
    /* Arabic language support for mobile */
    [dir="rtl"] .hero-slide .hero-image {
        right: auto;
        left: 0;
    }
}

.hero-slide .hero-main-image:hover {
    transform: scale(1.02);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-slide {
        min-height: 450px;
        padding: 30px 0 0 0;
    }
    
    /* إصلاح ترتيب العناصر في الموبايل */
    .hero-slide .row {
        flex-direction: column;
        align-items: center !important;
        justify-content: center;
    }
    
    .hero-slide .col-lg-6 {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
    }
    
    .hero-slide .hero-text {
        width: 100% !important;
        padding: 1.5rem 1rem !important;
        padding-bottom: 2rem !important;
        text-align: center;
        align-self: center !important;
        order: 1;
    }
    
    .hero-slide .hero-image {
        position: relative !important;
        width: 100% !important;
        max-width: 280px !important;
        margin: 0 auto;
        bottom: auto !important;
        right: auto !important;
        left: auto !important;
        order: 2;
        padding: 1rem 0;
    }
    
    .hero-slide .hero-title {
        font-size: 2rem;
        white-space: normal;
        text-align: center;
    }
    
    .hero-slide .hero-subtitle {
        font-size: 1.1rem;
        white-space: normal;
        text-align: center;
    }
    
    .hero-slide .hero-description {
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .hero-slide .hero-buttons {
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .hero-slide .hero-buttons .btn {
        padding: 0.7rem 2rem;
        font-size: 0.85rem;
        min-width: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
    }
    
    .carousel-control-prev {
        left: 10px;
    }
    
    .carousel-control-next {
        right: 10px;
    }
    
    /* RTL Support for Mobile */
    [dir="rtl"] .hero-slide .hero-text {
        text-align: center;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    [dir="rtl"] .hero-slide .hero-image {
        left: auto !important;
        right: auto !important;
    }
    
    [dir="rtl"] .hero-slide .hero-buttons {
        flex-direction: row-reverse;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .hero-slide {
        min-height: 400px;
        padding: 20px 0 0 0;
    }
    
    .hero-slide .hero-text {
        padding: 1rem 0.75rem !important;
        padding-bottom: 1.5rem !important;
    }
    
    .hero-slide .hero-image {
        max-width: 220px !important;
        padding: 0.5rem 0;
    }
    
    .hero-slide .hero-title {
        font-size: 1.6rem;
        white-space: normal;
        text-align: center;
    }
    
    .hero-slide .hero-subtitle {
        font-size: 0.95rem;
        white-space: normal;
        text-align: center;
    }
    
    .hero-slide .hero-description {
        font-size: 0.85rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .carousel-indicators {
        bottom: 15px;
        gap: 4px;
        display: flex !important;
        visibility: visible !important;
    }
    
    .carousel-indicators button {
        width: 8px;
        height: 8px;
        margin: 0;
        padding: 0;
        display: block !important;
        visibility: visible !important;
        background-color: transparent !important;
        border: 1px solid #FFFFFF !important;
        opacity: 1 !important;
    }
    
    .carousel-indicators button.active {
        background-color: #FFFFFF !important;
        border-color: #FFFFFF !important;
        opacity: 1 !important;
        border-width: 1px !important;
    }
    
    .hero-slide .hero-buttons {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        width: 100%;
    }
    
    .hero-slide .hero-buttons .btn {
        padding: 0.6rem 1.5rem;
        font-size: 0.8rem;
        min-width: 160px;
        width: 100%;
        max-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .carousel-indicators button:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-color: #FFFFFF !important;
        opacity: 1 !important;
    }
    
    .carousel-indicators button:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    
    /* RTL Support for Small Mobile */
    [dir="rtl"] .hero-slide .hero-buttons {
        flex-direction: column;
    }
}

/* Ensure indicators are visible and properly styled */
.carousel-indicators {
    pointer-events: auto !important;
    background: transparent !important;
    border: none !important;
    position: absolute !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    right: auto !important;
}

.carousel-indicators button {
    pointer-events: auto !important;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    background-image: none !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    background-size: auto !important;
    float: none !important;
    display: inline-block !important;
}

.carousel-indicators button:volume-range-end {
    outline: none !important;
    box-shadow: none !important;
}

/* Additional styling to ensure proper display */
.carousel-indicators button:before,
.carousel-indicators button:after {
    display: none !important;
}

/* Ensure perfect centering and small size */
.carousel-indicators {
    text-align: center;
    font-size: 0;
    line-height: 0;
    width: 100%;
    max-width: 200px;
    margin: 0 auto;
}

.carousel-indicators button {
    vertical-align: middle;
    text-indent: -9999px;
    overflow: hidden;
    margin-left: 4px !important;
    margin-right: 4px !important;
}

/* Override any Bootstrap default styles */
.carousel-indicators [data-bs-target] {
    background-color: transparent !important;
    border: 1px solid #FFFFFF !important;
    width: 8px !important;
    height: 8px !important;
    border-radius: 50% !important;
    margin: 0 !important;
    padding: 0 !important;
}

.carousel-indicators [data-bs-target].active {
    background-color: #FFFFFF !important;
    border-color: #FFFFFF !important;
}

/* Mobile responsive overrides */
@media (max-width: 576px) {
    .carousel-indicators {
        bottom: 15px;
        gap: 6px;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 6px !important;
        height: 6px !important;
    }
}


/* RTL Support */
[dir="rtl"] .hero-slide .hero-buttons {
    flex-direction: row-reverse;
}

[dir="rtl"] .carousel-control-prev {
    left: auto;
    right: 20px;
}

[dir="rtl"] .carousel-control-next {
    right: auto;
    left: 20px;
}

@media (max-width: 768px) {
    [dir="rtl"] .carousel-control-prev {
        right: 10px;
    }
    
    [dir="rtl"] .carousel-control-next {
        left: 10px;
    }
}

/* 5 Columns Layout for Home Products */
.products-section .row {
    display: flex;
    flex-wrap: wrap;
}

.products-section .col-lg-5-cols {
    flex: 0 0 20%;
    max-width: 20%;
    padding-left: 15px;
    padding-right: 15px;
}

@media (max-width: 1200px) {
    .products-section .col-lg-5-cols {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}

@media (max-width: 768px) {
    .products-section .col-lg-5-cols {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .products-section .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .products-section .col-lg-5-cols,
    .products-section .col-md-4,
    .products-section .col-sm-6 {
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 1.5rem;
    }
    
    .products-section .product-card {
        margin: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .products-section .product-image {
        height: 200px;
        min-height: 200px;
    }
    
    .products-section .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* RTL Support for Product Cards */
    [dir="rtl"] .products-section .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    [dir="rtl"] .products-section .col-lg-5-cols,
    [dir="rtl"] .products-section .col-md-4,
    [dir="rtl"] .products-section .col-sm-6 {
        padding-left: 10px;
        padding-right: 10px;
    }
}

@media (max-width: 576px) {
    .products-section .col-lg-5-cols {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .products-section .row {
        margin-left: -7.5px;
        margin-right: -7.5px;
    }
    
    .products-section .col-lg-5-cols,
    .products-section .col-md-4,
    .products-section .col-sm-6 {
        padding-left: 7.5px;
        padding-right: 7.5px;
        margin-bottom: 1.25rem;
    }
    
    .products-section .product-card {
        width: 100%;
    }
    
    .products-section .product-image {
        height: 180px;
        min-height: 180px;
    }
    
    /* RTL Support for Small Mobile */
    [dir="rtl"] .products-section .row {
        margin-left: -7.5px;
        margin-right: -7.5px;
    }
    
    [dir="rtl"] .products-section .col-lg-5-cols,
    [dir="rtl"] .products-section .col-md-4,
    [dir="rtl"] .products-section .col-sm-6 {
        padding-left: 7.5px;
        padding-right: 7.5px;
    }
}

/* Share menu styles for Home page products section */
.products-section .product-card {
    overflow: visible !important;
    min-height: 450px;
    display: flex !important;
    flex-direction: column !important;
}

.products-section .product-content {
    display: flex !important;
    flex-direction: column !important;
    flex-grow: 1 !important;
    min-height: 0 !important;
    overflow: visible !important;
}

.products-section .product-title {
    flex-shrink: 0 !important;
}

.products-section .product-description {
    /* Essential properties for line-clamp to work */
    display: -webkit-box !important;
    -webkit-box-orient: vertical !important;
    -webkit-line-clamp: 3 !important;
    line-clamp: 3 !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    flex-shrink: 0 !important;
    line-height: 1.5 !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    text-align: left !important;
    height: auto !important;
    /* Remove max-height - let line-clamp handle it */
    max-height: none !important;
}

.products-section .product-price {
    flex-shrink: 0 !important;
}

.products-section .product-actions {
    overflow: visible !important;
    margin-top: auto !important;
    flex-shrink: 0 !important;
    padding-top: 0.5rem !important;
}

.products-section .share-dropdown {
    position: relative;
    z-index: 100;
}

.products-section .share-menu {
    position: absolute;
    bottom: 100%;
    right: 0;
    margin-bottom: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.95);
    transition: all 0.3s ease;
    z-index: 1001;
    padding: 0.75rem 0;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

[dir="rtl"] .products-section .share-menu {
    right: auto;
    left: 0;
}

.products-section .share-dropdown.active .share-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.products-section .share-link-display {
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 0.5rem;
}

.products-section .share-link-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.products-section .share-link-input-container {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.products-section .share-link-input {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 0.8rem;
    background: white;
    color: var(--text-dark);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.products-section .share-link-copy-btn {
    padding: 0.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
}

.products-section .share-link-copy-btn:hover {
    background: #8B1A35;
    transform: scale(1.05);
}

.products-section .share-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--text-dark);
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.products-section .share-option:hover {
    background: #f8f9fa;
    color: var(--primary-color);
}

.products-section .share-option i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

.products-section .share-option[data-share="whatsapp"] i {
    color: #25D366;
}

.products-section .share-option[data-share="facebook"] i {
    color: #1877F2;
}

.products-section .share-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #dee2e6;
    color: #6c757d;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    padding: 0;
    font-size: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.products-section .share-btn:hover {
    background: #f8f9fa;
    color: var(--primary-color);
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<?php include 'includes/footer.php'; ?>

