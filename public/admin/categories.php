<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\CategoryController;

$categoryController = new CategoryController();

// Xử lý POST request nếu có
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
    }
}

// Lấy dữ liệu cho view
$categories = $categoryController->listCategories();
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

include __DIR__ . '/../../view/admin/category_management.php';