<?php 
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);

// Get products from database for cart
try {
    $db = Database::getInstance();
    $stmt = $db->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC
    ");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

include 'includes/header.php'; 
?>

<style>
/* Cart Page General Styling */
.cart-page {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.cart-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin: 2rem 0;
    border: 1px solid #e9ecef;
}

.cart-header {
    background: #A51E3F;
    color: white;
    padding: 2rem;
    text-align: center;
}

.cart-header .section-subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 1rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.cart-header .section-title {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

/* Cart Items Styling */
.cart-items {
    padding: 2rem;
    background: white;
    min-height: 300px;
}

.cart-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.cart-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    border-color: #A51E3F;
}

.cart-item img {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.cart-item:hover img {
    transform: scale(1.05);
}

.quantity-controls {
    background: #f8f9fa;
    border-radius: 25px;
    padding: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.quantity-controls button {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #A51E3F;
    background: white;
    color: #A51E3F;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.quantity-controls button:hover {
    background: #A51E3F;
    color: white;
    transform: scale(1.1);
}

.quantity-controls span {
    font-weight: 600;
    font-size: 1.1rem;
    color: #A51E3F;
    min-width: 30px;
    text-align: center;
}

/* Cart Summary Styling */
.cart-summary {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    position: sticky;
    top: 120px;
    margin-bottom: 2rem;
}

.cart-summary h4 {
    color: #A51E3F;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #A51E3F;
    font-size: 1.3rem;
    text-align: center;
}

.summary-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 1.1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-item:last-child {
    border-bottom: none;
    font-weight: 700;
    font-size: 1.2rem;
    color: #A51E3F;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #A51E3F;
}

/* Payment Options Styling */
.payment-method-selection {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    margin-bottom: 2rem;
}

.payment-method-selection h5 {
    color: #A51E3F;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
}

.payment-options .form-check {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 0.5rem;
}

.payment-options .form-check:hover {
    border-color: #A51E3F;
    box-shadow: 0 2px 8px rgba(165, 30, 63, 0.1);
}

.payment-options .form-check-input:checked + .form-check-label {
    color: #A51E3F;
}

.payment-options .form-check-input:checked {
    background-color: #A51E3F;
    border-color: #A51E3F;
}

.payment-options .form-check-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    font-size: 1rem;
}

.payment-options .form-check-label i {
    font-size: 1.2rem;
    margin-right: 0.75rem;
}

.payment-options .form-check-label small {
    display: block;
    margin-top: 0.25rem;
    font-weight: 400;
    opacity: 0.8;
    font-size: 0.9rem;
}

/* Credit Card Form Styling */
.credit-card-form {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #A51E3F;
    margin-top: 1rem;
}

.credit-card-form h5 {
    color: #A51E3F;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
}

.credit-card-form .form-control {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    padding: 10px 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.credit-card-form .form-control:focus {
    border-color: #A51E3F;
    box-shadow: 0 0 0 0.2rem rgba(165, 30, 63, 0.25);
}

.credit-card-form .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

/* Buttons Styling */
#proceedPaymentBtn {
    background: #A51E3F;
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(165, 30, 63, 0.3);
}

#proceedPaymentBtn:hover {
    background: #8B1A35;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(165, 30, 63, 0.4);
}

#clearCartBtn {
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid #dc3545;
    color: #dc3545;
    background: white;
}

#clearCartBtn:hover {
    background: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

/* Empty Cart Styling */
.empty-cart {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    border: 2px dashed #A51E3F;
    margin: 2rem 0;
}

.empty-cart i {
    color: #A51E3F;
    margin-bottom: 1rem;
    font-size: 3rem;
}

.empty-cart h4 {
    color: #A51E3F;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.empty-cart p {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

.empty-cart .btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 1rem;
    background: #A51E3F;
    border: none;
    box-shadow: 0 2px 8px rgba(165, 30, 63, 0.3);
    transition: all 0.3s ease;
}

.empty-cart .btn:hover {
    background: #8B1A35;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(165, 30, 63, 0.4);
}

/* RTL Support */
[dir="rtl"] .payment-options .form-check-label {
    text-align: right;
}

[dir="rtl"] .credit-card-form .form-label {
    text-align: right;
}

[dir="rtl"] .payment-options .form-check-label i {
    margin-right: 0;
    margin-left: 1rem;
}

/* Loading Animation */
.cart-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4rem;
}

.cart-loading .spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #A51E3F;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Success Animation */
.payment-success {
    text-align: center;
    padding: 3rem;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border-radius: 20px;
    border: 2px solid #28a745;
    margin: 2rem 0;
}

.payment-success i {
    color: #28a745;
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: successPulse 2s infinite;
}

