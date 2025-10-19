<?php
// File: project/includes/helpers/admin_auth.php

function require_admin_login() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Nếu không có session staff_id, chuyển hướng về trang đăng nhập
    if (!isset($_SESSION['staff_id'])) {
        // Cần nạp config để có BASE_URL
        require_once __DIR__ . '/../config.php';
        header('Location: ' . BASE_URL . 'public/admin/index.php');
        exit();
    }
}