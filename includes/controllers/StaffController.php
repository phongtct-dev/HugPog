<?php
// File: project/includes/controllers/StaffController.php

require_once __DIR__ . '/../../models/StaffModel.php';
require_once __DIR__ . '/../config.php';

class StaffController
{
    /**
     * Xử lý đăng nhập cho nhân viên/admin.
     */
    public function handleLogin()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Nếu đã đăng nhập, chuyển thẳng vào dashboard
        if (isset($_SESSION['staff_id'])) {
            header('Location: ' . BASE_URL . 'public/admin/dashboard.php');
            exit();
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $staffModel = new StaffModel();
            $staff = $staffModel->findStaffByUsername($username);

            if ($staff && password_verify($password, $staff['password_hash'])) {
                // Đăng nhập thành công, lưu session cho admin
                $_SESSION['staff_id'] = $staff['id'];
                $_SESSION['staff_username'] = $staff['username'];
                $_SESSION['staff_role'] = $staff['role'];

                header('Location: ' . BASE_URL . 'public/admin/dashboard.php');
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
    public function handleLogout()
    {
        // Kiểm tra session hiện tại
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa các biến session cụ thể của Staff
        unset($_SESSION['staff_id']);
        unset($_SESSION['staff_username']);
        unset($_SESSION['staff_role']);

        // Hủy toàn bộ session
        session_destroy();

        $login_url = BASE_URL . 'public/admin/login.php';
        header('Location: ' . $login_url);
        exit();
    }

    /**
     * Lấy danh sách nhân viên, chỉ cho phép admin.
     * @return array
     */
    public function listStaff()
    {
        // Rất quan trọng: Chỉ admin mới có quyền xem danh sách nhân viên
        if ($_SESSION['staff_role'] !== 'admin') {
            // Nếu không phải admin, có thể chuyển hướng về dashboard hoặc báo lỗi
            header('Location: ' . BASE_URL . 'public/admin/dashboard.php');
            exit();
        }
        $staffModel = new StaffModel();
        return $staffModel->getAllStaff();
    }

    /**
     * Xử lý việc tạo nhân viên mới.
     */
    public function handleCreateStaff()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Chỉ xử lý nếu đây là POST request và hành động là 'create_staff'
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_staff') {

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => $email,
                'username' => $username,
                'role' => trim($_POST['role'] ?? 'employee'),
                'password' => $password,
            ];

            $staffModel = new StaffModel();

            // 1. KIỂM TRA ĐẦU VÀO CƠ BẢN
            if (empty($data['full_name']) || empty($username) || empty($email) || empty($password)) {
                $_SESSION['error_message'] = "❌ Lỗi: Vui lòng điền đầy đủ Tên, Tên đăng nhập, Email và Mật khẩu.";
            }

            // 2. KIỂM TRA TÊN ĐĂNG NHẬP TRÙNG LẶP (Giải quyết lỗi Duplicate entry)
            elseif ($staffModel->isUsernameExists($username)) {
                $_SESSION['error_message'] = "❌ Lỗi: Tên đăng nhập **{$username}** đã tồn tại. Vui lòng chọn tên khác.";
            }

            // 3. XỬ LÝ VÀ CHÈN VÀO CSDL
            else {
                // Băm mật khẩu để đảm bảo an toàn
                $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

                // Loại bỏ password thô khỏi mảng $data trước khi truyền vào Model
                unset($data['password']);

                if ($staffModel->createStaff($data)) {
                    $_SESSION['success_message'] = "✅ Tạo tài khoản nhân viên **{$username}** thành công!";
                } else {
                    // Lỗi cSDL khác (ví dụ: lỗi kết nối)
                    $_SESSION['error_message'] = "❌ Lỗi: Tạo tài khoản thất bại (Lỗi hệ thống).";
                }
            }
        }

