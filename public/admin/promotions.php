<?php
require_once '../../includes/controllers/PromotionController.php';
$controller = new PromotionController();
$promotions = $controller->listPromotions();
$products = $controller->getProductsForForm(); // Lấy danh sách sản phẩm cho form
include '../../View/admin/promotion_management.php';
?>