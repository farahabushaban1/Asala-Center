// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            addToCart(productId);
        });
    });
    
    // Favorite functionality
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    
    favoriteButtons.forEach(button => {
        // Ensure heart is outlined by default
        const heartIcon = button.querySelector('i');
        if (heartIcon) {
            heartIcon.style.fontWeight = '400';
        }
        
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            toggleFavorite(productId, this);
        });
        
        // Check if product is already in favorites
        const productId = button.getAttribute('data-product-id');
        if (isFavorite(productId)) {
            button.classList.add('active');
            const heartIcon = button.querySelector('i');
            if (heartIcon) {
                heartIcon.style.fontWeight = '900';
            }
        }
    });
    
    // Update cart count
    updateCartCount();
});

function addToCart(productId) {
    // Get current cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if product already exists in cart
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            quantity: 1
        });
    }
    
    // Save cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
    // Show success message
    showNotification('Product added to cart!', 'success');
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Determine background color based on type
    let bgColor = '#007bff'; // default info
    if (type === 'success') bgColor = '#28a745';
    if (type === 'error') bgColor = '#dc3545';
    if (type === 'warning') bgColor = '#ffc107';
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
        max-width: 300px;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Favorite functionality
function toggleFavorite(productId, button) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    
    const index = favorites.indexOf(productId);
    const heartIcon = button.querySelector('i');
    
    if (index > -1) {
        // Remove from favorites
        favorites.splice(index, 1);
        button.classList.remove('active');
        if (heartIcon) {
            heartIcon.style.fontWeight = '400';
        }
        showNotification('Product removed from favorites!', 'info');
    } else {
        // Add to favorites
        favorites.push(productId);
        button.classList.add('active');
        if (heartIcon) {
            heartIcon.style.fontWeight = '900';
        }
        showNotification('Product added to favorites!', 'success');
    }
    
    // Save to localStorage
    localStorage.setItem('favorites', JSON.stringify(favorites));
}

function isFavorite(productId) {
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    return favorites.includes(productId);
}

// Share functionality
function toggleShareMenu(event, productId, productName, productPrice) {
    event.preventDefault();
    event.stopPropagation();
    
    // Close all other share menus
    document.querySelectorAll('.share-dropdown').forEach(dropdown => {
        if (dropdown !== event.target.closest('.share-dropdown')) {
            dropdown.classList.remove('active');
        }
    });
    
    // Toggle current share menu
    const dropdown = event.target.closest('.share-dropdown');
    if (dropdown) {
        const isActive = dropdown.classList.contains('active');
        dropdown.classList.toggle('active');
        
        // Set product URL when opening menu
        if (!isActive) {
            const baseUrl = window.location.origin + window.location.pathname;
            const currentLang = document.documentElement.lang || 'en';
            const productUrl = `${baseUrl}?page=product&product_id=${productId}&lang=${currentLang}`;
            
            const linkInput = dropdown.querySelector(`#share-link-${productId}`);
            if (linkInput) {
                linkInput.value = productUrl;
            }
        }
    }
}

function copyProductLink(productId, event) {
    event.preventDefault();
    event.stopPropagation();
    
    const linkInput = document.getElementById(`share-link-${productId}`);
    if (!linkInput) return;
    
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(linkInput.value).then(() => {
        showNotification('Link copied to clipboard!', 'success');
        // Change icon temporarily
        const copyBtn = event.target.closest('.share-link-copy-btn');
        if (copyBtn) {
            const icon = copyBtn.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-check';
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        }
    }).catch(() => {
        // Fallback for older browsers
        try {
            document.execCommand('copy');
            showNotification('Link copied to clipboard!', 'success');
        } catch (err) {
            showNotification('Failed to copy link', 'error');
        }
    });
}

function shareProduct(event, platform, productId, productName, productPrice) {
    event.preventDefault();
    event.stopPropagation();
    
    const baseUrl = window.location.origin + window.location.pathname;
    const currentLang = document.documentElement.lang || 'en';
    const productUrl = `${baseUrl}?page=product&product_id=${productId}&lang=${currentLang}`;
    const shareText = `${productName} - ${productPrice} ₪\n${productUrl}`;
    
    let shareLink = '';
    
    switch(platform) {
        case 'whatsapp':
            shareLink = `https://wa.me/?text=${encodeURIComponent(shareText)}`;
            window.open(shareLink, '_blank');
            break;
        case 'facebook':
            shareLink = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
            window.open(shareLink, '_blank', 'width=600,height=400');
            break;
    }
    
    // Close share menu after a short delay
    setTimeout(() => {
        const dropdown = event.target.closest('.share-dropdown');
        if (dropdown) {
            dropdown.classList.remove('active');
        }
    }, 300);
}

// Close share menu when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.share-dropdown')) {
        document.querySelectorAll('.share-dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Hero section indicators
const indicators = document.querySelectorAll('.indicator');
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', function() {
        // Remove active class from all indicators
        indicators.forEach(ind => ind.classList.remove('active'));
        // Add active class to clicked indicator
        this.classList.add('active');
        
        // Here you can add carousel functionality
        // For now, just visual feedback
    });
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(248, 249, 250, 0.95)';
        header.style.backdropFilter = 'blur(10px)';
    } else {
        header.style.background = '#F8F9FA';
        header.style.backdropFilter = 'none';
    }
});

// Product card hover effects
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Hero Carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    const heroCarousel = document.getElementById('heroCarousel');
    
    if (heroCarousel) {
        // Initialize Bootstrap carousel
        const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 8000, // 8 seconds
            wrap: true,
            keyboard: false,
            pause: false
        });
        
        // Add custom animations
        const carouselItems = heroCarousel.querySelectorAll('.carousel-item');
        carouselItems.forEach((item, index) => {
            item.addEventListener('transitionend', function() {
                if (this.classList.contains('active')) {
                    // Animate text elements
                    const title = this.querySelector('.hero-title');
                    const subtitle = this.querySelector('.hero-subtitle');
                    const description = this.querySelector('.hero-description');
                    const buttons = this.querySelector('.hero-buttons');
                    const image = this.querySelector('.hero-main-image');
                    
                    if (title) {
                        title.style.animation = 'slideInLeft 0.8s ease-out';
                    }
                    if (subtitle) {
                        subtitle.style.animation = 'slideInLeft 0.8s ease-out 0.2s both';
                    }
                    if (description) {
                        description.style.animation = 'slideInLeft 0.8s ease-out 0.4s both';
                    }
                    if (buttons) {
                        buttons.style.animation = 'slideInLeft 0.8s ease-out 0.6s both';
                    }
                    if (image) {
                        image.style.animation = 'slideInRight 0.8s ease-out 0.3s both';
                    }
                }
            });
        });
        

    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .hero-title,
    .hero-subtitle,
    .hero-description,
    .hero-buttons,
    .hero-main-image {
        opacity: 0;
    }
`;
document.head.appendChild(style);


