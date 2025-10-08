<?php
// File: project/includes/config.php

// === BẬT HIỂN THỊ LỖI ĐỂ DEBUG ===
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === CẤU HÌNH DATABASE ===
define('DB_SERVER', 'localhost');      // Tên server, thường là localhost
define('DB_USERNAME', 'root');         // Username của MySQL, mặc định là root
define('DB_PASSWORD', '17042nqH@');             // Mật khẩu của MySQL, mặc định là trống
define('DB_NAME', 'webhugpog');        // Tên database của bạn

// === CẤU HÌNH CHUNG ===
// URL gốc của website. Rất quan trọng!
// Sửa '/project/' nếu bạn đặt tên thư mục khác
define('BASE_URL', '/HugPog/');
define('VIEW_URL', 'http://localhost/HugPog/view/');

// Cài đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Bắt đầu session để lưu trữ dữ liệu người dùng (như trạng thái đăng nhập)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>