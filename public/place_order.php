<?php
// File: project/public/place_order.php
require_once '../includes/controllers/OrderController.php';
$orderController = new OrderController();
$orderController->handlePlaceOrder();
?>