@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.payment-success h4 {
    color: #155724;
    font-weight: 700;
    margin-bottom: 1rem;
}

/* Error Animation */
.payment-error {
    text-align: center;
    padding: 3rem;
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    border-radius: 20px;
    border: 2px solid #dc3545;
    margin: 2rem 0;
}

.payment-error i {
    color: #dc3545;
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: errorShake 0.5s ease-in-out;
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.payment-error h4 {
    color: #721c24;
    font-weight: 700;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-page {
        padding: 1rem 0;
    }
    
    .cart-container {
        margin: 1rem;
        border-radius: 12px;
    }
    
    .cart-header {
        padding: 1.5rem;
    }
    
    .cart-header .section-title {
        font-size: 2rem;
    }
    
    .cart-items {
        padding: 1.5rem;
    }
    
    .cart-summary,
    .payment-method-selection,
    .credit-card-form {
        padding: 1.5rem;
    }
    
    .cart-item {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .payment-options .form-check {
        padding: 0.75rem;
    }
    
    .cart-summary {
        position: static;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    
    .quantity-controls button {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
    }
    
    .quantity-controls span {
        font-size: 1rem;
        min-width: 25px;
    }
}

@media (max-width: 576px) {
    .cart-header .section-title {
        font-size: 1.5rem;
    }
    
    .cart-items,
    .cart-summary,
    .payment-method-selection,
    .credit-card-form {
        padding: 1rem;
    }
    
    .cart-item {
        padding: 0.75rem;
    }
    
    .quantity-controls button {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .quantity-controls span {
        font-size: 0.9rem;
        min-width: 20px;
    }
    
    .empty-cart {
        padding: 2rem 1rem;
    }
    
    .empty-cart i {
        font-size: 2.5rem;
    }
    
    .empty-cart h4 {
        font-size: 1.1rem;
    }
}
</style>

<div class="cart-page">
    <div class="container py-5" style="margin-top: 100px;">
        <div class="cart-container">
            <!-- Cart Header -->
            <div class="cart-header">
                <h6 class="section-subtitle"><?= $lang['cart'] ?></h6>
                <h2 class="section-title"><?= $lang['shopping_cart'] ?></h2>
            </div>
            
            <div class="row g-0">
                <!-- Cart Items Section -->
                <div class="col-lg-8">
                    <div class="cart-items" id="cartItems">
                        <!-- Cart items will be loaded here via JavaScript -->
                    </div>
                </div>
                
                <!-- Cart Summary & Payment Section -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4><?= $lang['order_summary'] ?></h4>
                        
                        <div class="summary-item d-flex justify-content-between">
                            <span><?= $lang['subtotal'] ?>:</span>
                            <span id="subtotal">0 ₪</span>
                        </div>
                        
                        <div class="summary-item d-flex justify-content-between">
                            <span><?= $lang['shipping'] ?>:</span>
                            <span><?= $lang['free'] ?></span>
                        </div>
                        
                        <div class="summary-item d-flex justify-content-between">
                            <strong><?= $lang['total'] ?>:</strong>
                            <strong id="total">0 ₪</strong>
                        </div>
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <div class="payment-method-selection">
                        <h5><?= $lang['select_payment_method'] ?></h5>
                        
                        <div class="payment-options">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="whatsappPayment" value="whatsapp" checked>
                                <label class="form-check-label" for="whatsappPayment">
                                    <i class="fab fa-whatsapp"></i>
                                    <strong><?= $lang['whatsapp_payment'] ?></strong>
                                    <small><?= $lang['payment_secure'] ?></small>
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="creditCardPayment" value="creditcard">
                                <label class="form-check-label" for="creditCardPayment">
                                    <i class="fas fa-credit-card"></i>
                                    <strong><?= $lang['credit_card_payment'] ?></strong>
                                    <small><?= $lang['payment_secure'] ?></small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Credit Card Payment Form (Hidden by default) -->
                    <div class="credit-card-form" id="creditCardForm" style="display: none;">
                        <h5><?= $lang['payment_details'] ?></h5>
                        
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label"><?= $lang['card_number'] ?></label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiryDate" class="form-label"><?= $lang['expiry_date'] ?></label>
                                <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="col-md-6">
                                <label for="cvv" class="form-label"><?= $lang['cvv'] ?></label>
                                <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cardholderName" class="form-label"><?= $lang['cardholder_name'] ?></label>
                            <input type="text" class="form-control" id="cardholderName" placeholder="<?= $currentLang === 'ar' ? 'اسم حامل البطاقة' : 'Cardholder Name' ?>">
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2" id="actionButtons" style="display: none;">
                        <button class="btn btn-primary" id="proceedPaymentBtn">
                            <i class="fab fa-whatsapp me-2"></i><?= $lang['checkout_via_whatsapp'] ?>
                        </button>
                        
                        <button class="btn btn-outline-danger" id="clearCartBtn">
                            <i class="fas fa-trash me-2"></i><?= $lang['clear_cart'] ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Product data from database
const products = <?= json_encode(array_reduce($products ?? [], function($carry, $product) use ($currentLang) {
    $carry[$product['id']] = [
        'name' => $currentLang === 'ar' ? ($product['name_ar'] ?: $product['name']) : $product['name'],
        'price' => $product['price'],
        'image' => $product['image'] ? 'assets/images/products/' . $product['image'] : 'assets/images/product-placeholder.jpg'
    ];
    return $carry;
}, [])) ?>;

function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContainer = document.getElementById('cartItems');
    const actionButtons = document.getElementById('actionButtons');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-4x mb-4"></i>
                <h4><?= $currentLang === 'ar' ? 'سلة التسوق فارغة' : 'Your cart is empty' ?></h4>
                <p class="text-muted mb-4"><?= $currentLang === 'ar' ? 'أضف بعض المنتجات إلى سلة التسوق للبدء' : 'Add some products to your cart to get started' ?></p>
                <a href="?page=product&lang=<?= $currentLang ?>" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i><?= $lang['browse_products'] ?>
                </a>
            </div>
        `;
        actionButtons.style.display = 'none';
        updateSummary(0);
        return;
    }
    
    // Show action buttons when cart has items
    actionButtons.style.display = 'block';
    
    let cartHTML = '';
    let total = 0;
    
    cart.forEach(item => {
        const product = products[item.id];
        if (product) {
            const itemTotal = product.price * item.quantity;
            total += itemTotal;
            
            cartHTML += `
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="${product.image}" alt="${product.name}" class="img-fluid">
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-2 fw-bold">${product.name}</h5>
                            <p class="text-muted mb-0 fs-5">${product.price} ₪</p>
                        </div>
                        <div class="col-md-3">
                            <div class="quantity-controls d-flex align-items-center justify-content-center">
                                <button class="btn" onclick="updateQuantity(${item.id}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="mx-3">${item.quantity}</span>
                                <button class="btn" onclick="updateQuantity(${item.id}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <strong class="fs-5 text-primary">${itemTotal} ₪</strong>
                        </div>
                        <div class="col-md-1 text-center">
                            <button class="btn btn-outline-danger btn-sm" onclick="removeItem(${item.id})" title="<?= $currentLang === 'ar' ? 'حذف المنتج' : 'Remove item' ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    });
    
    cartContainer.innerHTML = cartHTML;
    updateSummary(total);
}

function updateQuantity(productId, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const itemIndex = cart.findIndex(item => item.id == productId);
    
    if (itemIndex !== -1) {
        cart[itemIndex].quantity += change;
        
        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function removeItem(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id != productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
}

function updateSummary(total) {
    document.getElementById('subtotal').textContent = total + ' ₪';
    document.getElementById('total').textContent = total + ' ₪';
}

function clearCart() {
    localStorage.removeItem('cart');
    loadCart();
    updateCartCount();
}

function proceedToPayment() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        showNotification('<?= $currentLang === 'ar' ? 'سلة التسوق فارغة!' : 'Your cart is empty!' ?>', 'error');
        return;
    }
    
    const selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    
    if (selectedPaymentMethod === 'whatsapp') {
        checkoutViaWhatsApp();
    } else if (selectedPaymentMethod === 'creditcard') {
        processCreditCardPayment();
    }
}

async function checkoutViaWhatsApp() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    let message = '<?= $currentLang === 'ar' ? 'مرحباً! أود تقديم طلب:\n\n' : 'Hello! I would like to place an order:\n\n' ?>';
    let total = 0;
    
    // Prepare cart data for API
    const cartData = [];
    
    cart.forEach(item => {
        const product = products[item.id];
        if (product) {
            const itemTotal = product.price * item.quantity;
            total += itemTotal;
            message += `${product.name} x${item.quantity} = ${itemTotal} ₪\n`;
            
            cartData.push({
                product_id: item.id,
                product_name: product.name,
                quantity: item.quantity,
                price: product.price
            });
        }
    });
    
    message += `\n<?= $currentLang === 'ar' ? 'المجموع:' : 'Total:' ?> ${total} ₪`;
    
    // Save order to database
    try {
        const response = await fetch('api/create_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart: cartData,
                payment_method: 'whatsapp',
                customer_name: 'Guest',
                customer_email: 'guest@example.com',
                whatsapp_message: message
            })
        });
        
        const result = await response.json();
        if (result.success) {
            console.log('Order saved successfully:', result.order_id);
        }
    } catch (error) {
        console.error('Error saving order:', error);
    }
    
    // WhatsApp link
    const whatsappLink = `https://wa.me/<?= getSetting('whatsapp_number', '0592310435') ?>?text=${encodeURIComponent(message)}`;
    window.open(whatsappLink, '_blank');
}

