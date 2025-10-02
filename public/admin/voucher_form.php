<?php
require_once '../../includes/controllers/VoucherController.php';
$controller = new VoucherController();
$voucher = $controller->showVoucherForm();
include '../../View/admin/voucher_form.php';
?>