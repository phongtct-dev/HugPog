<?php
// 1. Nạp Autoloader của Composer và file cấu hình
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';

// 2. Khai báo lớp Controller sẽ sử dụng
use App\Controllers\StaffController;

// 3. Khởi tạo Controller và gọi hàm đăng xuất
$staffController = new StaffController();
$staffController->handleLogout();

include __DIR__ . '/../../view/admin/login.php';