        header('Location: ' . BASE_URL . 'public/admin/staff.php');
        exit();
    }

    /**
     * Xử lý cập nhật trạng thái nhân viên.
     */
    public function handleUpdateStaffStatus()
    {
        if ($_SESSION['staff_role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'public/admin/staff.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'], $_POST['new_status'])) {
            $staffId = intval($_POST['staff_id']);
            $newStatus = $_POST['new_status'];

            // Không cho admin tự khóa chính mình
            if ($staffId == $_SESSION['staff_id']) {
                $_SESSION['error_message'] = "Bạn không thể khóa tài khoản của chính mình!";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $staffModel = new StaffModel();
            $staffModel->updateStaffStatus($staffId, $newStatus);

            $_SESSION['success_message'] = "Cập nhật trạng thái nhân viên thành công!";
        } else {
            $_SESSION['error_message'] = "Yêu cầu không hợp lệ!";
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    public function handleUpdateStaff()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['staff_id'])) {
            return;
        }

        $staffId = intval($_POST['staff_id']);
        $newFullName = trim($_POST['full_name']);
        $newEmail = trim($_POST['email']);
        $newRole = trim($_POST['role']);
        $newPassword = $_POST['password'] ?? '';

        $staffModel = new StaffModel();
        $currentStaff = $staffModel->getStaffById($staffId);

        // **QUY TẮC BẢO MẬT: KHÔNG ĐƯỢC CHẠM VÀO TÀI KHOẢN ADMIN/QUẢN TRỊ VIÊN GỐC**
        if ($currentStaff && ($currentStaff['role'] === 'Quản trị viên' || $currentStaff['role'] === 'admin')) {
            $_SESSION['error_message'] = "❌ Lỗi: Không thể sửa thông tin tài khoản Quản trị viên gốc.";
            header('Location: ' . BASE_URL . 'public/admin/staff.php');
            exit();
        }

        $data = [
            'id' => $staffId,
            'full_name' => $newFullName,
            'email' => $newEmail,
            'role' => $newRole,
            'password' => $newPassword, // Mật khẩu (có thể rỗng)
            'username' => $newEmail, // Giả định username = email
        ];

        if ($staffModel->UpdateStaff($data)) {
            $_SESSION['success_message'] = "✅ Cập nhật thông tin nhân viên thành công!";
        } else {
            $_SESSION['error_message'] = "❌ Lỗi: Cập nhật thông tin nhân viên thất bại.";
        }

        header('Location: ' . BASE_URL . 'public/admin/staff.php');
        exit();
    }

    /**
     * Xử lý xóa nhân viên.
     * CÓ KIỂM TRA: KHÔNG ĐƯỢC CHẠM VÀO TÀI KHOẢN ADMIN.
     */
    public function handleDeleteStaff()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['staff_id'])) {
            return;
        }

        $staffId = intval($_POST['staff_id']);

        $staffModel = new StaffModel();
        $currentStaff = $staffModel->getStaffById($staffId);

        // **QUY TẮC BẢO MẬT: KHÔNG ĐƯỢC CHẠM VÀO TÀI KHOẢN ADMIN/QUẢN TRỊ VIÊN GỐC**
        if ($currentStaff && ($currentStaff['role'] === 'Quản trị viên' || $currentStaff['role'] === 'admin')) {
            $_SESSION['error_message'] = "❌ Lỗi: Không thể xóa tài khoản Quản trị viên gốc.";
            header('Location: ' . BASE_URL . 'public/admin/staff.php');
            exit();
        }

        // Không cho nhân viên tự xóa chính mình
        if (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $staffId) {
            $_SESSION['error_message'] = "❌ Lỗi: Không thể tự xóa tài khoản của mình.";
            header('Location: ' . BASE_URL . 'public/admin/staff.php');
            exit();
        }

        if ($staffModel->deleteStaff($staffId)) {
            $_SESSION['success_message'] = "✅ Xóa nhân viên thành công!";
        } else {
            $_SESSION['error_message'] = "❌ Lỗi: Xóa nhân viên thất bại.";
        }

        header('Location: ' . BASE_URL . 'public/admin/staff.php');
        exit();
    }
}
