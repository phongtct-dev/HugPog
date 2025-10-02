<?php
require_once '../../includes/controllers/OrderController.php';
$orderController = new OrderController();
$order = $orderController->showOrderDetailForAdmin();
include '../../View/admin/order_detail.php';
?>