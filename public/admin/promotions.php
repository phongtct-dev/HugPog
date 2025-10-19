<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\PromotionController;

$promoController = new PromotionController();

// Xử lý POST request nếu có
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $promoController->handleAdminAction();
}

// Lấy dữ liệu cho view
$promotions = $promoController->getPromotionsViewData();
$all_products = $promoController->getProductsForForm();
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

include __DIR__ . '/../../view/admin/promotion_management.php';