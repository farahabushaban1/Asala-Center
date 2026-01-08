# 🧵 Asala Center – PHP Online Store

A simple e-commerce project showcasing **Palestinian oriental embroidery**.  
Built with **PHP (no framework)** and **MySQL**, with a frontend using **HTML5 / CSS3 / JavaScript / Bootstrap 5**.

> **Note:** This store is intended for product showcase and ordering via **Cart + WhatsApp Checkout**.  
> There is **no customer login** (only an admin dashboard).

---

## 📂 Project Structure

```
/project-root
│── index.php              # Entry point, routes to pages
│
├── pages/                 # Frontend pages
│   ├── home.php
│   ├── about.php
│   ├── product.php
│   ├── cart.php
│   ├── contact.php
│   └── 404.php
│
├── languages/             # Translations
│   ├── ar.php
│   └── en.php
│
├── includes/              # Shared includes
│   ├── header.php
│   ├── footer.php
│   └── functions.php
│
├── database/              # Database shop
│   └── shop.sql
│
├── config/                # Config files
│   ├── config.php
│   └── database.php
│
├── assets/                # Static assets
│   ├── css/
│   ├── js/
│   └── images/
│
├── api/                   # API endpoints (AJAX / POST)
│   └── contact.php
│
├── admin/                 # Admin dashboard
│   ├── index.php          # Dashboard
│   ├── login.php
│   ├── logout.php
│   ├── company_info.php
│   └── includes/
│       └── functions.php  # Admin session + helper functions
│
└── 
```

---

## ⚙️ Setup & Run

1. **Clone the project:**
   ```bash
  git clone https://github.com/farahabushaban1/Asala-Center.git
  cd Asala-Center
   ```

2. **Database Setup:**
   - Create a new MySQL database called `shop`
   - Import the shop file:
     ```sql
     database/shop.sql
     ```

3. **Config database connection:**
   - Edit `config/config.php`:
     ```php
     define('DB_USER', 'root');
     define('DB_PASS', 'secret');
     define('APP_ENV', 'dev'); // or 'prod'
     ```

4. **Run locally (XAMPP / Laragon / PHP Built-in):**
   ```bash
   php -S localhost:81 -t .
   ```
   Then open in browser:  
   👉 `http://localhost:81`

---

## 🔐 Admin Panel

- Access via: `http://localhost:81/admin`
- Login with credentials from `users` table (`role = admin`).
- Features:
  - Manage products
  - Manage categories
  - Review orders (from cart)
  - Edit store info (`company_info.php`)

---

## 🗄️ Database (Summary)

### `users`
| id | name | email | password_hash | role (admin/customer) |

### `categories`
| id | name | slug |

### `products`
| id | category_id | name | slug | price | sale_price | description |

### `product_images`
| id | product_id | url | is_main |

### `carts` + `cart_items`
Store added products for cart.

### `orders` + `order_items`
Orders placed, integrated with **WhatsApp Checkout**.

---

## 🌐 Features

- 🔄 **Multi-language (English / Arabic)** via `languages` folder.
- 🎨 **Responsive Bootstrap 5 design** (Mobile First).
- 🛒 **Simple shopping cart** (add, update, remove).
- 📦 **Checkout via WhatsApp** (instead of payment gateway).
- 🖼️ Product images stored in `assets/images`.
- 🛡️ Security:
  - **CSRF tokens** for POST requests.
  - **PDO prepared statements** against SQL injection.
  - **Secure sessions** for admin.

---

## 🚀 Future Improvements

- Product filters (by price, category)
- Discount coupons
- Payment gateway (PayPal / Stripe)
- Customer + admin email notifications

---

## 👨‍💻 Developer

- ✨ **Asala Center E-Store**
- 📍 Gaza – Palestine
- 🧵 Platform to showcase and sell Palestinian oriental embroidery
