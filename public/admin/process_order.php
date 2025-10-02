<?php
// File: project/public/admin/process_order.php
require_once '../../includes/controllers/OrderController.php';

$orderController = new OrderController();
$orderController->handleProcessOrder();
?>