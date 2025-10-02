<?php
// File: project/public/logout.php

require_once '../includes/controllers/UserController.php';

$userController = new UserController();
$userController->handleLogout();
?>