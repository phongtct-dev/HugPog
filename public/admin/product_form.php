<?php
require_once '../../includes/controllers/ProductController.php';
require_once '../../includes/controllers/CategoryController.php';


$controller = new ProductController();
$product = null;

// Nếu có ID trên URL, tức là đang sửa, thì lấy thông tin sản phẩm đó
if (isset($_GET['id'])) {
    $product = $controller->showProductDetail();
}

$categoryController = new CategoryController();
$categories = $categoryController->listCategories();
include '../../View/admin/product_form.php';
?>