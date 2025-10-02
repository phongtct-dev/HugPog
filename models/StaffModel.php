<?php
// File: project/models/StaffModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class StaffModel {
    /**
     * Tìm nhân viên/admin bằng username.
     * @param string $username
     * @return array|null
     */
    public function findStaffByUsername($username) {
        $conn = db_connect();
        $sql = "SELECT * FROM staff_accounts WHERE username = ? AND status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $staff;
    }
    
    //

    /**
     * Lấy tất cả tài khoản nhân viên.
     * @return array
     */
    public function getAllStaff() {
        $conn = db_connect();
        $sql = "SELECT id, username, email, full_name, role, status FROM staff_accounts ORDER BY id DESC";
        $result = $conn->query($sql);
        $staff = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $staff;
    }

    /**
     * Tạo tài khoản nhân viên mới.
     * @param array $data
     * @return bool
     */
    public function createStaff($data) {
        $conn = db_connect();
        $sql = "INSERT INTO staff_accounts (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $data['username'], $data['email'], $data['password_hash'], $data['full_name'], $data['role']);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Cập nhật trạng thái tài khoản nhân viên.
     * @param int $staffId
     * @param string $newStatus
     * @return bool
     */
    public function updateStaffStatus($staffId, $newStatus) {
        $conn = db_connect();
        $sql = "UPDATE staff_accounts SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $staffId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }
}
?>