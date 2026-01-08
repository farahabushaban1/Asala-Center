<?php 
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);

// Get selected category from URL parameter
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get categories from database
try {
    $db = Database::getInstance();
    $categoriesStmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $categoriesStmt->fetchAll();
} catch (Exception $e) {
    $categories = [];
}

// Get products from database with category filter
try {
    $db = Database::getInstance();
    if ($selectedCategory > 0) {
        $stmt = $db->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = $selectedCategory
            ORDER BY p.created_at DESC
        ");
    } else {
        $stmt = $db->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC
        ");
    }
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

include 'includes/header.php'; 
?>

<div class="container py-5" style="margin-top: 100px;">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h6 class="section-subtitle"><?= $lang['product'] ?></h6>
            <h2 class="section-title"><?= $lang['our_distinctive_products'] ?></h2>
        </div>
    </div>
    
    <!-- Categories Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="categories-filter">
                <a href="?page=product&lang=<?= $currentLang ?>" 
                   class="category-btn <?= $selectedCategory == 0 ? 'active' : '' ?>">
                    <?= $lang['all_products'] ?? 'All Products' ?>
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="?page=product&lang=<?= $currentLang ?>&category=<?= $category['id'] ?>" 
                       class="category-btn <?= $selectedCategory == $category['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($currentLang === 'ar' ? (isset($category['name_ar']) && $category['name_ar'] ? $category['name_ar'] : $category['name']) : $category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($products)): ?>
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= $lang['no_products_available'] ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product['image'])): ?>
                                <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 10px 10px 0 0;">
                                    <i class="fas fa-image" style="font-size: 3rem; color: #6c757d;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-content">
                            <h5 class="product-title"><?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?></h5>
                            <p class="product-description"><?= htmlspecialchars($currentLang === 'ar' ? (isset($product['description_ar']) && $product['description_ar'] ? $product['description_ar'] : $product['description']) : $product['description']) ?></p>
                            <div class="product-price"><?= number_format($product['price']) ?> ₪</div>
                            <div class="product-actions">
                                <button class="btn btn-primary add-to-cart" 
                                        data-product-id="<?= $product['id'] ?>"
                                        data-product-name="<?= htmlspecialchars($currentLang === 'ar' ? (isset($product['name_ar']) && $product['name_ar'] ? $product['name_ar'] : $product['name']) : $product['name']) ?>"
                                        data-product-price="<?= $product['price'] ?>">
                                    <i class="fas fa-shopping-cart me-2"></i><?= $lang['add_to_cart'] ?>
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
                                <div class="comments-dropdown">
                                    <button class="comments-btn" 
                                            onclick="toggleCommentsMenu(event, <?= $product['id'] ?>)"
                                            title="<?= $currentLang === 'ar' ? 'التعليقات' : 'Comments' ?>">
                                        <i class="fas fa-comments"></i>
                                        <span class="comment-count" id="comment-count-<?= $product['id'] ?>" style="display: none;">0</span>
                                    </button>
                                    <div class="comments-menu" id="comments-menu-<?= $product['id'] ?>">
                                        <div class="comments-menu-header">
                                            <h6><?= $currentLang === 'ar' ? 'التعليقات' : 'Comments' ?></h6>
                                        </div>
                                        <div class="comments-list" id="comments-list-<?= $product['id'] ?>">
                                            <!-- Comments will be loaded here via AJAX -->
                                        </div>
                                        <div class="add-comment-form">
                                            <form id="comment-form-<?= $product['id'] ?>" onsubmit="submitComment(event, <?= $product['id'] ?>)">
                                                <div class="mb-2">
                                                    <input type="text" class="form-control" name="name" placeholder="<?= $currentLang === 'ar' ? 'الاسم' : 'Your Name' ?>" required>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="email" class="form-control" name="email" placeholder="<?= $currentLang === 'ar' ? 'البريد الإلكتروني' : 'Your Email' ?>" required>
                                                </div>
                                                <div class="mb-2">
                                                    <textarea class="form-control" name="comment" rows="3" placeholder="<?= $currentLang === 'ar' ? 'اكتب تعليقك...' : 'Write your comment...' ?>" required></textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="rating-label"><?= $currentLang === 'ar' ? 'التقييم' : 'Rating' ?>:</label>
                                                    <div class="rating-stars">
                                                        <input type="radio" name="rating" value="5" id="rating-5-<?= $product['id'] ?>">
                                                        <label for="rating-5-<?= $product['id'] ?>"><i class="fas fa-star"></i></label>
                                                        <input type="radio" name="rating" value="4" id="rating-4-<?= $product['id'] ?>">
                                                        <label for="rating-4-<?= $product['id'] ?>"><i class="fas fa-star"></i></label>
                                                        <input type="radio" name="rating" value="3" id="rating-3-<?= $product['id'] ?>">
                                                        <label for="rating-3-<?= $product['id'] ?>"><i class="fas fa-star"></i></label>
                                                        <input type="radio" name="rating" value="2" id="rating-2-<?= $product['id'] ?>">
                                                        <label for="rating-2-<?= $product['id'] ?>"><i class="fas fa-star"></i></label>
                                                        <input type="radio" name="rating" value="1" id="rating-1-<?= $product['id'] ?>">
                                                        <label for="rating-1-<?= $product['id'] ?>"><i class="fas fa-star"></i></label>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-paper-plane me-1"></i><?= $currentLang === 'ar' ? 'إرسال' : 'Submit' ?>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Share and Comments Dropdown - Fix for product page */
