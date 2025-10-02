-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 02, 2025 lúc 08:36 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webhugpog`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Tai Nghe', 'Các loại tai nghe có dây, không dây, chụp tai, nhét tai.'),
(2, 'Sạc & Cáp', 'Củ sạc, cáp sạc cho các thiết bị di động.'),
(3, 'Pin dự phòng', 'Pin sạc dự phòng dung lượng cao, sạc nhanh.'),
(4, 'Chuột & Bàn phím', 'Các thiết bị ngoại vi cho máy tính, laptop.'),
(5, 'Loa di động', 'Loa Bluetooth, loa thông minh.'),
(6, 'Ốp lưng & Dán màn hình', 'Phụ kiện bảo vệ cho điện thoại.'),
(7, 'Thiết bị lưu trữ', 'USB, thẻ nhớ, ổ cứng di động.'),
(8, 'Webcam & Micro', 'Thiết bị cho học tập và làm việc từ xa.'),
(9, 'Hub & Cáp chuyển đổi', 'Các loại hub USB-C, cáp chuyển HDMI, VGA.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `voucher_id` int(11) DEFAULT NULL,
  `voucher_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','delivered','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `shipping_address`, `phone`, `voucher_id`, `voucher_code`, `discount_amount`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn An', '123 Đường A, Quận 1, TP.HCM', '0901112221', NULL, NULL, 0.00, 1040000.00, 'completed', '2025-02-03 01:10:54', '2025-10-03 01:10:54'),
