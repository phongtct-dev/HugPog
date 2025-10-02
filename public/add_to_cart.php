<?php
// File: project/public/add_to_cart.php


require_once '../includes/controllers/CartController.php';

$cartController = new CartController();
$cartController->handleAddToCart();