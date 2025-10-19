<?php
// FILE: public/login.php (PHIÊN BẢN ĐÃ SỬA)

// 1. Nạp Autoloader của Composer và file cấu hình
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// 2. Khai báo lớp Controller sẽ sử dụng
use App\Controllers\UserController;

// 3. Khởi tạo Controller
$userController = new UserController();

// 4. Gọi hàm xử lý đăng nhập. Nó sẽ tự chuyển hướng nếu thành công.
$errors = $userController->handleLogin();

// 5. Chuẩn bị dữ liệu để truyền cho file View
// Lấy thông báo thành công (ví dụ: từ trang đăng ký)
$success = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Lấy lỗi đầu tiên trong mảng lỗi để hiển thị
$error = empty($errors) ? '' : $errors[0];

// Chuẩn bị dữ liệu cho header (trang login không cần tính giỏ hàng, danh mục)
$cart_qty = 0;
$categories = [];
$is_logged_in = false;
$logged_in_username = 'Khách';

// 6. Gọi file View để hiển thị giao diện
include __DIR__ . '/../view/user/login.php';