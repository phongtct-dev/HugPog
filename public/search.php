<?php
require_once '../includes/controllers/ProductController.php';

$controller = new ProductController();
$searchData = $controller->handleSearch();

// Truyền các biến ra view
$products = $searchData['products'];
$keyword = $searchData['keyword'];

include '../view/user/search_results.php';
?>