<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\VoucherController;

$voucherController = new VoucherController();

// Xử lý POST request nếu có
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucherController->handleAdminVoucherAction();
}

// Lấy dữ liệu cho view
$vouchers = $voucherController->listVouchers();
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $icon = ($type === 'success' ? '✅' : '❌');
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . $icon . ' ' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

// Các hàm helper cho view
function displayDiscountValue($value) {
    if ($value < 100 && $value == round($value)) {
        return $value . '%';
    }
    return number_format($value, 0, ',', '.') . ' VNĐ';
}
function displayVoucherStatusBadge($status, $expiry_date, $quantity) {
    $current_datetime = new DateTime();
    $expiry_datetime = new DateTime($expiry_date);
    $status_text = ''; $class = '';
    if ($current_datetime > $expiry_datetime) {
        $status_text = 'Hết hạn'; $class = 'bg-danger';
    } else if ($quantity <= 0) {
        $status_text = 'Hết SL'; $class = 'bg-danger';
    } else {
        switch (strtolower($status)) {
            case 'active': $status_text = 'Hoạt động'; $class = 'bg-success'; break;
            case 'inactive': $status_text = 'Ngừng'; $class = 'bg-secondary'; break;
            default: $status_text = 'Không rõ'; $class = 'bg-warning text-dark'; break;
        }
    }
    return '<span class="badge ' . $class . '">' . $status_text . '</span>';
}
function formatDatetimeForInput($datetime_db) {
    if (empty($datetime_db)) return '';
    try {
        return (new DateTime($datetime_db))->format('Y-m-d\TH:i');
    } catch (Exception $e) { return ''; }
}

include __DIR__ . '/../../view/admin/voucher_management.php';