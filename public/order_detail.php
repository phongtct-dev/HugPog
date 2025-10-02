<?php
require_once '../includes/controllers/OrderController.php';
$controller = new OrderController();
$order = $controller->showUserOrderDetail();
include '../View/user/order_detail.php';
?>