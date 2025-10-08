<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Điều chỉnh đường dẫn
require_once '../../includes/controllers/VoucherController.php';

$voucherController = new VoucherController();

// =================================================================================
// LOGIC XỬ LÝ POST REQUEST
// =================================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controller xử lý POST request (Add/Update/Status Toggle) và tự động REDIRECT/EXIT
    $voucherController->handleAdminVoucherAction();
    // Sau khi xử lý POST và redirect, đoạn mã dưới đây sẽ không chạy
}

// =================================================================================
// TẢI DỮ LIỆU VOUCHER VÀ THÔNG BÁO
// =================================================================================
$vouchers = $voucherController->listVouchers();

// Lấy thông báo từ session
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    // Sử dụng icon cho thông báo
    $icon = ($type === 'success' ? '✅' : '❌');
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . $icon . ' ' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

// =================================================================================
// HÀM HỖ TRỢ HIỂN THỊ (VIEW HELPER FUNCTIONS) - KHÔNG THAY ĐỔI
// =================================================================================

/**
 * Định dạng giá trị giảm giá (giả định là tiền mặt, cần điều chỉnh nếu là %)
 * @param float $value
 * @return string
 */
function displayDiscountValue($value)
{
    // Giữ nguyên logic: dưới 100 là %, ngược lại là VNĐ
    if ($value < 100 && $value == round($value)) {
        return $value . '%';
    }
    return number_format($value, 0, ',', '.') . ' VNĐ';
}

/**
 * Hiển thị trạng thái voucher
 * @param string $status
 * @param string $expiry_date
 * @param int $quantity
 * @return string
 */
function displayVoucherStatusBadge($status, $expiry_date, $quantity)
{
    // Giữ nguyên logic kiểm tra Hết hạn/Hết SL
    $current_datetime = new DateTime();
    $expiry_datetime = new DateTime($expiry_date);
    $status_text = '';
    $class = '';

    if ($current_datetime > $expiry_datetime) {
        $status_text = 'Hết hạn';
        $class = 'bg-danger';
    } else if ($quantity <= 0) {
        $status_text = 'Hết SL';
        $class = 'bg-danger';
    } else {
        $status = strtolower($status);
        switch ($status) {
            case 'active':
                $status_text = 'Hoạt động';
                $class = 'bg-success';
                break;
            case 'inactive':
                $status_text = 'Ngừng';
                $class = 'bg-secondary';
                break;
            case 'expired':
                $status_text = 'Hết hạn (DB)';
                $class = 'bg-danger';
                break;
            default:
                $status_text = 'Không rõ';
                $class = 'bg-warning text-dark';
                break;
        }
    }

    return '<span class="badge ' . $class . '">' . $status_text . '</span>';
}

/**
 * Chuẩn hóa định dạng ngày giờ cho input type="datetime-local"
 * @param string $datetime_db
 * @return string
 */
function formatDatetimeForInput($datetime_db)
{
    // Giữ nguyên logic
    if (empty($datetime_db) || $datetime_db === '0000-00-00 00:00:00') {
        return '';
    }
    try {
        $dt = new DateTime($datetime_db);
        return $dt->format('Y-m-d\TH:i');
    } catch (Exception $e) {
        return '';
    }
}

include '../../View/admin/voucher_management.php';
?>