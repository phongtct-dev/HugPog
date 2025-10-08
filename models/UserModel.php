<?php
// File: project/models/UserModel.php

// Nhúng file kết nối DB để sử dụng hàm db_connect()
require_once __DIR__ . '/../includes/db_connect.php';

class UserModel
{
    /**
     * Hàm này kiểm tra xem username hoặc email đã tồn tại trong DB chưa.
     * @param string $identifier - Tên đăng nhập hoặc email cần kiểm tra.
     * @return bool - true nếu đã tồn tại, false nếu chưa.
     */
    public function doesUserExist($identifier)
    {
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
    public function createUser($data)
    {
        $conn = db_connect();
        $sql = "INSERT INTO users (username, email, password_hash, full_name, phone, birth_date, status, `rank`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $phone = $data['phone'] ?? NULL;
        $birth_date = $data['birth_date'] ?? NULL;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssss", // 8 tham số
            $data['username'],
            $data['email'],
            $password_hash,
            $data['full_name'],
            $phone,
            $birth_date,
            $data['status'],
            $data['rank']
        );

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Hàm này tìm và lấy thông tin của một người dùng dựa trên username.
     * Chúng ta cần lấy password_hash để so sánh và id, username để lưu vào session.
     * @param string $username - Tên đăng nhập cần tìm.
     * @return array|null - Trả về một mảng chứa thông tin user, hoặc null nếu không tìm thấy.
     */
    public function findUserByUsername($username)
    {
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

    /**
     * Lấy tất cả người dùng (khách hàng) cho trang admin.
     * @return array
     */
    public function getAllUsers()
    {
        $conn = db_connect();
        $sql = "SELECT id, username, email, full_name, phone, birth_date, `rank`, total_spent, status, created_at FROM users ORDER BY id DESC";
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
    public function updateUserStatus($userId, $newStatus)
    {
        $conn = db_connect();
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $userId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Cập nhật tổng chi tiêu và hạng của người dùng sau khi một đơn hàng hoàn thành.
     * @param int $userId ID của người dùng.
     * @param float $orderTotal Giá trị đơn hàng vừa hoàn thành.
     */
    public function updateUserSpendingAndRank($userId, $orderTotal)
    {
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

    /**
     * Hàm lấy tổng số lượng người dùng thường ('user') đang hoạt động.
     */
    public function getTotalUsers()
    {
        $conn = db_connect();
        // Giả định bảng users có cột 'role' và 'status'
        $sql = "SELECT COUNT(*) as total FROM users WHERE status = 'active'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row ? $row['total'] : 0;
    }

    /**
     * Cập nhật thông tin người dùng từ admin. (Giờ đã bao gồm cập nhật trạng thái)
     * @param int $userId ID người dùng.
     * @param array $data Dữ liệu cần cập nhật.
     * @return bool
     */
    public function updateUser($userId, $data)
    {
        $conn = db_connect();
        $setClauses = [];
        $params = [];
        $types = "";

        // Tạo câu lệnh SET động và mảng tham số
        if (isset($data['username'])) { $setClauses[] = "username = ?"; $params[] = $data['username']; $types .= "s"; }
        if (isset($data['email'])) { $setClauses[] = "email = ?"; $params[] = $data['email']; $types .= "s"; }
        if (!empty($data['password'])) { 
            $setClauses[] = "password_hash = ?"; 
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT); 
            $types .= "s"; 
        } 
        if (isset($data['full_name'])) { $setClauses[] = "full_name = ?"; $params[] = $data['full_name']; $types .= "s"; }
        if (isset($data['phone'])) { $setClauses[] = "phone = ?"; $params[] = $data['phone']; $types .= "s"; }
        // Đã bỏ birth_date/gender/role

        // Cập nhật trạng thái người dùng (active/locked)
        if (isset($data['status'])) { 
            $setClauses[] = "status = ?"; 
            $params[] = (strtolower($data['status']) === 'locked') ? 'locked' : 'active'; 
            $types .= "s"; 
        }
        if (isset($data['rank'])) { $setClauses[] = "`rank` = ?"; $params[] = $data['rank']; $types .= "s"; }

        if (empty($setClauses)) {
            $conn->close();
            return true;
        }

        $sql = "UPDATE users SET " . implode(", ", $setClauses) . " WHERE id = ?";
        
        $params[] = $userId;
        $types .= "i";

        $stmt = $conn->prepare($sql);

        $bind_names = [$types];
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function getUserById($userId)
    {
        $conn = db_connect();
        $sql = "SELECT id, username, email, full_name, phone, birth_date, `rank`, total_spent, status, created_at 
            FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user ?: null;
    }

    public function getUserByEmail($email)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPasswordByEmail($email, $new_hashed_password)
    {
        global $pdo;
        // Cập nhật trường 'password' cho người dùng có 'email' tương ứng
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([$new_hashed_password, $email]);
    }
}
