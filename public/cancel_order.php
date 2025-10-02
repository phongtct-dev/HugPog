<?php
require_once '../includes/controllers/OrderController.php';
$controller = new OrderController();
$controller->handleCancelOrder();
?>