async function processCreditCardPayment() {
    const cardNumber = document.getElementById('cardNumber').value;
    const expiryDate = document.getElementById('expiryDate').value;
    const cvv = document.getElementById('cvv').value;
    const cardholderName = document.getElementById('cardholderName').value;
    
    // Basic validation
    if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
        showPaymentError('<?= $currentLang === 'ar' ? 'يرجى ملء جميع الحقول المطلوبة' : 'Please fill in all required fields' ?>');
        return;
    }
    
    // Show loading animation
    showPaymentLoading();
    
    // Prepare cart data for API
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartData = [];
    
    cart.forEach(item => {
        const product = products[item.id];
        if (product) {
            cartData.push({
                product_id: item.id,
                product_name: product.name,
                quantity: item.quantity,
                price: product.price
            });
        }
    });
    
    // Simulate payment processing
    setTimeout(async () => {
        // Simulate success (90% chance) or failure (10% chance)
        const isSuccess = Math.random() > 0.1;
        
        if (isSuccess) {
            // Save order to database
            try {
                const response = await fetch('api/create_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cart: cartData,
                        payment_method: 'creditcard',
                        customer_name: cardholderName,
                        customer_email: 'guest@example.com',
                        customer_phone: '',
                        customer_address: ''
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    console.log('Order saved successfully:', result.order_id);
                    showPaymentSuccess('<?= $currentLang === 'ar' ? 'تم الدفع بنجاح! سنتواصل معك قريباً.' : 'Payment successful! We will contact you soon.' ?>');
                    // Clear cart after successful payment
                    setTimeout(() => {
                        localStorage.removeItem('cart');
                        loadCart();
                        updateCartCount();
                    }, 3000);
                } else {
                    showPaymentError('<?= $currentLang === 'ar' ? 'تم الدفع بنجاح ولكن حدث خطأ في حفظ الطلب. يرجى التواصل معنا.' : 'Payment successful but error saving order. Please contact us.' ?>');
                }
            } catch (error) {
                console.error('Error saving order:', error);
                showPaymentError('<?= $currentLang === 'ar' ? 'تم الدفع بنجاح ولكن حدث خطأ في حفظ الطلب. يرجى التواصل معنا.' : 'Payment successful but error saving order. Please contact us.' ?>');
            }
        } else {
            showPaymentError('<?= $currentLang === 'ar' ? 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى.' : 'Payment processing failed. Please try again.' ?>');
        }
    }, 3000);
}

