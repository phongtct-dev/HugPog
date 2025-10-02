<?php
require_once '../../includes/controllers/OrderController.php';
$orderController = new OrderController();
$orders = $orderController->listOrdersForAdmin();
include '../../View/admin/order_management.php';
?>