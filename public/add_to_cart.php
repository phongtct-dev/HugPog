<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\CartController;

$cartController = new CartController();
$cartController->handleAddToCart();