function showPaymentLoading() {
    const cartContainer = document.querySelector('.cart-items');
    cartContainer.innerHTML = `
        <div class="cart-loading">
            <div class="spinner"></div>
        </div>
    `;
}

function showPaymentSuccess(message) {
    const cartContainer = document.querySelector('.cart-items');
    cartContainer.innerHTML = `
        <div class="payment-success">
            <i class="fas fa-check-circle"></i>
            <h4><?= $currentLang === 'ar' ? 'تم بنجاح!' : 'Success!' ?></h4>
            <p>${message}</p>
        </div>
    `;
}

function showPaymentError(message) {
    const cartContainer = document.querySelector('.cart-items');
    cartContainer.innerHTML = `
        <div class="payment-error">
            <i class="fas fa-exclamation-triangle"></i>
            <h4><?= $currentLang === 'ar' ? 'خطأ في الدفع' : 'Payment Error' ?></h4>
            <p>${message}</p>
            <button class="btn btn-primary mt-3" onclick="loadCart()">
                <i class="fas fa-redo me-2"></i><?= $currentLang === 'ar' ? 'إعادة المحاولة' : 'Try Again' ?>
            </button>
        </div>
    `;
}

// Event listeners
document.getElementById('clearCartBtn').addEventListener('click', clearCart);
document.getElementById('proceedPaymentBtn').addEventListener('click', proceedToPayment);

// Payment method change handler
document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const creditCardForm = document.getElementById('creditCardForm');
        const proceedBtn = document.getElementById('proceedPaymentBtn');
        
        if (this.value === 'creditcard') {
            creditCardForm.style.display = 'block';
            proceedBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i><?= $lang['pay_now'] ?>';
        } else {
            creditCardForm.style.display = 'none';
            proceedBtn.innerHTML = '<i class="fab fa-whatsapp me-2"></i><?= $lang['checkout_via_whatsapp'] ?>';
        }
    });
});

// Credit card input formatting
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

document.getElementById('expiryDate').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});

document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Load cart on page load
document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php include 'includes/footer.php'; ?>

