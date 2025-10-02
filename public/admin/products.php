<?php
require_once '../../includes/controllers/ProductController.php';
$controller = new ProductController();
$products = $controller->listProductsForAdmin();
include '../../View/admin/product_management.php';
?>