<?php
// File: project/includes/controllers/VoucherController.php

require_once __DIR__ . '/../../models/VoucherModel.php';
// require_once __DIR__ . '/../config.php'; // Giả định file này chứa BASE_URL nếu cần

class VoucherController
{
    private $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    public function listVouchers()
    {
        return $this->voucherModel->getAllVouchers();
    }

    // Hàm showVoucherForm và handleSaveVoucher gốc có vẻ không được dùng trong Admin UI này
    // public function showVoucherForm() { /* ... */ } 
    // public function handleSaveVoucher() { /* ... */ }

    /**
     * Xử lý các hành động quản trị (Add, Update, Status Toggle) trên voucher.
     */
    public function handleAdminVoucherAction()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Mặc định Staff ID nếu chưa đăng nhập. Thay '1' bằng logic session thực tế
        $staff_id = $_SESSION['staff_id'] ?? 1;
        $redirect_url = 'vouchers.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action_type = $_POST['action_type'] ?? 'add';
        $voucher_id = $_POST['voucherId'] ?? null;
        $result = false;
        $message_content = '';

        try {
            // ==========================================================
            // XỬ LÝ STATUS TOGGLE
            // ==========================================================
            if ($action_type === 'status_toggle' && !empty($voucher_id)) {
                $current_status = strtolower(trim($_POST['currentStatus'] ?? 'inactive'));
                $new_status = ($current_status === 'active') ? 'inactive' : 'active';
                $action_text = ($new_status === 'active') ? 'Kích hoạt' : 'Ngừng hoạt động';

                $result = $this->voucherModel->updateVoucherStatus(intval($voucher_id), $new_status);
                $message_content = $result ? "✅ $action_text Voucher thành công!" : "❌ Lỗi: $action_text Voucher thất bại.";
            }
            // ==========================================================
            // XỬ LÝ THÊM HOẶC CẬP NHẬT
            // ==========================================================
            else if ($action_type === 'add' || $action_type === 'update') {

                // 1. Chuẩn hóa format ngày giờ
                $expiry_input = trim($_POST['voucherExpiry'] ?? '');
                $expiry_date_safe = '';

                if (!empty($expiry_input)) {
                    // Thay thế 'T' (từ input datetime-local) bằng khoảng trắng
                    $timestamp = strtotime(str_replace('T', ' ', $expiry_input));

                    if ($timestamp === false) {
                        $expiry_date_safe = 'INVALID';
                    } else {
                        // Định dạng thành chuẩn YYYY-MM-DD HH:MM:SS
                        $expiry_date_safe = date('Y-m-d H:i:s', $timestamp);
                    }
                }

                $data = [
                    'code'           => strtoupper(trim($_POST['voucherCode'] ?? '')),
                    'discount_value' => floatval($_POST['discountValue'] ?? 0),
                    'quantity'       => intval($_POST['voucherQuantity'] ?? 0),
                    'expiry_date'    => $expiry_date_safe, // ĐÃ CHUẨN HÓA
                    'status'         => trim($_POST['voucherStatus'] ?? 'active'),
                    'staff_id'       => $staff_id,
                ];

                // 2. Validation
                $error = '';
                if (empty($data['code']) || $data['discount_value'] <= 0 || $data['quantity'] < 0) {
                    $error = 'Vui lòng điền đầy đủ Mã, Giá trị giảm giá, và Số lượng hợp lệ.';
                } else if (empty($data['expiry_date']) || $data['expiry_date'] === 'INVALID') {
                    $error = 'Định dạng Ngày và Giờ hết hạn không hợp lệ. Vui lòng đảm bảo đã chọn đủ Ngày và Giờ.';
                }

                if (empty($error)) {
                    // PHẦN LOGIC THÊM/CẬP NHẬT
                    if ($action_type === 'add') {
                        // Kiểm tra trùng lặp mã chỉ khi thêm mới
                        if ($this->voucherModel->getVoucherByCode($data['code'])) {
                            $error = 'Mã Voucher này đã tồn tại trong hệ thống.';
                        } else {
                            $result = $this->voucherModel->createVoucher($data);
                            $message_content = $result ? '✅ Thêm Voucher thành công!' : '❌ Lỗi: Thêm Voucher thất bại.';
                        }
                    } else if ($action_type === 'update' && !empty($voucher_id)) {
                        // Khi cập nhật, bỏ qua kiểm tra trùng lặp code
                        $result = $this->voucherModel->updateVoucher(intval($voucher_id), $data);
                        $message_content = $result ? '✅ Cập nhật Voucher thành công!' : '❌ Lỗi: Cập nhật Voucher thất bại.';
                    }
                }

                if (!empty($error)) {
                    $message_content = '❌ Lỗi: ' . $error;
                }
            }
        } catch (Exception $e) {
            // Bắt lỗi từ Model (ví dụ lỗi MySQLi)
            $message_content = '❌ Lỗi hệ thống: ' . $e->getMessage();
        }

        $message_type = $result ? 'success' : 'error';
        $_SESSION['message'] = ['type' => $message_type, 'content' => $message_content];

        header("Location: " . $redirect_url);
        exit();
    }

}
