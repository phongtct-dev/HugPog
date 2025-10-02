<?php
// File: project/public/remove_from_cart.php
require_once '../includes/controllers/CartController.php';
$cartController = new CartController();
$cartController->handleRemoveItem();
?>