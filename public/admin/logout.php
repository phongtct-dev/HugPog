<?php
// File: project/public/admin/logout.php

require_once '../../includes/controllers/StaffController.php';
$staffController = new StaffController();
$staffController->handleLogout();
?>