<?php
// File: project/includes/db_connect.php

// Nhúng file cấu hình vào để lấy thông tin DB
require_once 'config.php';

/**
 * Hàm này tạo và trả về một đối tượng kết nối MySQLi.
 * Nó sẽ tự động lấy thông tin từ file config.php.
 * @return mysqli Đối tượng kết nối
 */
function db_connect() {
    // Tạo kết nối mới
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Kiểm tra nếu có lỗi kết nối thì dừng chương trình và báo lỗi
    if ($conn->connect_error) {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    // Thiết lập bảng mã UTF-8 để hiển thị tiếng Việt chính xác
    $conn->set_charset("utf8mb4");

    // Trả về kết nối
    return $conn;
}
?>