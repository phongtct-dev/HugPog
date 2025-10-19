<?php
// Nạp autoloader và config
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// Khai báo lớp sẽ sử dụng
use App\Controllers\UserController;

$userController = new UserController();

// Xử lý logic đăng ký
$errors = $userController->handleRegister();

// Lấy dữ liệu cũ để điền lại vào form nếu có lỗi
$old_data = [
    'full_name' => $_POST['full_name'] ?? '',
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
];

// Chuẩn bị dữ liệu cho header
$cart_qty = 0;
$categories = [];
$is_logged_in = false;
$logged_in_username = 'Khách';

// Lấy thông báo lỗi/thành công (nếu có)
$error_message = !empty($errors) ? implode('<br>', $errors) : '';
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Hiển thị view
include __DIR__ . '/../view/user/register.php';