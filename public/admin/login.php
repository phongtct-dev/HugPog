<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';

use App\Controllers\StaffController;

$staffController = new StaffController();
$errors = $staffController->handleLogin();

include __DIR__ . '/../../view/admin/login.php';