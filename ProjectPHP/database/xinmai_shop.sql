-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 05:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xinmai_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`) VALUES
(1, 'Honda', 'honda', NULL, 1),
(2, 'Mitsubishi', 'mitsubishi', NULL, 1),
(3, 'Kubota', 'kubota', NULL, 1),
(4, 'Hyundai', 'hyundai', NULL, 1),
(5, 'Denyo', 'denyo', NULL, 1),
(6, 'Jasic', 'jasic', NULL, 1),
(7, 'Makita', 'makita', NULL, 1),
(8, 'Bosch', 'bosch', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `image`, `icon`, `description`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Máy phát điện', 'may-phat-dien', NULL, NULL, 'fa-bolt', 'Máy phát điện chạy xăng, dầu chính hãng', 1, 1, '2026-06-07 14:55:47'),
(2, 'Máy bơm nước', 'may-bom-nuoc', NULL, NULL, 'fa-water', 'Bơm nước, bơm chìm, bơm công trình', 2, 1, '2026-06-07 14:55:47'),
(3, 'Máy hàn', 'may-han', NULL, NULL, 'fa-fire', 'Máy hàn que, hàn TIG, hàn inverter', 3, 1, '2026-06-07 14:55:47'),
(4, 'Máy nén khí', 'may-nen-khi', NULL, NULL, 'fa-wind', 'Máy nén khí dây đai, không dầu', 4, 1, '2026-06-07 14:55:47'),
(5, 'Máy đầm', 'may-dam', NULL, NULL, 'fa-weight-hanging', 'Máy đầm cóc, đầm bàn nền móng', 5, 1, '2026-06-07 14:55:47'),
(6, 'Máy xịt rửa áp lực cao', 'may-xit-rua', NULL, NULL, 'fa-spray-can', 'Máy rửa xe, xịt rửa cao áp', 6, 1, '2026-06-07 14:55:47'),
(7, 'Máy cắt - Máy khoan', 'may-cat-may-khoan', NULL, NULL, 'fa-screwdriver-wrench', 'Máy cắt sắt, khoan bê tông', 7, 1, '2026-06-07 14:55:47'),
(8, 'Máy trộn bê tông', 'may-tron-be-tong', NULL, NULL, 'fa-trowel', 'Máy trộn bê tông, trộn vữa', 8, 1, '2026-06-07 14:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value` decimal(15,0) NOT NULL DEFAULT 0,
  `min_order` decimal(15,0) NOT NULL DEFAULT 0,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order`, `usage_limit`, `used_count`, `expires_at`, `is_active`) VALUES
(1, 'XINMAI5', 'percent', 5, 2000000, NULL, 0, '2026-12-31', 1),
(2, 'GIAM200K', 'fixed', 200000, 5000000, NULL, 0, '2026-12-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `import_receipts`
--

CREATE TABLE `import_receipts` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `supplier` varchar(160) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `total` decimal(15,0) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_receipt_details`
--

