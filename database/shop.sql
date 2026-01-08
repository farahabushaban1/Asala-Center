-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2025 at 12:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `name_ar`, `slug`, `description`, `description_ar`, `created_at`) VALUES
(2, 'Jumpsuit', 'بذلة', 'jumpsuit', 'Embroidered jumpsuit', 'بذلة مطرزة', '2025-08-18 10:25:09'),
(3, 'Dresses', 'فساتين', 'dresses', 'Palestinian heritage dresses', 'فساتين تراثية فلسطينية', '2025-08-18 10:25:09'),
(4, 'Jackets - Shawls', 'السترات والشالات', 'jackets-shawls', 'embroidered jackets and shoes', 'السترات والشالات مطرزة', '2025-08-18 10:25:09'),
(7, 'Kids Dresses', 'فساتين اطفال', 'kids-dresses', 'Kids Dresses', 'فساتين اطفال', '2025-09-10 09:07:58'),
(8, 'Palestinian Thobes', 'الأثواب الفلسطينية', 'palestinian-thobes', 'Traditional Palestinian dresses', 'أثواب فلسطينية تقليدية مطرزة يدوياً', '2025-09-10 09:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `rating` tinyint(1) DEFAULT NULL COMMENT 'Rating from 1 to 5',
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `name`, `email`, `comment`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 52, 'جميل', 'FarahAbuShaban@gmail.com', 'جميل', 5, 'approved', '2025-12-29 08:21:42', '2025-12-29 08:21:42'),
(2, 52, 'جميل', 'admin@gmail.com', 'جميل', 5, 'approved', '2025-12-29 11:33:22', '2025-12-29 11:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `whatsapp_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `total_amount`, `status`, `whatsapp_message`, `created_at`, `updated_at`) VALUES
(1, 'Guest', 'guest@example.com', '', '', 557.00, 'confirmed', 'Hello! I would like to place an order:\n\nJumpsuit x1 = 557 ₪\n\nTotal: 557 ₪\n\nPayment Method: WhatsApp', '2025-12-24 11:27:41', '2025-12-24 11:29:28'),
(2, 'بليب', 'guest@example.com', '', '', 557.00, 'pending', 'Payment Method: Credit Card', '2025-12-24 11:28:19', '2025-12-24 11:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 48, 'Jumpsuit', 1, 557.00, '2025-12-24 11:27:41'),
(2, 2, 48, 'Jumpsuit', 1, 557.00, '2025-12-24 11:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `name_ar`, `slug`, `description`, `description_ar`, `image`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
(26, 8, 'Majdalawi Dress', 'الثياب الفلسطينية', 'majdalawi-dress', 'Majdalawi Dress\r\nA traditional Majdalawi dress in black and red, rich in heritage and elegance.\r\nFeatures detailed embroidery and a graceful, flowing design.', 'زي مجدلوي\r\nزي مجدلوي تقليدي باللونين الأسود والأحمر، غني بالتراث والأناقة.\r\nيتميز بتطريز دقيق وتصميم انسيابي رقيق.', 'product_1760428471_68ee01b7b72b4.jpeg', 400.00, NULL, 55, 1, '2025-10-14 07:54:31', '2025-12-29 07:40:52'),
(27, 8, 'Blue Dress', 'فستان أزرق', 'blue-dress-1', 'A blue dress with colorful embroidery, soft, feminine, and beautiful.', 'فستان أزرق بتطريز ملون، ناعم، أنثوي، وجميل.', 'product_1760428533_68ee01f50fab3.jpeg', 450.00, NULL, 55, 1, '2025-10-14 07:55:33', '2025-12-29 07:39:56'),
(28, 8, 'Black Dress', 'فستان أسود', 'black-dress-2', 'A black and gold dress with traditional embroidery, elegant and timeless.', 'فستان أسود وذهبي بتطريز تقليدي، أنيق وخالد.', 'product_1760428583_68ee02273b676.jpeg', 455.00, NULL, 33, 1, '2025-10-14 07:56:23', '2025-12-29 07:39:04'),
(29, 8, 'Traditional Embroidered Dress', 'فستان مطرز تقليدي', 'traditional-embroidered-dress', 'Traditional Embroidered Dress\r\nA white dress with red traditional embroidery, elegant, graceful, and timeless.', 'فستان مطرز تقليدي\r\nفستان أبيض مطرز باللون الأحمر بتطريز تقليدي، أنيق، رقيق، وخالد.', 'product_1760428628_68ee0254009ad.jpeg', 550.00, NULL, 68, 1, '2025-10-14 07:57:08', '2025-12-29 07:41:57'),
(30, 3, 'Blue dress', 'فستان أزرق', 'blue-dress', 'A flowing powder blue gown crafted from delicate layered fabric, highlighted with refined embroidery', 'فستان أزرق فاتح انسيابي مصنوع من قماش رقيق متعدد الطبقات، مزين بتطريزات راقية.', 'product_1766932628_695140942e5e1.jpeg', 530.00, NULL, 55, 1, '2025-10-14 07:59:36', '2025-12-28 14:37:08'),
(31, 3, 'luxurious dress', 'فستان فاخر', 'luxurious-dress-1', 'n elegant ivory dress made from finely detailed embroidered fabric, featuring a soft lightweight texture', 'فستان أنيق بلون العاج مصنوع من قماش مطرز بتفاصيل دقيقة، يتميز بملمس ناعم وخفيف الوزن', 'product_1766932555_6951404bc45fe.jpeg', 600.00, NULL, 36, 1, '2025-10-14 08:00:10', '2025-12-28 14:35:55'),
(32, 3, 'luxurious dress', 'فستان فاخر', 'luxurious-dress-2', '“Luxurious matte satin dress in deep black, highlighted with bold burgundy accents and a regal, flowing silhouette.”', '&quot;فستان فاخر من الساتان غير اللامع باللون الأسود الداكن، مزين بلمسات جريئة باللون العنابي وقصة ملكية انسيابية.&quot;', 'product_1766932682_695140ca1e4b8.jpeg', 552.00, NULL, 66, 1, '2025-10-14 08:01:15', '2025-12-28 14:38:02'),
(33, 3, 'luxurious dress', 'فستان فاخر', 'luxurious-dress', 'A luxurious burgundy dress crafted from rich embroidered fabric', 'فستان فاخر بلون عنابي مصنوع من قماش مطرز فاخر', 'product_1766932499_695140139ae02.jpeg', 600.00, NULL, 88, 1, '2025-10-14 08:05:58', '2025-12-28 14:34:59'),
(34, 4, 'Formal Jacket', 'جاكيت رسمي', 'formal-jacket-1', 'A stylish formal jacket adorned with luxurious red embroidery for a sophisticated look.', 'جاكيت رسمي أنيق مزين بتطريز احمر فاخرة لإطلالة راقية', 'product_1760429273_68ee04d982687.jpeg', 300.00, NULL, 25, 1, '2025-10-14 08:07:53', '2025-12-28 13:49:42'),
(35, 4, 'Elegant formal', 'جاكيت رسمي', 'elegant-formal', 'Elegant formal jacket featuring refined blue embroidery for a sophisticated look', 'جاكيت رسمي أنيق مزين بتطريز أزرق فاخر لإطلالة راقية', 'product_1760429307_68ee04fbb861b.jpeg', 325.00, NULL, 36, 1, '2025-10-14 08:08:27', '2025-12-28 13:41:10'),
(36, 4, 'Formal Jacket', 'جاكيت رسمي', 'formal-jacket', 'A stylish formal jacket adorned with luxurious yellow embroidery for a sophisticated look.', 'جاكيت رسمي أنيق مزين بتطريز اصفر فاخرة لإطلالة راقية', 'product_1760429350_68ee052649df8.jpeg', 253.00, NULL, 58, 1, '2025-10-14 08:09:10', '2025-12-28 13:48:33'),
(37, 4, 'Elegant black', 'شال أسود', 'elegant-black-3', 'Elegant black shawl embroidered with Palestinian motifs in gold', 'شال أسود أنيق مطرز بزخارف فلسطينية ذهبي', 'product_1760429485_68ee05adec899.jpeg', 55.00, NULL, 22, 1, '2025-10-14 08:11:25', '2025-12-28 13:44:29'),
(39, 4, 'Elegant black', 'شال أسود', 'elegant-black-2', 'Elegant black shawl embroidered with Palestinian motifs, blue', 'شال أسود أنيق مطرز بزخارف فلسطينية ازرق', 'product_1760429536_68ee05e034920.jpeg', 58.00, NULL, 22, 1, '2025-10-14 08:12:16', '2025-12-28 13:43:42'),
(40, 4, 'Elegant black', 'أسود أنيق', 'elegant-black', 'Elegant Black shawl embroidered with red Palestinian motifs', 'شال أسود أنيق مطرز بزخارف فلسطينية حمراء', 'product_1760429598_68ee061e38533.jpeg', 50.00, NULL, 20, 1, '2025-10-14 08:13:18', '2025-12-28 13:40:24'),
(41, 4, 'Elegant black', 'شال أسود', 'elegant-black-1', 'Elegant black shawl embroidered with yellow Palestinian motifs', 'شال أسود أنيق مطرز بزخارف فلسطينية اصفر', 'product_1760429646_68ee064e18c05.jpeg', 50.00, NULL, 52, 1, '2025-10-14 08:14:06', '2025-12-28 13:43:01'),
(42, 7, 'Kids Dresses', 'فساتين الاطفال', 'kids-dresses', 'A white dress with blue embroidery for kids, sweet and graceful.', 'فستان أبيض مطرز باللون الأزرق للأطفال، جميل وأنيق.', 'product_1760430320_68ee08f04a3ce.jpeg', 320.00, NULL, 150, 1, '2025-10-14 08:25:20', '2025-12-28 13:05:23'),
(43, 7, 'Kids Dresses', 'فساتين الاطفال', 'kids-dresses-1', 'A beige embroidered dress for kids, soft and beautiful.', 'فستان بيج مطرز للأطفال، ناعم وجميل.', 'product_1760430354_68ee0912cadce.jpeg', 162.00, NULL, 58, 1, '2025-10-14 08:25:54', '2025-12-28 13:05:00'),
(44, 7, 'Kids Dresses', 'فساتين الاطفال', 'kids-dresses-2', 'A red embroidered dress for kids, bright and traditional.', 'فستان أحمر مطرز للأطفال، زاهي وتقليدي.', 'product_1760430392_68ee093800bab.jpeg', 122.00, NULL, 36, 1, '2025-10-14 08:26:32', '2025-12-28 13:04:39'),
(45, 7, 'Kids Dresses', 'فساتين الاطفال', 'kids-dresses-3', 'A black dress with red embroidery for kids, cute and elegant.', 'فستان أسود مطرز باللون الأحمر للأطفال، لطيف وأنيق.', 'product_1760430439_68ee0967435e5.jpeg', 126.00, NULL, 55, 1, '2025-10-14 08:27:19', '2025-12-28 13:04:18'),
(46, 2, 'Black Jumpsuit (Green Embroidery)', 'بذلة سوداء (تطريز أخضر)', 'black-jumpsuit-green-embroidery', 'Black jumpsuit with green embroidery, classic and elegant.', 'جمبسوت أسود مطرز باللون الأخضر، كلاسيكي وأنيق.', 'product_1760430553_68ee09d9ed62c.jpeg', 600.00, NULL, 522, 1, '2025-10-14 08:29:13', '2025-12-28 13:12:25'),
(47, 2, 'Black Jumpsuit (Red Embroidery)', 'بذلة سوداء (تطريز أحمر)', 'black-jumpsuit-red-embroidery', 'Black jumpsuit with red embroidery, elegant and bold.', 'جمبسوت أسود بتطريز أحمر، أنيق وجريء.', 'product_1760430580_68ee09f48418b.jpeg', 658.00, NULL, 58, 1, '2025-10-14 08:29:40', '2025-12-28 13:10:26'),
(48, 2, 'White Jumpsuit (Blue Embroidery)', 'جمبسوت أبيض (تطريز أزرق)', 'white-jumpsuit-blue-embroidery', 'White jumpsuit with blue embroidery, soft and classy.', 'جمبسوت أبيض مطرز باللون الأزرق، ناعم وأنيق.', 'product_1760430607_68ee0a0f11da9.jpeg', 557.00, NULL, 56, 1, '2025-10-14 08:30:07', '2025-12-28 13:11:09'),
(49, 2, 'White Jumpsuit (Red Embroidery)', 'بذلة بيضاء (تطريز أحمر)', 'white-jumpsuit-red-embroidery', 'White jumpsuit with red embroidery, stylish and modern.', 'جمبسوت أبيض بتطريز أحمر، أنيق وعصري.', 'product_1760430630_68ee0a26a5903.jpeg', 658.00, NULL, 58, 1, '2025-10-14 08:30:30', '2025-12-28 13:11:48'),
(50, 2, 'Suit (blue embroidery)', 'بدلة (تطريز ازرق)', 'suit-blue-embroidery', 'A white jumpsuit with blue embroidery, elegant and modern.', 'جمبسوت أبيض بتطريز ازرق، أنيق وعصري.', 'product_1760430679_68ee0a575a376.jpeg', 658.00, NULL, 23, 1, '2025-10-14 08:31:19', '2025-12-28 13:14:45'),
(51, 3, 'Soft dress', 'فستان ناعم', 'soft-dress', 'Soft off-white cotton-linen dress featuring delicate red embroidery, designed with a gentle flow and refined elegance', 'فستان ناعم من القطن والكتان بلون أبيض كريمي مزين بتطريز أحمر رقيق، مصمم بانسيابية لطيفة وأناقة راقية.', 'product_1766933058_6951424223ae8.jpeg', 522.00, NULL, 65, 1, '2025-12-28 14:44:18', '2025-12-28 14:44:18'),
(52, 3, 'White dress', 'فستان أبيض', 'white-dress', 'Elegant white cashmere-cotton dress adorned with rich burgundy embroidery, flowing gracefully for a timeless and luxurious look', 'فستان أبيض أنيق من الكشمير والقطن، مزين بتطريز بورغندي فاخر، ينساب برشاقة لإطلالة خالدة وفاخرة.', 'product_1766933136_695142900ef57.jpeg', 552.00, NULL, 56, 1, '2025-12-28 14:45:36', '2025-12-28 14:45:36'),
(53, 3, 'Black dress', 'فستان أسود', 'black-dress', 'A flowing black crepe dress adorned with intricate multicolor embroidery, offering a graceful movement and a luxurious, timeless elegance.', 'فستان أسود انسيابي من الكريب مزين بتطريز متعدد الألوان معقد، يوفر حركة رشيقة وأناقة فاخرة خالدة.', 'product_1766933196_695142cc4c248.jpeg', 256.00, NULL, 55, 1, '2025-12-28 14:46:36', '2025-12-28 14:46:36'),
(54, 3, 'black dress', 'فستان أسود', 'black-dress-1', 'Crafted from soft flowing crepe, this black dress features rich deep-red embroidery, creating a refined silhouette with elegant movement and classic luxury.', 'صُنع هذا الفستان الأسود من قماش الكريب الناعم المتدفق، ويتميز بتطريز أحمر داكن غني، مما يخلق صورة ظلية راقية مع حركة أنيقة وفخامة كلاسيكية.', 'product_1766933315_6951434335a0a.jpeg', 362.00, NULL, 25, 1, '2025-12-28 14:48:35', '2025-12-28 14:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Asala Center', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(2, 'site_description', 'Discover Palestinian heritage in our original oriental embroidery each piece tells a story of history and authenticity.', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(3, 'contact_email', 'FarahAbuShaban@gmail.com', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(4, 'contact_phone', '00970592310435', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(5, 'contact_address', 'Palestine - Gaza', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(6, 'whatsapp_number', '970592310435', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(7, 'facebook_url', 'https://www.facebook.com/', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(8, 'instagram_url', 'https://www.instagram.com/', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(9, 'working_hours', 'الأحد - الخميس: 9:00 ص - 6:00 م', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(10, 'currency', 'ILS', '2025-08-21 12:08:49', '2025-08-21 12:29:46'),
(11, 'currency_symbol', '₪', '2025-08-21 12:08:49', '2025-08-21 12:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@asalacenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-08-21 13:49:25', '2025-08-21 13:49:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cart_items_cart` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `slug_2` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_active` (`is_active`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_product` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
