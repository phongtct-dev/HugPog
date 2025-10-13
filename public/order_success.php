<?php
// FILE: public/update_cart.php (PHIÊN BẢN ĐÃ SỬA)

// Nạp autoloader và config
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// Khai báo lớp sẽ sử dụng
use App\Controllers\CartController;

// Khởi tạo và gọi phương thức xử lý
$cartController = new CartController();
$cartController->handleUpdateCart();