<?php
require_once '../../includes/controllers/UserController.php';
$controller = new UserController();
$users = $controller->listUsersForAdmin();
include '../../View/admin/user_management.php';
?>