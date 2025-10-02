<?php
require_once '../../includes/controllers/CategoryController.php';
$controller = new CategoryController();
$category = $controller->showCategoryForm();
if (!$category) { die("Không tìm thấy danh mục."); }
include '../../View/admin/category_form.php';
?>