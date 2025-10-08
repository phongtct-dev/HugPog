<?php
// Tệp: user_management.php (Dynamic Web Page - View)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../includes/controllers/UserController.php'; 

$userController = new UserController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userController->handleAdminUserAction(); 
}

$users = $userController->listUsersForAdmin();

// Lấy thông báo từ session
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

function formatCurrency($amount)
{
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

function displayRankBadge($rank)
{
    $rank = strtolower($rank);
    $text = ucfirst($rank);
    $class = 'bg-secondary';
    switch ($rank) {
        case 'diamond':
            $class = 'bg-primary';
            $text = 'Kim cương';
            break;
        case 'gold':
            $class = 'bg-warning text-dark';
            $text = 'Vàng';
            break;
        case 'silver':
            $class = 'bg-info';
            $text = 'Bạc';
            break;
    }
    return '<span class="badge ' . $class . '">' . $text . '</span>';
}

function displayStatusBadge($status)
{
    $status = strtolower($status);
    $text = $status === 'active' ? 'Hoạt động' : 'Khóa';
    $class = $status === 'active' ? 'bg-success' : 'bg-danger';
    return '<span class="badge ' . $class . '">' . $text . '</span>';
}
include '../../View/admin/user_management.php';
?>