<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\StaffController;

$staff_controller = new StaffController();

// Xử lý POST request nếu có
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create_staff': $staff_controller->handleCreateStaff(); break;
        case 'update_staff': $staff_controller->handleUpdateStaff(); break;
        case 'change_status': $staff_controller->handleUpdateStaffStatus(); break;
    }
}

// Lấy dữ liệu cho view
$list_staff = $staff_controller->listStaff();
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

include __DIR__ . '/../../view/admin/staff_management.php';