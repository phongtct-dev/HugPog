<?php
// File: project/models/StaffModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class StaffModel
{
    /**
     * Tìm nhân viên/admin bằng username.
     * @param string $username
     * @return array|null
     */
    public function findStaffByUsername($username)
    {
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
    public function getAllStaff()
    {
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
    public function createStaff($data)
    {
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
    public function updateStaffStatus($staffId, $newStatus)
    {
        $conn = db_connect();
        $sql = "UPDATE staff_accounts SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $staffId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Lấy tổng số lượng nhân viên.
     */
    public function getTotalStaff()
    {
        $conn = db_connect();
        $sql = "SELECT COUNT(*) as total FROM staff_accounts WHERE role != 'admin' AND status = 'active'"; // Giả định: bảng staff_accounts
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row ? $row['total'] : 0;
    }

    /**
     * Lấy thông tin nhân viên theo ID.
     * Cần thiết để kiểm tra vai trò (role) trước khi cho phép sửa/xóa/thay đổi trạng thái.
     * @param int $staffId
     * @return array|null
     */
    public function getStaffById($staffId)
    {
        $conn = db_connect();
        // Lấy tất cả thông tin quan trọng
        $sql = "SELECT id, username, email, full_name, role, status FROM staff_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $staffId);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $staff;
    }

    /**
     * Cập nhật thông tin tài khoản nhân viên.
     * Đã cập nhật để bao gồm trường username và giải quyết lỗi scalar.
     * @param array $data
     * @return bool
     */
    public function updateStaff($data)
    {
        $conn = db_connect();
        
        // 1. Thiết lập SQL, Tham số và Kiểu dữ liệu ban đầu
        $sql = "UPDATE staff_accounts SET full_name = ?, email = ?, username = ?, role = ?";
        $params = [$data['full_name'], $data['email'], $data['username'], $data['role']];
        $types = "ssss";
        
        // 2. Thêm mật khẩu nếu có (password không rỗng)
        if (!empty($data['password'])) {
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql .= ", password_hash = ?";
            $params[] = $password_hash;
            $types .= "s";
        }

        // 3. Thêm điều kiện WHERE (Dùng ID)
        $sql .= " WHERE id = ?";
        $params[] = $data['id'];
        $types .= "i";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
             $conn->close();
             return false;
        }

        // 4. Tạo mảng tham số cho bind_param: [types_string, value1, value2, ...]
        // Sử dụng array_merge để tạo một mảng mới cho việc bind, giữ cho $params không bị thay đổi định nghĩa.
        $bind_params = array_merge([$types], $params);
        
        // 5. Chuyển mảng thành mảng tham chiếu và gọi bind_param
        $refs = $this->refValues($bind_params);
        
        if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
            $stmt->close();
            $conn->close();
            return false;
        }

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        
        return $success;
    }

    /**
     * Xóa tài khoản nhân viên.
     * @param int $staffId
     * @return bool
     */
    public function DeleteStaff($staffId)
    {
        $conn = db_connect();
        $sql = "DELETE FROM staff_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $staffId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Hàm tiện ích để bind_param hoạt động với mảng.
     * Đảm bảo mọi phần tử trong mảng là tham chiếu.
     * @param array $arr
     * @return array
     */
    private function refValues($arr) {
        $refs = [];
        // Lỗi "Cannot use a scalar value as an array" thường xảy ra ở đây 
        // nếu $arr không phải là mảng.
        // Bằng cách gọi is_array() hoặc đảm bảo nó là mảng trước khi gọi, lỗi sẽ được ngăn chặn.
        if (is_array($arr)) {
            foreach($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
        }
        return $refs;
    }

    /**
     * KIỂM TRA: Kiểm tra xem username đã tồn tại trong CSDL hay chưa. (Giải quyết lỗi Duplicate entry)
     * @param string $username
     * @param int|null $excludeId ID của staff hiện tại (khi update)
     * @return bool True nếu đã tồn tại, False nếu chưa.
     */
    public function isUsernameExists($username, $excludeId = null)
    {
        $conn = db_connect();
        
        $sql = "SELECT COUNT(*) FROM staff_accounts WHERE username = ?";
        $params = [$username];
        $types = "s";
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= "i";
        }
        
        $stmt = $conn->prepare($sql);
        
        if ($types === "s") {
            $stmt->bind_param($types, $params[0]);
        } else {
            $stmt->bind_param($types, $params[0], $params[1]);
        }

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        
        return $count > 0;
    }
}
