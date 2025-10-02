<?php
require_once '../includes/controllers/ProductController.php';

$controller = new ProductController();
$searchData = $controller->handleSearch();

// Truyền các biến ra view
$products = $searchData['products'];
$keyword = $searchData['keyword'];

include '../View/user/search_results.php';
?>