<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\UserController;

$userController = new UserController();

// Xử lý POST request nếu có
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userController->handleAdminUserAction(); 
}

// Lấy dữ liệu cho view
$users = $userController->listUsersForAdmin();
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

// Các hàm helper cho view
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}
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
function displayStatusBadge($status) {
    $status = strtolower($status);
    $text = $status === 'active' ? 'Hoạt động' : 'Khóa';
    $class = $status === 'active' ? 'bg-success' : 'bg-danger';
    return '<span class="badge ' . $class . '">' . $text . '</span>';
}

include __DIR__ . '/../../view/admin/user_management.php';