(2, 2, 'Trần Văn Bình', '456 Đường B, Quận 3, TP.HCM', '0901112222', NULL, NULL, 0.00, 6990000.00, 'completed', '2025-03-03 01:10:54', '2025-10-03 01:10:54'),
(3, 3, 'Lê Thị Cường', '789 Đường C, Quận 5, TP.HCM', '0901112223', NULL, NULL, 0.00, 4780000.00, 'completed', '2025-04-03 01:10:54', '2025-10-03 01:10:54'),
(4, 4, 'Phạm Văn Dũng', '101 Đường D, TP. Thủ Đức', '0901112224', NULL, NULL, 0.00, 550000.00, 'cancelled', '2025-05-03 01:10:54', '2025-10-03 01:10:54'),
(5, 5, 'Hoàng Thị E', '212 Đường E, Quận 10, TP.HCM', '0901112225', NULL, NULL, 0.00, 5080000.00, 'shipping', '2025-06-03 01:10:54', '2025-10-03 01:10:54'),
(6, 2, 'Bình Trần', '456 Đường B, Quận 3, TP.HCM', '0901112222', NULL, NULL, 0.00, 750000.00, 'delivered', '2025-07-03 01:10:54', '2025-10-03 01:10:54'),
(7, 1, 'An Nguyễn', '123 Đường A, Quận 1, TP.HCM', '0901112221', NULL, NULL, 0.00, 2490000.00, 'confirmed', '2025-08-03 01:10:54', '2025-10-03 01:10:54'),
(8, 7, 'Đỗ Thị H', '333 Đường H, Quận Gò Vấp', '0901112227', NULL, NULL, 0.00, 350000.00, 'pending', '2025-09-03 01:10:54', '2025-10-03 01:10:54'),
(9, 8, 'Ngô Thị K', '444 Đường K, Quận Bình Thạnh', '0901112228', NULL, NULL, 0.00, 890000.00, 'pending', '2025-10-03 01:10:54', '2025-10-03 01:10:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 350000.00),
(2, 1, 3, 1, 690000.00),
(3, 2, 1, 1, 6990000.00),
(4, 3, 4, 1, 2190000.00),
(5, 3, 5, 1, 2590000.00),
(6, 4, 6, 1, 550000.00),
(7, 5, 5, 1, 2590000.00),
(8, 5, 4, 1, 2490000.00),
(9, 6, 7, 1, 750000.00),
(10, 7, 8, 1, 2490000.00),
(11, 8, 2, 1, 350000.00),
(12, 9, 3, 1, 890000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `brand`, `description`, `price`, `stock`, `image_url`, `status`, `created_at`) VALUES
(1, 1, 'Tai nghe Sony WH-1000XM5', 'Sony', 'Tai nghe chống ồn chủ động không dây hàng đầu.', 6990000.00, 50, 'https://placehold.co/600x400/E9A8A9/333333?text=Sony+XM5', 'active', '2025-10-03 01:10:54'),
(2, 2, 'Củ sạc Anker PowerPort III 20W', 'Anker', 'Sạc nhanh Power Delivery 20W cho iPhone.', 350000.00, 200, 'https://placehold.co/600x400/A8D1E9/333333?text=Anker+20W', 'active', '2025-10-03 01:10:54'),
(3, 3, 'Pin sạc dự phòng Xiaomi 20000mAh Gen 3', 'Xiaomi', 'Dung lượng lớn, hỗ trợ sạc nhanh 2 chiều.', 890000.00, 150, 'https://placehold.co/600x400/CCCCCC/333333?text=Xiaomi+20k', 'active', '2025-10-03 01:10:54'),
(4, 4, 'Chuột không dây Logitech MX Master 3S', 'Logitech', 'Chuột công thái học cao cấp dành cho công việc.', 2190000.00, 80, 'https://placehold.co/600x400/F2E4A7/333333?text=Logitech+MX', 'active', '2025-10-03 01:10:54'),
(5, 5, 'Loa Bluetooth JBL Flip 6', 'JBL', 'Âm thanh mạnh mẽ, chống nước IP67.', 2590000.00, 100, 'https://placehold.co/600x400/A8E9C5/333333?text=JBL+Flip+6', 'active', '2025-10-03 01:10:54'),
(6, 6, 'Ốp lưng Spigen Ultra Hybrid cho iPhone 15', 'Spigen', 'Chống sốc, trong suốt không ố vàng.', 550000.00, 300, 'https://placehold.co/600x400/D1A8E9/333333?text=Spigen+Case', 'active', '2025-10-03 01:10:54'),
(7, 7, 'Thẻ nhớ SanDisk Extreme Pro 128GB', 'SanDisk', 'Tốc độ đọc ghi cao, chuyên dụng cho quay 4K.', 750000.00, 250, 'https://placehold.co/600x400/E9CBA8/333333?text=SanDisk+128GB', 'active', '2025-10-03 01:10:54'),
(8, 8, 'Webcam Logitech C922 Pro Stream', 'Logitech', 'Full HD 1080p, tích hợp micro, phù hợp stream game.', 2490000.00, 60, 'https://placehold.co/600x400/A8B9E9/333333?text=Logitech+C922', 'active', '2025-10-03 01:10:54'),
(9, 9, 'Hub chuyển đa năng Baseus 8-in-1', 'Baseus', 'Hub USB-C ra HDMI 4K, USB 3.0, LAN, sạc PD.', 1250000.00, 120, 'https://placehold.co/600x400/E9A8A8/333333?text=Baseus+Hub', 'inactive', '2025-10-03 01:10:54'),
(10, 2, 'a', 'b', 'c', 1.00, 1, '', 'active', '2025-10-03 01:29:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_discounts`
--

CREATE TABLE `product_discounts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_by_staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_discounts`
--

INSERT INTO `product_discounts` (`id`, `product_id`, `discount_percent`, `start_date`, `end_date`, `created_by_staff_id`) VALUES
(1, 5, 15.00, '2025-10-03 01:10:54', '2025-10-10 01:10:54', 1),
(2, 4, 10.00, '2025-09-03 01:10:54', '2025-10-02 01:10:54', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('visible','hidden') DEFAULT 'visible',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `image_url`, `status`, `created_at`) VALUES
(1, 1, 2, 5, 'Sạc rất nhanh, nhỏ gọn, hàng Anker chất lượng.', NULL, 'visible', '2025-03-03 01:10:54'),
(2, 1, 3, 4, 'Pin dùng ổn, sạc được nhiều lần, hơi nặng một chút.', NULL, 'visible', '2025-03-03 01:10:54'),
(3, 2, 1, 5, 'Chống ồn tuyệt vời, nghe nhạc rất hay. Đáng tiền!', NULL, 'visible', '2025-04-03 01:10:54'),
(4, 3, 4, 5, 'Chuột dùng rất sướng tay, con lăn siêu tốc tiện lợi.', NULL, 'visible', '2025-05-03 01:10:54'),
(5, 3, 5, 4, 'Loa nghe to, rõ, bass mạnh. Chống nước tốt, mang đi bơi được.', NULL, 'hidden', '2025-05-03 01:10:54'),
(6, 2, 7, 5, 'Thẻ nhớ tốc độ cao, chép file 4K nhanh chóng.', NULL, 'visible', '2025-08-03 01:10:54'),
(7, 1, 8, 3, 'Webcam hình ảnh ổn, không quá xuất sắc trong điều kiện thiếu sáng.', NULL, 'visible', '2025-09-03 01:10:54'),
(8, 5, 5, 5, 'Mua cái thứ 2, loa JBL vẫn đỉnh như ngày nào.', NULL, 'hidden', '2025-10-03 01:10:54'),
(9, 5, 4, 4, 'Chuột làm việc tốt, pin trâu.', NULL, 'hidden', '2025-10-03 01:10:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff_accounts`
--

CREATE TABLE `staff_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `staff_accounts`
--

INSERT INTO `staff_accounts` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$JQ893tO5qGUGyOE/gSxlR.GDwJEVGORdBsNWzv6cuNIzx.pqHgN6i', 'Quản Trị Viên', 'admin', 'active', '2025-10-03 01:05:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `rank` enum('silver','gold','diamond') DEFAULT 'silver',
  `total_spent` decimal(15,2) DEFAULT 0.00,
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `phone`, `birth_date`, `rank`, `total_spent`, `status`, `created_at`) VALUES
(1, 'nguyenvana', 'nguyenvana@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Nguyễn Văn An', '0901112221', NULL, 'silver', 1500000.00, 'active', '2025-01-03 01:10:54'),
(2, 'tranvanbinh', 'tranvanbinh@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Trần Văn Bình', '0901112222', NULL, 'gold', 6500000.00, 'active', '2025-02-03 01:10:54'),
(3, 'lethicuong', 'lethicuong@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Lê Thị Cường', '0901112223', NULL, 'diamond', 12000000.00, 'active', '2025-03-03 01:10:54'),
(4, 'phamvand', 'phamvand@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Phạm Văn Dũng', '0901112224', NULL, 'silver', 500000.00, 'active', '2025-04-03 01:10:54'),
(5, 'hoangthie', 'hoangthie@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Hoàng Thị E', '0901112225', NULL, 'gold', 8000000.00, 'active', '2025-05-03 01:10:54'),
(6, 'vovang', 'vovang@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Võ Văn G', '0901112226', NULL, '', 0.00, 'locked', '2025-06-03 01:10:54'),
(7, 'dothih', 'dothih@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Đỗ Thị H', '0901112227', NULL, 'silver', 250000.00, 'active', '2025-07-03 01:10:54'),
(8, 'ngothik', 'ngothik@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Ngô Thị K', '0901112228', NULL, 'silver', 0.00, 'active', '2025-08-03 01:10:54'),
(9, 'trinhvanl', 'trinhvanl@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Trịnh Văn L', '0901112229', NULL, 'silver', 990000.00, 'active', '2025-09-03 01:10:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `expiry_date` datetime NOT NULL,
  `status` enum('active','expired','inactive') DEFAULT 'active',
  `created_by_staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_value`, `quantity`, `expiry_date`, `status`, `created_by_staff_id`) VALUES
(1, 'SALE50K', 50000.00, 100, '2025-11-03 01:10:54', 'active', 1),
(2, 'BLACKFRIDAY', 200000.00, 50, '2025-12-03 01:10:54', 'active', 1),
(3, 'EXPIRED123', 10000.00, 0, '2025-10-02 01:10:54', 'expired', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product_unique` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `created_by_staff_id` (`created_by_staff_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `staff_accounts`
--
ALTER TABLE `staff_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `created_by_staff_id` (`created_by_staff_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `staff_accounts`
--
ALTER TABLE `staff_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD CONSTRAINT `product_discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_discounts_ibfk_2` FOREIGN KEY (`created_by_staff_id`) REFERENCES `staff_accounts` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`created_by_staff_id`) REFERENCES `staff_accounts` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
