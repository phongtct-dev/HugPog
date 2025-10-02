<?php
// File: project/includes/controllers/StaffController.php

require_once __DIR__ . '/../../models/StaffModel.php';
require_once __DIR__ . '/../config.php';

class StaffController {
    /**
     * Xử lý đăng nhập cho nhân viên/admin.
     */
    public function handleLogin() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Nếu đã đăng nhập, chuyển thẳng vào dashboard
        if (isset($_SESSION['staff_id'])) {
            header('Location: dashboard.php');
            exit();
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username =trim($_POST['username']);
            $password =trim($_POST['password']);
            
            $staffModel = new StaffModel();
            $staff = $staffModel->findStaffByUsername($username);

            //đứng
            

            if ($staff && password_verify($password, $staff['password_hash'])) {
                // Đăng nhập thành công, lưu session cho admin
                $_SESSION['staff_id'] = $staff['id'];
                $_SESSION['staff_username'] = $staff['username'];
                $_SESSION['staff_role'] = $staff['role'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
            }
        }
        return $errors;
    }

    /**
     * Xử lý đăng xuất.
     */
    public function handleLogout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        // Hủy các biến session của admin
        unset($_SESSION['staff_id']);
        unset($_SESSION['staff_username']);
        unset($_SESSION['staff_role']);

        header('Location: index.php'); // Chuyển về trang đăng nhập admin
        exit();
    }

    //

    /**
     * Lấy danh sách nhân viên, chỉ cho phép admin.
     * @return array
     */
    public function listStaff() {
        // Rất quan trọng: Chỉ admin mới có quyền xem danh sách nhân viên
        if ($_SESSION['staff_role'] !== 'admin') {
            // Nếu không phải admin, có thể chuyển hướng về dashboard hoặc báo lỗi
            header('Location: dashboard.php');
            exit();
        }
        $staffModel = new StaffModel();
        return $staffModel->getAllStaff();
    }

    /**
     * Xử lý việc tạo nhân viên mới.
     */
    public function handleCreateStaff() {
        if ($_SESSION['staff_role'] !== 'admin') {
            header('Location: dashboard.php');
            exit();
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username'  => trim($_POST['username']),
                'email'     => trim($_POST['email']),
                'full_name' => trim($_POST['full_name']),
                'password'  => $_POST['password'],
                'role'      => $_POST['role']
            ];
            
            // Validate... (có thể thêm sau)

            // Băm mật khẩu
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $staffModel = new StaffModel();
            if ($staffModel->createStaff($data)) {
                header('Location: staff.php');
                exit();
            } else {
                $errors[] = "Tạo tài khoản thất bại.";
            }
        }
        return $errors;
    }

    /**
     * Xử lý cập nhật trạng thái nhân viên.
     */
    public function handleUpdateStaffStatus() {
        if ($_SESSION['staff_role'] !== 'admin') {
            header('Location: dashboard.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'], $_POST['current_status'])) {
            $staffId = intval($_POST['staff_id']);
            $currentStatus = $_POST['current_status'];
            
            // Không cho admin tự khóa chính mình
            if ($staffId == $_SESSION['staff_id']) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $newStatus = ($currentStatus === 'active') ? 'locked' : 'active';
            
            $staffModel = new StaffModel();
            $staffModel->updateStaffStatus($staffId, $newStatus);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>