CREATE TABLE `import_receipt_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `receipt_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `cost_price` decimal(15,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `coupon_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_email` varchar(160) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `subtotal` decimal(15,0) NOT NULL DEFAULT 0,
  `discount` decimal(15,0) NOT NULL DEFAULT 0,
  `total` decimal(15,0) NOT NULL DEFAULT 0,
  `payment_method` enum('cod','bank') NOT NULL DEFAULT 'cod',
  `status` enum('pending','confirmed','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `code`, `user_id`, `coupon_id`, `customer_name`, `customer_phone`, `customer_address`, `customer_email`, `note`, `subtotal`, `discount`, `total`, `payment_method`, `status`, `created_at`) VALUES
(2, 'DH260607F962F', 1, NULL, 'Quản trị viên', '0901234567', 'Hà Nội', 'admin@xinmai.vn', NULL, 7400000, 0, 7400000, 'bank', 'confirmed', '2026-06-07 15:01:15'),
(3, 'DH26060781A42', NULL, NULL, 'Trần Thị Hương', '0987654321', 'Số 12, Đường Nguyễn Trãi, Phường Thượng Đình, Quận Thanh Xuân, Hà Nội', 'huong@example.com', 'Giao giờ hành chính, gọi trước khi đến.', 22490000, 610000, 21880000, 'cod', 'pending', '2026-06-07 15:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(220) NOT NULL,
  `price` decimal(15,0) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 2, 3, 'Máy bơm nước Honda WB30XT (3 inch)', 7400000, 1),
(2, 3, 1, 'Máy phát điện Honda EP2500CX (2.2KVA)', 12200000, 1),
(3, 3, 5, 'Máy hàn điện tử Jasic ARC-250', 2890000, 1),
(4, 3, 3, 'Máy bơm nước Honda WB30XT (3 inch)', 7400000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(220) NOT NULL,
  `slug` varchar(240) NOT NULL,
  `sku` varchar(60) DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `price` decimal(15,0) NOT NULL DEFAULT 0,
  `sale_price` decimal(15,0) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `thumbnail` varchar(255) DEFAULT NULL,
  `short_desc` varchar(500) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `category_id`, `brand_id`, `price`, `sale_price`, `stock`, `thumbnail`, `short_desc`, `description`, `specs`, `is_featured`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Máy phát điện Honda EP2500CX (2.2KVA)', 'may-phat-dien-honda-ep2500cx', 'MPD-EP2500', 1, 1, 13500000, 12200000, 24, 'may-phat-dien.svg', 'Máy phát điện chạy xăng 2.2KVA, động cơ Honda GX160, giật nổ, êm và bền bỉ.', 'Máy phát điện Honda EP2500CX là dòng máy phát điện dân dụng phổ biến, sử dụng động cơ Honda GX160 chính hãng. Phù hợp cấp điện cho gia đình, cửa hàng, công trình nhỏ khi mất điện. Vận hành êm, tiết kiệm nhiên liệu, độ bền cao.', '{\"Công suất\":\"2.2 KVA\",\"Động cơ\":\"Honda GX160\",\"Nhiên liệu\":\"Xăng\",\"Dung tích bình\":\"3.6 lít\",\"Khởi động\":\"Giật nổ\",\"Trọng lượng\":\"39 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(2, 'Máy phát điện Hyundai HY9000LE (6KW)', 'may-phat-dien-hyundai-hy9000le', 'MPD-HY9000', 1, 4, 28500000, NULL, 12, 'may-phat-dien.svg', 'Máy phát điện xăng 6KW đề nổ, có ổn áp AVR, dùng cho hộ gia đình và văn phòng.', 'Hyundai HY9000LE công suất 6KW, trang bị bộ ổn áp AVR giúp điện áp đầu ra ổn định, bảo vệ thiết bị điện tử. Hệ thống đề nổ tiện lợi, vận hành bền bỉ cho công trình và hộ kinh doanh.', '{\"Công suất\":\"6 KW\",\"Nhiên liệu\":\"Xăng\",\"Khởi động\":\"Đề nổ / Giật\",\"Ổn áp\":\"AVR\",\"Điện áp\":\"220V\",\"Trọng lượng\":\"86 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(3, 'Máy bơm nước Honda WB30XT (3 inch)', 'may-bom-nuoc-honda-wb30xt', 'MBN-WB30', 2, 1, 7900000, 7400000, 40, 'may-bom-nuoc.svg', 'Máy bơm nước chạy xăng họng 3 inch, lưu lượng lớn, dùng tưới tiêu, công trình.', 'Máy bơm nước Honda WB30XT họng xả 3 inch (76mm), động cơ Honda GX160. Lưu lượng bơm lớn, hút sâu, phù hợp tưới tiêu nông nghiệp, thoát nước công trình, cứu hỏa cục bộ.', '{\"Họng bơm\":\"3 inch (76mm)\",\"Động cơ\":\"Honda GX160\",\"Lưu lượng\":\"1100 lít/phút\",\"Cột áp\":\"26 m\",\"Trọng lượng\":\"25 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(4, 'Máy bơm chìm nước thải Tsurumi KTZ', 'may-bom-chim-tsurumi-ktz', 'MBN-KTZ', 2, 2, 9200000, NULL, 18, 'may-bom-nuoc.svg', 'Máy bơm chìm hút nước thải, bùn loãng, dùng cho công trình và hầm chứa.', 'Bơm chìm chuyên hút nước thải, nước bẩn lẫn bùn loãng tại công trình xây dựng, hầm móng. Vỏ gang chắc chắn, cánh bơm chịu mài mòn, vận hành liên tục ổn định.', '{\"Công suất\":\"1.5 KW\",\"Cấp điện\":\"380V\",\"Lưu lượng\":\"400 lít/phút\",\"Cột áp\":\"18 m\",\"Cỡ họng\":\"50mm\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(5, 'Máy hàn điện tử Jasic ARC-250', 'may-han-dien-tu-jasic-arc250', 'MH-ARC250', 3, 6, 3200000, 2890000, 55, 'may-han.svg', 'Máy hàn que inverter 250A, nhỏ gọn, mối hàn đẹp, tiết kiệm điện.', 'Máy hàn điện tử Jasic ARC-250 công nghệ inverter, dòng hàn tối đa 250A. Khởi động hồ quang mượt, mối hàn chắc đẹp, trọng lượng nhẹ dễ di chuyển. Phù hợp thợ cơ khí và công trình.', '{\"Dòng hàn\":\"20 - 250A\",\"Điện áp\":\"220V\",\"Que hàn\":\"2.5 - 4.0mm\",\"Công nghệ\":\"Inverter IGBT\",\"Trọng lượng\":\"5.5 kg\"}', 1, 1, 1, '2026-06-07 14:55:47', '2026-06-07 15:04:57'),
(6, 'Máy hàn Tig/Que Jasic TIG-200', 'may-han-tig-jasic-tig200', 'MH-TIG200', 3, 6, 4500000, NULL, 30, 'may-han.svg', 'Máy hàn TIG 2 chức năng Tig/Que 200A, hàn inox, sắt thép mỏng sắc nét.', 'Jasic TIG-200 hỗ trợ 2 chế độ hàn TIG và hàn que. Phù hợp hàn inox, sắt thép tấm mỏng cho mối hàn tinh xảo. Công nghệ inverter ổn định, tiết kiệm điện năng.', '{\"Chức năng\":\"TIG / MMA\",\"Dòng hàn\":\"10 - 200A\",\"Điện áp\":\"220V\",\"Công nghệ\":\"Inverter\",\"Trọng lượng\":\"7 kg\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(7, 'Máy nén khí Pegasus 2HP 50 lít', 'may-nen-khi-pegasus-2hp-50l', 'MNK-PG2HP', 4, 2, 4100000, 3790000, 22, 'may-nen-khi.svg', 'Máy nén khí dây đai 2HP bình 50 lít, dùng cho gara, tiệm sửa xe.', 'Máy nén khí Pegasus 2HP bình chứa 50 lít, đầu nén dây đai chạy êm. Cấp khí ổn định cho súng bắn ốc, súng phun sơn, bơm lốp tại gara, tiệm rửa xe.', '{\"Công suất\":\"2 HP\",\"Dung tích bình\":\"50 lít\",\"Áp suất\":\"8 bar\",\"Điện áp\":\"220V\",\"Lưu lượng\":\"230 lít/phút\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(8, 'Máy nén khí không dầu 24 lít', 'may-nen-khi-khong-dau-24l', 'MNK-24L', 4, 7, 2350000, NULL, 35, 'may-nen-khi.svg', 'Máy nén khí mini không dầu 24 lít, êm, sạch, dùng phun sơn, vệ sinh.', 'Máy nén khí không dầu dung tích 24 lít, vận hành êm và khí sạch không lẫn dầu. Phù hợp phun sơn mỹ thuật, vệ sinh máy móc, bơm hơi gia dụng.', '{\"Công suất\":\"1.5 HP\",\"Dung tích bình\":\"24 lít\",\"Áp suất\":\"8 bar\",\"Loại\":\"Không dầu\",\"Điện áp\":\"220V\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(9, 'Máy đầm cóc Mikasa MT55 (xăng)', 'may-dam-coc-mikasa-mt55', 'MDM-MT55', 5, 1, 18900000, 17500000, 9, 'may-dam.svg', 'Máy đầm cóc động cơ Honda, lực đầm mạnh, nén nền đất, móng công trình.', 'Máy đầm cóc Mikasa MT55 dùng động cơ Honda GX100, lực đầm lớn giúp nén chặt nền đất, đường, móng công trình. Thiết kế chắc chắn, vận hành ổn định nhiều giờ liên tục.', '{\"Động cơ\":\"Honda GX100\",\"Lực đầm\":\"10 KN\",\"Tần số\":\"650 lần/phút\",\"Nhiên liệu\":\"Xăng\",\"Trọng lượng\":\"55 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(10, 'Máy đầm bàn Honda GX160', 'may-dam-ban-honda-gx160', 'MDM-GX160', 5, 1, 15400000, NULL, 11, 'may-dam.svg', 'Máy đầm bàn động cơ Honda, nén bề mặt phẳng, gạch block, sân bãi.', 'Máy đầm bàn sử dụng động cơ Honda GX160, thích hợp nén bề mặt phẳng, lát gạch block, sân bãi, vỉa hè. Bản đế rộng cho hiệu quả nén cao.', '{\"Động cơ\":\"Honda GX160\",\"Lực ly tâm\":\"15 KN\",\"Kích thước bàn\":\"500 x 400mm\",\"Nhiên liệu\":\"Xăng\",\"Trọng lượng\":\"88 kg\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(11, 'Máy xịt rửa cao áp Bosch GHP 180', 'may-xit-rua-bosch-ghp180', 'MXR-GHP180', 6, 8, 3650000, 3290000, 28, 'may-xit-rua.svg', 'Máy rửa xe áp lực cao 2000W, áp lực 130 bar, rửa xe, sân, tường.', 'Máy xịt rửa cao áp Bosch GHP 180 công suất 2000W, áp lực tối đa 130 bar. Tia nước mạnh làm sạch nhanh xe cộ, sân vườn, tường rào, máy móc công trình.', '{\"Công suất\":\"2000W\",\"Áp lực\":\"130 bar\",\"Lưu lượng\":\"420 lít/giờ\",\"Điện áp\":\"220V\",\"Trọng lượng\":\"12 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(12, 'Máy rửa xe cao áp Lutian 3KW', 'may-rua-xe-lutian-3kw', 'MXR-LT3KW', 6, 4, 4900000, NULL, 16, 'may-xit-rua.svg', 'Máy rửa xe chuyên nghiệp 3KW dùng mô tơ dây đồng, bền cho tiệm rửa xe.', 'Máy rửa xe cao áp Lutian 3KW mô tơ dây đồng nguyên chất, chạy bền liên tục cho tiệm rửa xe chuyên nghiệp. Áp lực mạnh, đầu bơm gốm chống mài mòn.', '{\"Công suất\":\"3 KW\",\"Áp lực\":\"180 bar\",\"Mô tơ\":\"Dây đồng\",\"Điện áp\":\"220V\",\"Trọng lượng\":\"28 kg\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(13, 'Máy cắt sắt bàn Makita 2414NB', 'may-cat-sat-makita-2414nb', 'MCT-2414', 7, 7, 3850000, 3590000, 20, 'may-cat-may-khoan.svg', 'Máy cắt sắt để bàn Makita lưỡi 355mm, cắt sắt hộp, thép cây nhanh gọn.', 'Máy cắt sắt bàn Makita 2414NB dùng lưỡi cắt 355mm, công suất mạnh cắt ngọt sắt hộp, thép cây, ống thép. Bệ kẹp chắc chắn, an toàn cho xưởng cơ khí.', '{\"Công suất\":\"2000W\",\"Đường kính lưỡi\":\"355mm\",\"Tốc độ\":\"3800 vòng/phút\",\"Điện áp\":\"220V\",\"Trọng lượng\":\"17 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(14, 'Máy khoan bê tông Bosch GBH 2-26', 'may-khoan-be-tong-bosch-gbh226', 'MKH-GBH226', 7, 8, 2790000, NULL, 33, 'may-cat-may-khoan.svg', 'Máy khoan búa Bosch 800W, khoan bê tông, đục phá nhẹ, đa năng.', 'Máy khoan bê tông Bosch GBH 2-26 công suất 800W, 3 chế độ khoan - khoan búa - đục. Lực đập mạnh khoan bê tông dễ dàng, độ bền cao cho thợ xây dựng.', '{\"Công suất\":\"800W\",\"Lực đập\":\"2.7 J\",\"Đường kính khoan\":\"26mm\",\"Chế độ\":\"Khoan / Búa / Đục\",\"Trọng lượng\":\"2.8 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(15, 'Máy trộn bê tông tự do 350 lít', 'may-tron-be-tong-350l', 'MTB-350L', 8, 2, 9800000, 8990000, 7, 'may-tron-be-tong.svg', 'Máy trộn bê tông quả lê 350 lít, mô tơ 2.2KW, trộn vữa, bê tông công trình.', 'Máy trộn bê tông tự do dung tích 350 lít, mô tơ 2.2KW khỏe, trộn đều bê tông và vữa xây. Khung thép chắc chắn, bánh xe di chuyển linh hoạt trong công trình.', '{\"Dung tích\":\"350 lít\",\"Mô tơ\":\"2.2 KW\",\"Điện áp\":\"220V\",\"Năng suất\":\"6 m³/giờ\",\"Trọng lượng\":\"180 kg\"}', 1, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47'),
(16, 'Máy trộn vữa cầm tay 1600W', 'may-tron-vua-cam-tay-1600w', 'MTB-1600W', 8, 7, 1650000, 1490000, 42, 'may-tron-be-tong.svg', 'Máy trộn vữa, sơn, keo cầm tay 1600W 2 tốc độ, gọn nhẹ tiện dụng.', 'Máy trộn vữa cầm tay 1600W với 2 cấp tốc độ, trộn nhanh vữa, sơn, keo, bột bả. Tay cầm chống rung, nhẹ và dễ thao tác cho thợ hoàn thiện.', '{\"Công suất\":\"1600W\",\"Tốc độ\":\"2 cấp\",\"Đường kính cây trộn\":\"140mm\",\"Điện áp\":\"220V\",\"Trọng lượng\":\"4.5 kg\"}', 0, 1, 0, '2026-06-07 14:55:47', '2026-06-07 14:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `author` varchar(120) NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `content` varchar(1000) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(80) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'site_name', 'XINMAI - Tổng kho máy xây dựng'),
(2, 'hotline', '0901 234 567'),
(3, 'hotline_north', '0936 766 266'),
(4, 'hotline_south', '0915 463 433'),
(5, 'email', 'lienhe@xinmai.vn'),
(6, 'address', 'Số 123 Đường Giải Phóng, Hoàng Mai, Hà Nội'),
(7, 'working_hours', 'Thứ 2 - Chủ nhật: 8h00 - 18h00'),
(8, 'free_ship_threshold', '5000000');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `is_active`, `created_at`) VALUES
(1, 'Quản trị viên', 'admin@xinmai.vn', '$2y$10$onDG/FrkLRnBQIy.2iuYJe4kdFsIubevr2QS.K4wGhDt6RtSERewW', '0901234567', NULL, 'admin', 1, '2026-06-07 14:55:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_brands_slug` (`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_coupons_code` (`code`);

--
-- Indexes for table `import_receipts`
--
ALTER TABLE `import_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_import_receipts_code` (`code`);

--
-- Indexes for table `import_receipt_details`
--
ALTER TABLE `import_receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ird_receipt` (`receipt_id`),
  ADD KEY `fk_ird_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_orders_code` (`code`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_coupon` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_products_slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`),
  ADD KEY `idx_products_featured` (`is_featured`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_product` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_settings_key` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `import_receipts`
--
ALTER TABLE `import_receipts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_receipt_details`
--
ALTER TABLE `import_receipt_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `import_receipt_details`
--
ALTER TABLE `import_receipt_details`
  ADD CONSTRAINT `fk_ird_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ird_receipt` FOREIGN KEY (`receipt_id`) REFERENCES `import_receipts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