.product-card {
    overflow: visible !important;
}

.product-content {
    overflow: visible !important;
}

.product-actions {
    overflow: visible !important;
    position: relative;
    z-index: 10;
}

.product-card .share-dropdown,
.product-card .comments-dropdown {
    position: relative;
    z-index: 100;
}

.product-card .share-menu,
.product-card .comments-menu {
    position: absolute;
    bottom: 100%;
    right: 0;
    margin-bottom: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    min-width: 280px;
    max-width: 320px;
    max-height: 500px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.95);
    transition: all 0.3s ease;
    z-index: 1001;
    padding: 0;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

[dir="rtl"] .product-card .share-menu,
[dir="rtl"] .product-card .comments-menu {
    right: auto;
    left: 0;
}

.product-card .share-dropdown.active .share-menu,
.product-card .comments-dropdown.active .comments-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

/* Comments Dropdown Styles */
.comments-dropdown {
    position: relative;
}

.comments-btn {
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
    position: relative;
}

.comments-btn:hover {
    background: #f8f9fa;
    color: #6c757d;
    border-color: #adb5bd;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.comments-btn .comment-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: none;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    min-width: 18px;
}

.comments-header h6 {
    margin-bottom: 1rem;
    color: var(--text-dark);
    font-weight: 600;
}

.comments-menu .comments-list {
    max-height: 200px;
    overflow-y: auto;
    padding: 0.75rem;
}

.comment-item {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.75rem;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.comment-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.9rem;
}

.comment-date {
    font-size: 0.75rem;
    color: #6c757d;
}

.comment-rating {
    color: #ffc107;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.comment-text {
    color: var(--text-dark);
    font-size: 0.85rem;
    line-height: 1.5;
}

.comments-menu .add-comment-form {
    background: #ffffff;
    padding: 0.75rem;
    border-top: 1px solid #e9ecef;
}

.add-comment-form .form-control {
    font-size: 0.85rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
}

.add-comment-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(165, 30, 63, 0.25);
}

.rating-label {
    font-size: 0.85rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: block;
}

.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 2px;
}

.rating-stars input[type="radio"] {
    display: none;
}

.rating-stars label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.2rem;
    transition: color 0.2s;
}

.rating-stars input[type="radio"]:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}

.comments-loading {
    text-align: center;
    padding: 1rem;
    color: #6c757d;
    font-size: 0.85rem;
}

.no-comments {
    text-align: center;
    padding: 1rem;
    color: #6c757d;
    font-size: 0.85rem;
    font-style: italic;
}

