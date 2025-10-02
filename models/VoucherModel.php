<?php
// File: project/models/VoucherModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class VoucherModel {
    /**
     * Lấy tất cả voucher.
     * @return array
     */
    public function getAllVouchers() {
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
    public function findVoucherById($id) {
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
    public function createVoucher($data) {
        $conn = db_connect();
        $sql = "INSERT INTO vouchers (code, discount_value, quantity, expiry_date, status, created_by_staff_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sdissi",
            $data['code'],
            $data['discount_value'],
            $data['quantity'],
            $data['expiry_date'],
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
    public function updateVoucher($id, $data) {
        $conn = db_connect();
        $sql = "UPDATE vouchers SET 
                    code = ?, discount_value = ?, quantity = ?, expiry_date = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sdisii",
            $data['code'],
            $data['discount_value'],
            $data['quantity'],
            $data['expiry_date'],
            $data['status'],
            $id
        );
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    //

    /**
     * Tìm một voucher hợp lệ bằng mã code.
     * @param string $code
     * @return array|null
     */
    public function findVoucherByCode($code) {
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
}
?>