-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2025 lúc 10:54 PM
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

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(13, 12, 1, 2, '2025-10-06 02:01:33'),
(142, 20, 17, 1, '2025-10-14 00:23:29'),
(152, 19, 5, 1, '2025-10-14 01:40:00'),
(158, 18, 5, 12, '2025-10-14 03:33:28'),
(169, 18, 17, 1, '2025-10-14 03:33:49'),
(171, 18, 1, 1, '2025-10-14 03:33:54'),
(172, 18, 3, 1, '2025-10-14 03:33:58');

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
(9, 'Hub & Cáp chuyển đổi', 'Các loại hub USB-C, cáp chuyển HDMI, VGA.'),
(15, 'VHU', 'Văn Hiến');

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
  `status` enum('Chờ Xác nhận','Đã Xác nhận','Đang giao','Đã giao','Thành công','Đã hủy') NOT NULL DEFAULT 'Chờ Xác nhận',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `shipping_address`, `phone`, `voucher_id`, `voucher_code`, `discount_amount`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn An', '123 Đường A, Quận 1, TP.HCM', '0901112221', NULL, NULL, 0.00, 1040000.00, 'Thành công', '2025-02-03 01:10:54', '2025-10-03 04:01:02'),
(2, 2, 'Trần Văn Bình', '456 Đường B, Quận 3, TP.HCM', '0901112222', NULL, NULL, 0.00, 6990000.00, 'Thành công', '2025-03-03 01:10:54', '2025-10-03 04:01:02'),
(3, 3, 'Lê Thị Cường', '789 Đường C, Quận 5, TP.HCM', '0901112223', NULL, NULL, 0.00, 4780000.00, 'Thành công', '2025-04-03 01:10:54', '2025-10-03 04:01:02'),
(4, 4, 'Phạm Văn Dũng', '101 Đường D, TP. Thủ Đức', '0901112224', NULL, NULL, 0.00, 550000.00, 'Thành công', '2025-05-03 01:10:54', '2025-10-03 04:01:02'),
(5, 5, 'Hoàng Thị E', '212 Đường E, Quận 10, TP.HCM', '0901112225', NULL, NULL, 0.00, 5080000.00, 'Thành công', '2025-06-03 01:10:54', '2025-10-03 04:01:02'),
(6, 2, 'Bình Trần', '456 Đường B, Quận 3, TP.HCM', '0901112222', NULL, NULL, 0.00, 750000.00, 'Thành công', '2025-07-03 01:10:54', '2025-10-03 04:01:02'),
(7, 1, 'An Nguyễn', '123 Đường A, Quận 1, TP.HCM', '0901112221', NULL, NULL, 0.00, 2490000.00, 'Thành công', '2025-08-03 01:10:54', '2025-10-03 04:01:02'),
(8, 7, 'Đỗ Thị H', '333 Đường H, Quận Gò Vấp', '0901112227', NULL, NULL, 0.00, 350000.00, 'Thành công', '2025-09-03 01:10:54', '2025-10-03 04:01:02'),
(9, 8, 'Ngô Thị K', '444 Đường K, Quận Bình Thạnh', '0901112228', NULL, NULL, 0.00, 890000.00, 'Thành công', '2025-10-03 01:10:54', '2025-10-03 04:01:02'),
(10, 10, 'a', 'Thành Phố Hồ Chí Minh', '0384222348', NULL, NULL, 0.00, 6990000.00, 'Thành công', '2025-10-03 04:10:53', '2025-10-03 04:11:32'),
(12, 11, 'Nguyễn Hùng', '123 Đường ABC, Phường XYZ, Quận 1', '083xxxxxxxxx', NULL, NULL, 0.00, 6990000.00, 'Chờ Xác nhận', '2025-10-07 22:17:40', '2025-10-07 22:17:40'),
(13, 11, 'A', 'A', '083xxxxxxxxx', NULL, NULL, 0.00, 6990000.00, 'Chờ Xác nhận', '2025-10-07 22:19:33', '2025-10-07 22:19:33'),
(16, 11, 'Phong', 'thầy Khải', '083xxxxxxxxx', 1, 'SALE50K', 50000.00, 0.00, 'Chờ Xác nhận', '2025-10-07 22:42:44', '2025-10-07 22:42:44'),
(17, 11, 'Nguyễn Hùng', '123 Đường ABC, Phường XYZ, Quận 1', '083xxxxxxxxx', 1, 'SALE50K', 50000.00, 6940000.00, 'Chờ Xác nhận', '2025-10-07 22:47:53', '2025-10-07 22:47:53'),
(18, 11, 'Nguyễn Hùng', '123 Đường ABC, Phường XYZ, Quận 1', '083xxxxxxxxx', 1, 'SALE50K', 50000.00, 650000.00, 'Thành công', '2025-10-08 01:13:32', '2025-10-08 14:12:43'),
(19, 11, 'Nguyễn Hùng', '123 Đường ABC, Phường XYZ, Quận 1', '083xxxxxxxxx', 5, 'SALE10%', 10.00, 476990.00, 'Thành công', '2025-10-08 15:43:24', '2025-10-08 15:43:51'),
(20, 11, 'Nguyễn Hùng', '123 Đường ABC, Phường XYZ, Quận 1', '083xxxxxxxxx', 5, 'SALE10%', 10.00, 476990.00, 'Thành công', '2025-10-08 17:51:33', '2025-10-08 17:52:00'),
(21, 15, 'En', 'Phôn Lòng', '111111333432413', 5, 'SALE10%', 10.00, 476990.00, 'Đã hủy', '2025-10-08 18:26:17', '2025-10-08 18:26:45'),
(22, 15, 'En', 'Phôn Lòng', '98652232231', 2, 'BLACKFRIDAY', 200000.00, 277000.00, 'Thành công', '2025-10-08 18:27:36', '2025-10-08 18:28:02'),
(23, 15, 'En', '11111', '412321421', NULL, NULL, 0.00, 6990000.00, 'Thành công', '2025-10-08 18:29:10', '2025-10-08 18:29:22'),
(24, 15, 'En', '123 Đường ABC, Phường XYZ, Quận 1', '111111333432413', 6, 'NEWTEST', 10.00, 7339990.00, 'Thành công', '2025-10-08 19:02:37', '2025-10-08 19:02:51'),
(25, 17, 'T', 'adudu', '0384222348', NULL, NULL, 0.00, 350000.00, 'Chờ Xác nhận', '2025-10-12 19:04:21', '2025-10-12 19:04:21'),
(26, 17, 'T', 'ssga', '25855666', NULL, NULL, 0.00, 1500000.00, 'Chờ Xác nhận', '2025-10-12 19:11:18', '2025-10-12 19:11:18'),
(27, 17, 'T', 'Thành Phố Hồ Chí Minh', '0384222348', 7, 'VNFREE', 2.00, 476998.00, 'Đã giao', '2025-10-13 01:33:45', '2025-10-13 22:55:02'),
(28, 16, 'Q', 'Thành Phố Hồ Chí Minh', '0384222348', NULL, NULL, 0.00, 2490000.00, 'Đã giao', '2025-10-13 07:43:16', '2025-10-13 07:44:06'),
(29, 20, 'TRE', 'B', '0505979797', 7, 'VNFREE', 2.00, 953998.00, 'Chờ Xác nhận', '2025-10-13 23:51:10', '2025-10-13 23:51:10'),
(30, 16, 'Q', 'Alien', '0384222348', NULL, NULL, 0.00, 4236000.00, 'Chờ Xác nhận', '2025-10-14 00:46:25', '2025-10-14 00:46:25'),
(31, 19, 'Ptest', 'CamPuCHia', '0707060606', NULL, NULL, 0.00, 27960000.00, 'Chờ Xác nhận', '2025-10-14 01:04:42', '2025-10-14 01:04:42'),
(32, 18, 'Hung z', 'CamPuChia', '0909080800', NULL, NULL, 0.00, 3894000.00, 'Chờ Xác nhận', '2025-10-14 01:33:52', '2025-10-14 01:33:52'),
(33, 19, 'Ptest', 'CamPuCHia', '0707060606', NULL, NULL, 0.00, 2540000.00, 'Chờ Xác nhận', '2025-10-14 01:37:25', '2025-10-14 01:37:25'),
(34, 19, 'Ptest', 'CamPuCHia', '0707060606', NULL, NULL, 0.00, 615000.00, 'Chờ Xác nhận', '2025-10-14 01:38:08', '2025-10-14 01:38:08'),
(35, 18, 'Hung z', 'helu', '03887785', NULL, NULL, 0.00, 965000.00, 'Chờ Xác nhận', '2025-10-14 01:41:12', '2025-10-14 01:41:12'),
(36, 18, 'Hung z', 'kakak', '0492616296', 8, '78VIP', 60.00, 8366940.00, 'Chờ Xác nhận', '2025-10-14 01:42:23', '2025-10-14 01:42:23');

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
(12, 9, 3, 1, 890000.00),
(13, 10, 1, 1, 6990000.00),
(15, 12, 1, 1, 6990000.00),
(16, 13, 1, 1, 6990000.00),
(19, 16, 1, 1, 6990000.00),
(20, 17, 1, 1, 6990000.00),
(21, 18, 2, 2, 350000.00),
(22, 19, 17, 1, 477000.00),
(23, 20, 17, 1, 477000.00),
(24, 21, 17, 1, 477000.00),
(25, 22, 17, 1, 477000.00),
(26, 23, 1, 1, 6990000.00),
(27, 24, 1, 1, 6990000.00),
(28, 24, 2, 1, 350000.00),
(29, 25, 2, 1, 350000.00),
(30, 26, 7, 2, 750000.00),
(31, 27, 17, 1, 477000.00),
(32, 28, 8, 1, 2490000.00),
(33, 29, 17, 2, 477000.00),
(34, 30, 17, 3, 477000.00),
(35, 30, 19, 1, 615000.00),
(36, 30, 4, 1, 2190000.00),
(37, 31, 1, 4, 6990000.00),
(38, 32, 17, 2, 477000.00),
(39, 32, 5, 1, 2590000.00),
(40, 32, 2, 1, 350000.00),
(41, 33, 2, 1, 350000.00),
(42, 33, 4, 1, 2190000.00),
(43, 34, 19, 1, 615000.00),
(44, 35, 19, 1, 615000.00),
(45, 35, 2, 1, 350000.00),
(46, 36, 17, 1, 477000.00),
(47, 36, 2, 1, 350000.00),
(48, 36, 1, 1, 6990000.00),
(49, 36, 6, 1, 550000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(8, 'nqhung101203@gmail.com', '930c7b823200e58b79b1ee0d3e116dc9f08988b12f14a9ed0509dcff80431c68', '2025-10-13 21:03:19', '2025-10-13 13:03:19');

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
(1, 1, 'Tai nghe Sony WH-1000XM5', 'Sony', 'Tai nghe chống ồn chủ động không dây hàng đầu.', 6990000.00, 50, 'http://www.maccenter.vn/Headphone/Sony-1000XM5-Black-A.jpg', 'active', '2025-10-03 01:10:54'),
(2, 2, 'Củ sạc Anker PowerPort III 20W', 'Anker', 'Sạc nhanh Power Delivery 20W cho iPhone.', 350000.00, 200, 'https://cdni.dienthoaivui.com.vn/x,webp,q100/https://dashboard.dienthoaivui.com.vn/uploads/wp-content/uploads/images/wp-content_uploads_images_cu-sac-anker-power-port-iii-1c-20w-a2149-den.png', 'active', '2025-10-03 01:10:54'),
(3, 3, 'Pin sạc dự phòng Xiaomi 20000mAh Gen 3', 'Xiaomi', 'Dung lượng lớn, hỗ trợ sạc nhanh 2 chiều.', 890000.00, 150, 'https://bizweb.dktcdn.net/thumb/1024x1024/100/314/518/products/pin-sac-du-phong-20000mah-gen3.jpg?v=1577526710620', 'active', '2025-10-03 01:10:54'),
(4, 4, 'Chuột không dây Logitech MX Master 3S', 'Logitech', 'Chuột công thái học cao cấp dành cho công việc.', 2190000.00, 80, 'https://hanoicomputercdn.com/media/product/66603_chuot_khong_day_logitech_mx_master_3s_graphite_usb_bluetooth_910_006561_0002_3.jpg', 'active', '2025-10-03 01:10:54'),
(5, 5, 'Loa Bluetooth JBL Flip 6', 'JBL', 'Âm thanh mạnh mẽ, chống nước IP67.', 2590000.00, 100, 'https://thongsokythuat.vn/wp-content/uploads/Loa-bluetooth-JBL-Flip-6-2021.jpg', 'active', '2025-10-03 01:10:54'),
(6, 6, 'Ốp lưng Spigen Ultra Hybrid cho iPhone 15', 'Spigen', 'Chống sốc, trong suốt không ố vàng.', 550000.00, 300, 'https://cdni.dienthoaivui.com.vn/x,webp,q100/https://dashboard.dienthoaivui.com.vn/uploads/wp-content/uploads/images/op-lung-iphone-15-spigen-ultra-hybrid-1.jpg', 'active', '2025-10-03 01:10:54'),
(7, 7, 'Thẻ nhớ SanDisk Extreme Pro 128GB', 'SanDisk', 'Tốc độ đọc ghi cao, chuyên dụng cho quay 4K.', 750000.00, 250, 'https://lagihitech.vn/wp-content/uploads/2023/03/the-nho-SDXC-SanDisk-Extreme-PRO-128GB-200MBs-SDSDXXD-128G-GN4IN-hinh-5.jpg', 'active', '2025-10-03 01:10:54'),
(8, 8, 'Webcam Logitech C922 Pro Stream', 'Logitech', 'Full HD 1080p, tích hợp micro, phù hợp stream game.', 2490000.00, 60, 'https://www.advancedpcbahrain.com/wp-content/uploads/2020/12/Logitech-Pro-HD-Stream-Webcam-C922-1024x1024.png', 'active', '2025-10-03 01:10:54'),
(9, 9, 'Hub chuyển đa năng Baseus 8-in-1', 'Baseus', 'Hub USB-C ra HDMI 4K, USB 3.0, LAN, sạc PD.', 1250000.00, 120, 'https://product.hstatic.net/1000152881/product/6_be5d8ab386e04622a8e3a0b60be56f63_1024x1024.jpg', 'inactive', '2025-10-03 01:10:54'),
(17, 1, 'Tai nghe gaming Sony MDR - ZX110AP có mic', 'Sony', 'Là một trong những mẫu tai nghe chụp tai có mic gọn nhẹ bậc nhất trên thị trường, Sony MDR-ZX110AP được sáng tạo dành cho những ai yêu trải nghiệm âm nhạc và thường xuyên dịch chuyển. Với thiết kế headband có thể nới rộng hoặc thu gọn tùy thích, bạn sẽ thoải mái mang theo sản phẩm trong những chuyến đi xa.', 530000.00, 20, 'https://cdn2.fptshop.com.vn/unsafe/2021_10_11_637695674664494745_MDR-ZX110APBCE-01.jpg', 'active', '2025-10-08 15:15:53'),
(19, 15, 'Test', 'Adubai', 'đẹp', 1230000.00, 5, 'https://tse4.mm.bing.net/th/id/OIP.GvubTY04gtPcPgyk3Q6HAAHaFj?cb=12&rs=1&pid=ImgDetMain&o=7&rm=3', 'active', '2025-10-13 10:00:46');

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
(3, 9, 15.00, '2025-10-12 00:00:00', '2025-12-21 00:00:00', 1),
(4, 17, 10.00, '2025-10-08 00:00:00', '2025-10-22 00:00:00', 1),
(6, 19, 50.00, '2025-10-13 00:00:00', '2025-10-22 00:00:00', 1),
(7, 5, 97.00, '2025-10-13 00:00:00', '2025-12-03 00:00:00', 1);

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
(5, 3, 5, 4, 'Loa nghe to, rõ, bass mạnh. Chống nước tốt, mang đi bơi được.', NULL, 'visible', '2025-05-03 01:10:54'),
(6, 2, 7, 5, 'Thẻ nhớ tốc độ cao, chép file 4K nhanh chóng.', NULL, 'visible', '2025-08-03 01:10:54'),
(7, 1, 8, 3, 'Webcam hình ảnh ổn, không quá xuất sắc trong điều kiện thiếu sáng.', NULL, 'visible', '2025-09-03 01:10:54'),
(8, 5, 5, 5, 'Mua cái thứ 2, loa JBL vẫn đỉnh như ngày nào.', NULL, 'hidden', '2025-10-03 01:10:54'),
(9, 5, 4, 4, 'Chuột làm việc tốt, pin trâu.', NULL, 'hidden', '2025-10-03 01:10:54'),
(10, 11, 2, 5, '123', NULL, 'visible', '2025-10-08 14:38:25'),
(11, 11, 17, 3, 'HUM', NULL, 'hidden', '2025-10-08 15:44:15'),
(12, 11, 17, 4, 'cũng được', NULL, 'visible', '2025-10-08 17:52:26'),
(13, 15, 17, 4, 'dùng như c', NULL, 'visible', '2025-10-08 18:29:52');

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
(1, 'admin', 'admin@example.com', '$2y$10$JQ893tO5qGUGyOE/gSxlR.GDwJEVGORdBsNWzv6cuNIzx.pqHgN6i', 'Quản Trị Viên', 'admin', 'active', '2025-10-03 01:05:00'),
(7, 'h@gmail.com', 'h@gmail.com', '$2y$10$aQyU0LDsJkmIKI1s5yRwmuEB8e1izykQQFjm4rRu98S7H8wGxOzhW', 'Hung', 'employee', 'active', '2025-10-05 02:08:29'),
(8, 'user1@example.com', 'user1@example.com', '$2y$10$T4pp2YZOQ11j3ruArdhom.kgNTXD60MA/Z1x/uClmJEnsvyOwYQge', 'Hung Dep trai', 'employee', 'locked', '2025-10-06 08:23:54'),
(10, 'phongl', 'phong@gmail.com', '$2y$10$1mn1vDiGaU5gEPzgSRVCquUcsd05jTZ0n8W7.Oj1pG1keowAgKzcq', 'Phong', 'employee', 'active', '2025-10-08 18:31:51');

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
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `rank` enum('silver','gold','diamond') DEFAULT 'silver',
  `total_spent` decimal(15,2) DEFAULT 0.00,
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `phone`, `address`, `birth_date`, `rank`, `total_spent`, `status`, `created_at`) VALUES
(1, 'nguyenvana', 'nguyenvana@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Nguyễn Văn An', '0901112221', NULL, NULL, 'silver', 1500000.00, 'active', '2025-01-03 01:10:54'),
(2, 'tranvanbinh', 'tranvanbinh@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Trần Văn Bình', '0901112222', NULL, NULL, 'gold', 6500000.00, 'active', '2025-02-03 01:10:54'),
(3, 'lethicuong', 'lethicuong@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Lê Thị Cường', '0901112223', NULL, NULL, 'diamond', 12000000.00, 'active', '2025-03-03 01:10:54'),
(4, 'phamvand', 'phamvand@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Phạm Văn Dũng', '0901112224', NULL, NULL, 'silver', 500000.00, 'active', '2025-04-03 01:10:54'),
(5, 'hoangthie', 'hoangthie@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Hoàng Thị E', '0901112225', NULL, NULL, 'gold', 8000000.00, 'active', '2025-05-03 01:10:54'),
(6, 'vovang', 'vovang@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Võ Văn G', '0901112226', NULL, NULL, '', 0.00, 'active', '2025-06-03 01:10:54'),
(7, 'dothih', 'dothih@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Đỗ Thị H', '0901112227', NULL, NULL, 'silver', 250000.00, 'active', '2025-07-03 01:10:54'),
(8, 'ngothik', 'ngothik@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Ngô Thị K', '0901112228', NULL, NULL, 'silver', 0.00, 'active', '2025-08-03 01:10:54'),
(9, 'trinhvanl', 'trinhvanl@email.com', '$2y$10$DnqUFgr4Hu3vftnkSqteQuTRyP/YWk768XgYmwh5ekPUdayPof9Iy', 'Trịnh Văn L', '0901112229', NULL, NULL, 'silver', 990000.00, 'active', '2025-09-03 01:10:54'),
(10, 'Văn A', 'a@gmail.com', '$2y$10$kogPFHCN26yrj3YS3D7TDOCgbIc6kaDhYvDMCpiRuQ07N7lnp3rq2', 'tranphong', '', NULL, NULL, 'diamond', 6990000.00, 'active', '2025-10-03 04:09:49'),
(11, 'hung', 'h@gmail.com', '$2y$10$Le1QyHONU2rnOtIhce6HfOPLMB0BkXvDfQnSjgfH1H2v8HJC1498W', 'Nguyễn Hùng', '083xxxxxxxxx', NULL, NULL, 'silver', 1603980.00, 'active', '2025-10-05 12:31:01'),
(12, 'hung1', 'p@gmail.com', '$2y$10$4ZAH0aNPQbBrkbGXHwOPl.keEHflEkS/YnaxC4S9SGmZVu3ppcIla', 'Nguyễn Hùng', '213123123213', NULL, NULL, 'silver', 0.00, 'locked', '2025-10-05 23:42:01'),
(13, 'phong', 'k@gmail.com', '$2y$10$1keuyQyrnKREGVMOSQjD9.zOEctmXtlWH3yCJ0bLNaYHW/rKvPuGq', 'Phong', '4124124141', NULL, NULL, 'diamond', 0.00, 'locked', '2025-10-08 15:25:56'),
(14, 'dat', 'd@gmail.com', '$2y$10$Oxzzgg2c0usX7DhlYsKdiumdlCM5rtDBcCQTmgfIdxWFOeIPdJsii', 'Nguyễn Đạt', NULL, NULL, NULL, 'silver', 0.00, 'active', '2025-10-08 15:50:58'),
(15, 'en', 'en@gmail.com', '$2y$10$Pnlp6a2EMaeBye9RFF1qh.zExXbrTjR8klf2.htISCgvi03zEhdS2', 'En', NULL, NULL, NULL, 'diamond', 14606990.00, 'active', '2025-10-08 18:23:51'),
(16, 'QN', 'ab@gmail.com', '$2y$10$I3hlQAi4Rwwa2t3j/UTV4ONM/voCDvuw0N2jNgsPPN9V/7X/x8FGa', 'Q', '0384222348', 'Alien', NULL, 'silver', 0.00, 'active', '2025-10-11 02:19:25'),
(17, 'T', 'T@gmail.com', '$2y$10$Jxnucn0rHhvE77m/1y3oZe7umxdJUs25VJQihBfvFU5VYhB04hW2m', 'T', NULL, NULL, NULL, 'silver', 0.00, 'active', '2025-10-12 18:11:20'),
(18, 'Hung z', 'nqhung101203@gmail.com', '$2y$10$pCBIrF4f62q1IE9gWxCyx.F6yjCn4ZnFtlv9DEMT4Yas88Pq7DfbW', 'Hung z', NULL, NULL, NULL, 'silver', 0.00, 'active', '2025-10-13 18:37:51'),
(19, 'Ptest', 'trantranpoala@gmail.com', '$2y$10$3ZCEhDDYh7uWbn59ry4k2e1ijVmHCcME9Ai9MppXnbYIT8xz.e6Cu', 'Ptest', '0707060606', 'CamPuCHia', NULL, 'silver', 0.00, 'active', '2025-10-13 18:59:11'),
(20, 'TRU', 'Phong221A290024@st.vhu.edu.vn', '$2y$10$XyCqS8fKeWRcFrjjwzIoSe5U8p7hmqOKrvB.ZY3C/euIAcbAf4Fge', 'TRE', '0505979797', 'B', NULL, 'silver', 0.00, 'active', '2025-10-13 22:39:43'),
(21, 'Te', 'huhuhu@gmail.com', '$2y$10$Z1MktbuDuMvlk0Fo4I1u7.WG0AAqP60HQ08er2kkv88avRu2mq6oG', 'Te', '090938432424', 'Lala Nunu', NULL, 'silver', 0.00, 'active', '2025-10-14 03:10:06');

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
(1, 'SALE50K', 50000.00, 96, '2025-11-03 01:10:54', 'active', 1),
(2, 'BLACKFRIDAY', 200000.00, 48, '2025-12-03 01:10:54', 'active', 1),
(3, 'EXPIRED123', 10000.00, 0, '2025-10-02 01:10:54', 'active', 1),
(4, 'AAAA', 100000.00, 12, '2025-12-02 12:00:00', 'expired', 1),
(5, 'SALE10%', 10.00, 7, '2025-10-29 15:36:00', 'active', 1),
(6, 'NEWTEST', 10.00, 1, '2025-10-09 19:00:00', 'inactive', 1),
(7, 'VNFREE', 2.00, 0, '2025-10-14 15:06:00', 'active', 1),
(8, '78VIP', 60.00, 1, '2025-10-29 22:42:00', 'active', 1);

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
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `staff_accounts`
--
ALTER TABLE `staff_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
