<?php
// File: project/public/login.php

// 1. Nhúng Controller
require_once '../includes/controllers/UserController.php';

// 2. Tạo đối tượng Controller
$userController = new UserController();

// 3. Gọi hàm xử lý đăng nhập và nhận về mảng lỗi
$errors = $userController->handleLogin();

// 4. Nhúng View để hiển thị form
include '../view/user/login.php';
?>