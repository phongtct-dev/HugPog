<?php
// File: project/models/UserModel.php

// Nhúng file kết nối DB để sử dụng hàm db_connect()
require_once __DIR__ . '/../includes/db_connect.php';

class UserModel {
    /**
     * Hàm này kiểm tra xem username hoặc email đã tồn tại trong DB chưa.
     * @param string $identifier - Tên đăng nhập hoặc email cần kiểm tra.
     * @return bool - true nếu đã tồn tại, false nếu chưa.
     */
    public function doesUserExist($identifier) {
        $conn = db_connect();
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $stmt->store_result(); // Lưu kết quả để đếm số hàng
        $num_rows = $stmt->num_rows;
        $stmt->close();
        $conn->close();
        return $num_rows > 0;
    }

    /**
     * Hàm này tạo một người dùng mới trong database.
     * @param string $username - Tên đăng nhập
     * @param string $email - Email
     * @param string $hashedPassword - Mật khẩu đã được mã hóa
     * @param string $fullName - Họ và tên
     * @return bool - true nếu tạo thành công, false nếu thất bại.
     */
    public function createUser($username, $email, $hashedPassword, $fullName) {
        $conn = db_connect();
        // Câu lệnh SQL khớp với cấu trúc bảng `users` của bạn
        $sql = "INSERT INTO users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $email, $hashedPassword, $fullName);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return true;
        }
        $stmt->close();
        $conn->close();
        return false;
    }

    //

    /**
     * Hàm này tìm và lấy thông tin của một người dùng dựa trên username.
     * Chúng ta cần lấy password_hash để so sánh và id, username để lưu vào session.
     * @param string $username - Tên đăng nhập cần tìm.
     * @return array|null - Trả về một mảng chứa thông tin user, hoặc null nếu không tìm thấy.
     */
    public function findUserByUsername($username) {
        $conn = db_connect();
        // Lấy các cột cần thiết để xác thực và lưu session
        $sql = "SELECT id, username, password_hash, status FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        
        // Lấy kết quả
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); // fetch_assoc() lấy 1 dòng kết quả
        
        $stmt->close();
        $conn->close();
        
        return $user;
    }

    //

    /**
     * Lấy tất cả người dùng (khách hàng) cho trang admin.
     * @return array
     */
    public function getAllUsers() {
        $conn = db_connect();
        $sql = "SELECT id, username, email, full_name, `rank`, total_spent, status, created_at FROM users ORDER BY id DESC";
        $result = $conn->query($sql);
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $users;
    }

    /**
     * Cập nhật trạng thái của một người dùng (active/locked).
     * @param int $userId ID người dùng.
     * @param string $newStatus Trạng thái mới.
     * @return bool
     */
    public function updateUserStatus($userId, $newStatus) {
        $conn = db_connect();
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $userId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    //

        /**
     * Cập nhật tổng chi tiêu và hạng của người dùng sau khi một đơn hàng hoàn thành.
     * @param int $userId ID của người dùng.
     * @param float $orderTotal Giá trị đơn hàng vừa hoàn thành.
     */
    public function updateUserSpendingAndRank($userId, $orderTotal) {
        $conn = db_connect();

        // 1. Cộng dồn tổng chi tiêu cho người dùng
        $sqlUpdateSpending = "UPDATE users SET total_spent = total_spent + ? WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdateSpending);
        $stmt->bind_param("di", $orderTotal, $userId);
        $stmt->execute();
        $stmt->close();

        // 2. Lấy lại tổng chi tiêu mới nhất
        $sqlSelectSpending = "SELECT total_spent FROM users WHERE id = ?";
        $stmt = $conn->prepare($sqlSelectSpending);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $totalSpent = $user['total_spent'];
        $stmt->close();

        // 3. Xác định hạng mới dựa trên tổng chi tiêu
        // Bạn có thể thay đổi các mốc này tùy theo chính sách của cửa hàng
        $newRank = 'silver';
        if ($totalSpent >= 10000000) { // Mốc 10 triệu cho hạng Kim cương
            $newRank = 'diamond';
        } elseif ($totalSpent >= 5000000) { // Mốc 5 triệu cho hạng Vàng
            $newRank = 'gold';
        }

        // 4. Cập nhật lại hạng mới cho người dùng
        $sqlUpdateRank = "UPDATE users SET `rank` = ? WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdateRank);
        $stmt->bind_param("si", $newRank, $userId);
        $stmt->execute();
        $stmt->close();

        $conn->close();
    }
}
?>