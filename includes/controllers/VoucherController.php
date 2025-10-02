<?php
// File: project/includes/controllers/VoucherController.php

require_once __DIR__ . '/../../models/VoucherModel.php';
require_once __DIR__ . '/../config.php';

class VoucherController {
    private $voucherModel;

    public function __construct() {
        $this->voucherModel = new VoucherModel();
    }

    public function listVouchers() {
        return $this->voucherModel->getAllVouchers();
    }

    public function showVoucherForm() {
        $voucher = null;
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $voucher = $this->voucherModel->findVoucherById(intval($_GET['id']));
        }
        return $voucher;
    }

    public function handleSaveVoucher() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code'           => trim($_POST['code']),
                'discount_value' => floatval($_POST['discount_value']),
                'quantity'       => intval($_POST['quantity']),
                'expiry_date'    => $_POST['expiry_date'],
                'status'         => $_POST['status'],
                'staff_id'       => $_SESSION['staff_id'] // Lấy ID của admin đang đăng nhập
            ];

            $voucherId = $_POST['voucher_id'] ?? null;

            if ($voucherId) {
                $this->voucherModel->updateVoucher($voucherId, $data);
            } else {
                $this->voucherModel->createVoucher($data);
            }
            header('Location: ' . BASE_URL . 'public/admin/vouchers.php');
            exit();
        }
    }
}
?>