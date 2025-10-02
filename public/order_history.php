<?php
require_once '../includes/controllers/OrderController.php';
$controller = new OrderController();
$orders = $controller->showOrderHistory();
include '../View/user/order_history.php';
?>