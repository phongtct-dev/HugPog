<?php
require_once '../../includes/controllers/CategoryController.php';
$controller = new CategoryController();
$categories = $controller->listCategories();
include '../../View/admin/category_management.php';
?>