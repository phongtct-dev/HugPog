<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\OrderController;

$orderController = new OrderController();
$orderController->handleProcessOrder();