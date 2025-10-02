<?php
require_once '../../includes/controllers/VoucherController.php';
$controller = new VoucherController();
$vouchers = $controller->listVouchers();
include '../../View/admin/voucher_management.php';
?>