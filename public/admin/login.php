<?php
// File: project/public/admin/index.php

require_once '../../includes/controllers/StaffController.php';

$staffController = new StaffController();
$errors = $staffController->handleLogin();

include '../../View/admin/login.php';
?>