/* Scrollbar styling for comments list */
.comments-list::-webkit-scrollbar {
    width: 6px;
}

.comments-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.comments-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.comments-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<script>
// Function to toggle comments menu (dropdown) - replaces old toggleComments
function toggleCommentsMenu(event, productId) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropdown = event.target.closest('.comments-dropdown');
    const menu = document.getElementById('comments-menu-' + productId);
    
    // Close all other comment menus and share menus
    document.querySelectorAll('.comments-dropdown.active, .share-dropdown.active').forEach(activeDropdown => {
        if (activeDropdown !== dropdown) {
            activeDropdown.classList.remove('active');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('active');
    
    // Load comments if menu is being opened
    if (dropdown.classList.contains('active')) {
        loadComments(productId);
    }
}

// Close comments and share menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.comments-dropdown') && !event.target.closest('.share-dropdown')) {
        document.querySelectorAll('.comments-dropdown.active, .share-dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
});

// Function to load comments for a product
function loadComments(productId) {
    const commentsList = document.getElementById('comments-list-' + productId);
    commentsList.innerHTML = '<div class="comments-loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    fetch(`api/get_comments.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(productId, data.comments);
                updateCommentCount(productId, data.comments.length);
            } else {
                commentsList.innerHTML = '<div class="no-comments">No comments yet.</div>';
                updateCommentCount(productId, 0);
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            commentsList.innerHTML = '<div class="no-comments">Error loading comments.</div>';
            updateCommentCount(productId, 0);
        });
}

// Function to display comments
function displayComments(productId, comments) {
    const commentsList = document.getElementById('comments-list-' + productId);
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<div class="no-comments">No comments yet. Be the first to comment!</div>';
        return;
    }
    
    let html = '';
    comments.forEach(comment => {
        const date = new Date(comment.created_at);
        const formattedDate = date.toLocaleDateString();
        
        let ratingStars = '';
        if (comment.rating) {
            ratingStars = '<div class="comment-rating">' + 
                '★'.repeat(comment.rating) + '☆'.repeat(5 - comment.rating) + 
                '</div>';
        }
        
        html += `
            <div class="comment-item">
                <div class="comment-header">
                    <span class="comment-name">${escapeHtml(comment.name)}</span>
                    <span class="comment-date">${formattedDate}</span>
                </div>
                ${ratingStars}
                <div class="comment-text">${escapeHtml(comment.comment)}</div>
            </div>
        `;
    });
    
    commentsList.innerHTML = html;
}

// Function to update comment count
function updateCommentCount(productId, count) {
    const countElement = document.getElementById('comment-count-' + productId);
    if (countElement) {
        countElement.textContent = count;
        // Only show count if there are comments (count > 0)
        if (count > 0) {
            countElement.style.display = 'flex';
        } else {
            countElement.style.display = 'none';
        }
    }
}

// Function to submit comment
function submitComment(event, productId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('product_id', productId);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= $currentLang === "ar" ? "جاري الإرسال..." : "Submitting..." ?>';
    
    fetch('api/add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.reset();
            loadComments(productId);
            showNotification('<?= $currentLang === "ar" ? "تم إضافة التعليق بنجاح" : "Comment added successfully" ?>', 'success');
        } else {
            showNotification(data.message || 'Error adding comment', 'error');
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        showNotification('Error submitting comment', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load comment counts for all products when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Get all product IDs from comment count elements
    const commentCountElements = document.querySelectorAll('[id^="comment-count-"]');
    commentCountElements.forEach(element => {
        const productId = element.id.replace('comment-count-', '');
        // Load comment count for this product
        fetch(`api/get_comments.php?product_id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.comments && data.comments.length > 0) {
                    // Only update if there are comments
                    updateCommentCount(parseInt(productId), data.comments.length);
                } else {
                    // Hide count if no comments
                    updateCommentCount(parseInt(productId), 0);
                }
            })
            .catch(error => {
                console.error('Error loading comment count:', error);
                updateCommentCount(parseInt(productId), 0);
            });
    });
});
</script>

<?php include 'includes/footer.php'; ?>

