<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Cấu hình và Nhúng Controller/Model
require_once '../../includes/config.php';
require_once '../../includes/controllers/ProductController.php';
require_once '../../includes/controllers/CategoryController.php';

// 2. KHỞI TẠO CONTROLLER VÀ THIẾT LẬP DỮ LIỆU
$productController = new ProductController();
$categoryController = new CategoryController();

// Lấy danh sách sản phẩm và danh mục
$products = $productController->getProductsForHomePage();
$categories = $categoryController->listCategories();

// Định nghĩa thư mục lưu trữ file ảnh (Giữ nguyên logic này ở View để đơn giản hóa)
// Lấy và xóa thông báo sau khi hiển thị (PRG Pattern)
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action_type = $_POST['action_type'] ?? '';

    // --- XỬ LÝ THÊM / SỬA SẢN PHẨM ---
    if ($action_type === 'add' || $action_type === 'update') {

        // Nếu người dùng nhập link ảnh mới, lấy link đó
        $image_url = $_POST['image_url'] ?? '';

        // Nếu đang sửa mà không nhập link ảnh mới -> giữ link cũ
        if (empty($image_url)) {
            $image_url = $_POST['existingProductImage'] ?? '';
        }

        // Gán image_url vào POST để Controller nhận
        $_POST['image_url'] = trim($image_url);

        // Gọi Controller để thực hiện lưu (Create/Update)
        $productController->handleSaveProduct();

    } elseif ($action_type === 'delete') {

        // Gọi Controller để thực hiện xóa
        $productController->handleDeleteProduct();
    }
}
include '../../View/admin/product_management.php';
?>