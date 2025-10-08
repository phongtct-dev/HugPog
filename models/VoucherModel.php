<?php
// File: project/models/VoucherModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class VoucherModel
{
    /**
     * Lấy tất cả voucher.
     * @return array
     */
    public function getAllVouchers()
    {
        $conn = db_connect();
        $sql = "SELECT * FROM vouchers ORDER BY id DESC";
        $result = $conn->query($sql);
        $vouchers = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $vouchers;
    }

    /**
     * Tìm voucher bằng ID.
     * @param int $id
     * @return array|null
     */
    public function findVoucherById($id)
    {
        $conn = db_connect();
        $sql = "SELECT * FROM vouchers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $voucher;
    }

    /**
     * Tạo voucher mới.
     * @param array $data
     * @return bool
     */
    public function createVoucher($data)
    {
        $conn = db_connect();

        // Chuẩn hóa định dạng ngày hết hạn (giữ nguyên logic gốc)
        $expiry_date = $data['expiry_date'] ?? null;
        if ($expiry_date) {
            // Xử lý các trường hợp nếu input không đủ giờ phút (chủ yếu là cho input type="date" hoặc nhập tay)
            if (preg_match('/^\d{4}$/', $expiry_date)) {
                $expiry_date = $expiry_date . '-12-31 23:59:59';
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiry_date)) {
                $expiry_date .= ' 23:59:59';
            }
        } else {
            $expiry_date = date('Y-m-d H:i:s', strtotime('+1 year'));
        }

        $sql = "INSERT INTO vouchers (code, discount_value, quantity, expiry_date, status, created_by_staff_id) 
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sdissi", // string, double, integer, string, string, integer
            $data['code'],
            $data['discount_value'],
            $data['quantity'],
            $expiry_date, 
            $data['status'],
            $data['staff_id']
        );

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Cập nhật voucher.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateVoucher($id, $data)
    {
        $conn = db_connect();
        $sql = "UPDATE vouchers SET 
                    code = ?, discount_value = ?, quantity = ?, expiry_date = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Chuỗi bind_param: code(s), discount_value(d), quantity(i), expiry_date(s), status(s), id(i)
        $stmt->bind_param(
            "sdissi", 
            $data['code'],
            $data['discount_value'],
            $data['quantity'],
            $data['expiry_date'],
            $data['status'],
            $id
        );
        $success = $stmt->execute();

        // Kiểm tra lỗi sau khi EXECUTE và ném ngoại lệ để Controller bắt
        if (!$success) {
            $error_message = "Lỗi hệ thống MySQLi: " . $stmt->error;
            error_log("MySQLi Error in updateVoucher: " . $error_message);
            $stmt->close();
            $conn->close();
            throw new Exception($error_message);
        }

        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Tìm một voucher hợp lệ bằng mã code.
     * @param string $code
     * @return array|null
     */
    public function findVoucherByCode($code)
    {
        $conn = db_connect();
        // Một voucher hợp lệ phải: đúng code, 'active', còn số lượng, và chưa hết hạn
        $sql = "SELECT * FROM vouchers 
                WHERE code = ? AND status = 'active' AND quantity > 0 AND expiry_date >= CURDATE()";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $voucher;
    }

    /**
     * Cập nhật trạng thái của một voucher (active/inactive).
     * @param int $voucherId
     * @param string $newStatus ('active', 'inactive')
     * @return bool
     */
    public function updateVoucherStatus($voucherId, $newStatus)
    {
        $conn = db_connect();
        $sql = "UPDATE vouchers SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $voucherId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Tìm voucher bằng mã code (dùng để kiểm tra trùng lặp).
     * @param string $code
     * @return array|null
     */
    public function getVoucherByCode($code)
    {
        $conn = db_connect();
        $sql = "SELECT * FROM vouchers WHERE code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $voucher;
    }
}
