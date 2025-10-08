<?php
// Tệp: promotion_management.php (View RẤT MỎNG)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../includes/controllers/PromotionController.php';

$promoController = new PromotionController();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controller xử lý toàn bộ logic, lưu session và tự redirect/exit.
    $promoController->handleAdminAction();
}


$promotions = $promoController->getPromotionsViewData();
$all_products = $promoController->getProductsForForm();

// Lấy thông báo từ session
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}
include '../../View/admin/promotion_management.php';
?>