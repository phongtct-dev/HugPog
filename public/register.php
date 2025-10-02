<?php
// File: project/public/register.php

// 1. Nhúng Controller vào
require_once '../includes/controllers/UserController.php';

// 2. Tạo đối tượng từ Controller
$userController = new UserController();

// 3. Gọi hàm xử lý logic đăng ký và nhận về mảng lỗi
$errors = $userController->handleRegister();

// 4. Nhúng file View (giao diện) vào để hiển thị
// Biến $errors sẽ được sử dụng bên trong file view này
include '../View/user/register.php';
?>