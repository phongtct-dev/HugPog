<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\UserController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Models\UserModel;

// Người dùng phải đăng nhập mới vào được trang này
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}

/**
 * Chuyển đổi rank (silver, gold, diamond) sang tiếng Việt và trả về HTML badge.
 * @param string $rank Rank tiếng Anh
 * @return string HTML cho badge
 */
function displayRankBadge($rank) {
    $rank = strtolower($rank);
    $text = ucfirst($rank); $class = 'bg-secondary';
    switch ($rank) {
        case 'diamond': $class = 'bg-primary'; $text = 'Kim cương'; break;
        case 'gold': $class = 'bg-warning text-dark'; $text = 'Vàng'; break;
        case 'silver': $class = 'bg-info'; $text = 'Bạc'; break;
    }
    return '<span class="badge ' . $class . '">' . $text . '</span>';
}

$userController = new UserController();
$cartController = new CartController();
$categoryController = new CategoryController();
$userModel = new UserModel();

// Xử lý việc cập nhật profile nếu có POST request
$message = $userController->handleUpdateProfile();

// Lấy thông tin mới nhất của người dùng để hiển thị
$currentUser = $userModel->getUserById($_SESSION['user_id']);

// Chuẩn bị dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = true;
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Hiển thị view
include __DIR__ . '/../view/user/profile.php';