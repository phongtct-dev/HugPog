<?php
require_once '../../includes/controllers/StaffController.php';
$controller = new StaffController();
$staff_list = $controller->listStaff();
include '../../View/admin/staff_management.php';
?>