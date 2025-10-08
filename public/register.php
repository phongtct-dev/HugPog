<?php
// File: register.php

// Khởi động session để lưu thông báo thành công (nếu có)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '../../includes/controllers/UserController.php';

// Khởi tạo Controller
$userController = new UserController();

// Gọi hàm xử lý đăng ký, hàm này sẽ trả về mảng lỗi (trống nếu thành công hoặc POST chưa được gửi)
$errors = $userController->handleRegister();

// Xử lý thông báo
$error_message = '';
$success_message = '';

// Nếu có lỗi, nối các lỗi lại thành một chuỗi để hiển thị
if (!empty($errors)) {
    $error_message = implode("<br>", $errors);
}

// Lấy thông báo thành công từ SESSION (nếu có sau khi chuyển hướng)
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Xóa ngay sau khi hiển thị
}

// Giữ lại dữ liệu cũ đã nhập (trừ mật khẩu)
$old_data = [
    'full_name' => $_POST['full_name'] ?? '',
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
];

include '../View/user/register.php';
