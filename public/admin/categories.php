<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../includes/config.php';
require_once '../../includes/controllers/CategoryController.php'; // Sử dụng CategoryController

$categoryController = new CategoryController();

// =================================================================================
// LOGIC XỬ LÝ POST REQUEST
// =================================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action_type = $_POST['action_type'] ?? '';

    switch ($action_type) {
        case 'create':
            $categoryController->handleCreateCategory();
            break;
        case 'update':
            $categoryController->handleUpdateCategory();
            break;
        case 'delete':
            $categoryController->handleDeleteCategory();
            break;
        default:
            break;
    }
}

// Lấy danh sách danh mục để hiển thị
$categories = $categoryController->listCategories();

// Lấy và xóa thông báo sau khi hiển thị
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
include '../../View/admin/category_management.php';
?>