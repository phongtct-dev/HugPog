<?php

namespace App\Controllers;
// File: project/includes/controllers/PromotionController.php
require_once __DIR__ . '/../config.php';
use App\Models\PromotionModel;
use App\Models\ProductModel;

class PromotionController
{

    private $promoModel;
    private $productModel;

    public function __construct()
    {
        $this->promoModel = new PromotionModel();
        $this->productModel = new ProductModel();
    }

    public function listPromotions()
    {
        $promoModel = new PromotionModel();
        return $promoModel->getAllPromotions();
    }

    public function getProductsForForm()
    {
        $productModel = new ProductModel();
        return $productModel->getAllProductsForAdmin();
    }

    public function handleSavePromotion()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'product_id'       => intval($_POST['product_id']),
                'discount_percent' => floatval($_POST['discount_percent']),
                'start_date'       => $_POST['start_date'],
                'end_date'         => $_POST['end_date'],
                'staff_id'         => $_SESSION['staff_id']
            ];
            $promoModel = new PromotionModel();
            $promoModel->createPromotion($data);
        }
        header('Location: ' . BASE_URL . 'public/admin/promotions.php');
        exit();
    }

    public function handleDeletePromotion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promotion_id'])) {
            $promoModel = new PromotionModel();
            $promoModel->deletePromotion(intval($_POST['promotion_id']));
        }
        header('Location: ' . BASE_URL . 'public/admin/promotions.php');
        exit();
    }

    /**
     * Xử lý toàn bộ POST request (Thêm, Xóa) cho trang quản lý khuyến mãi.
     * Lưu kết quả vào $_SESSION['message'] và thực hiện redirect.
     */
    public function handleAdminAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return; // Chỉ xử lý POST
        }
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Giả định URL cần redirect
        $redirect_url = 'promotions.php';

        $action_type = $_POST['action_type'] ?? 'add';
        $promotion_id = $_POST['promotionId'] ?? null;
        $result = false;
        $message_content = '';

        try {
            // Lấy Staff ID (giả định có trong session, mặc định là 1 nếu không có)
            $staff_id = $_SESSION['staff_id'] ?? 1;

            // --- XỬ LÝ XÓA ---
            if ($action_type === 'delete' && !empty($promotion_id)) {
                $result = $this->promoModel->deletePromotion(intval($promotion_id));
                $message_content = $result ? '✅ Xóa chương trình Khuyến Mãi thành công!' : '❌ Lỗi: Xóa chương trình Khuyến Mãi thất bại.';
            }
            // --- XỬ LÝ THÊM ---
            else if ($action_type === 'add') {
                $data = [
                    'product_id'       => $_POST['product_id'] ?? 0,
                    'discount_percent' => $_POST['discount_percent'] ?? 0,
                    'start_date'       => $_POST['start_date'] ?? '',
                    'end_date'         => $_POST['end_date'] ?? '',
                    'staff_id'         => $staff_id
                ];

                // 1. Validation trong Controller
                $product_id = intval($data['product_id']);
                $discount = floatval($data['discount_percent']);
                $start_date = $data['start_date'];
                $end_date = $data['end_date'];

                if ($product_id <= 0 || $discount <= 0 || $discount > 100 || empty($start_date) || empty($end_date)) {
                    $message_content = '❌ Lỗi: Vui lòng điền đầy đủ thông tin hợp lệ (Sản phẩm, %, Ngày bắt đầu/kết thúc).';
                } else if (strtotime($start_date) > strtotime($end_date)) {
                    $message_content = '❌ Lỗi: Ngày bắt đầu không được lớn hơn Ngày kết thúc.';
                } else {
                    // 2. Gọi Model
                    $result = $this->promoModel->createPromotion($data);
                    $message_content = $result ? '✅ Thêm Khuyến Mãi thành công!' : '❌ Lỗi: Thêm Khuyến Mãi thất bại.';
                }
            }
        } catch (\Exception $e) {
            error_log("Promotion Admin Action Error: " . $e->getMessage());
            $message_content = '❌ Lỗi hệ thống: Đã xảy ra sự cố không mong muốn.';
        }

        $message_type = $result && strpos($message_content, '❌') === false ? 'success' : 'error';
        $_SESSION['message'] = ['type' => $message_type, 'content' => $message_content];

        // Thực hiện điều hướng và kết thúc script
        header("Location: " . $redirect_url);
        exit();
    }

private function getPromotionStatusBadge($startDate, $endDate)
{
    // Cần phải xử lý để đảm bảo $startDate và $endDate chỉ là ngày tháng năm (YYYY-MM-DD)
    // Hoặc, nếu chúng đã là DATETIME, KHÔNG được nối chuỗi thêm nữa.
    
    try {
        $timezone = new \DateTimeZone('Asia/Ho_Chi_Minh');
        
        $now_dt = new \DateTime('now', $timezone);
        $now_timestamp = $now_dt->getTimestamp();

        // 1. Tính toán thời gian BẮT ĐẦU:
        // Cú pháp sửa: Chỉ truyền $startDate mà không nối chuỗi ' 00:00:00' nếu nó đã là DATETIME
        // Nếu muốn đảm bảo là 00:00:00, bạn có thể tạo từ ngày (date)
        $start_dt = new \DateTime((new \DateTime($startDate))->format('Y-m-d') . ' 00:00:00', $timezone);
        $start_timestamp = $start_dt->getTimestamp();
        
        // 2. Tính toán thời gian KẾT THÚC (cuối ngày: 23:59:59)
        // Cú pháp sửa: Đảm bảo chỉ có một phần thời gian
        $end_dt = new \DateTime((new \DateTime($endDate))->format('Y-m-d') . ' 23:59:59', $timezone); 
        $end_timestamp = $end_dt->getTimestamp();

        if ($start_timestamp > $now_timestamp) {
            return '<span class="badge bg-info text-dark">Sắp diễn ra</span>';
        }
        
        if ($end_timestamp < $now_timestamp) {
            return '<span class="badge bg-secondary">Đã kết thúc</span>';
        }
        
        return '<span class="badge bg-success">Đang diễn ra</span>';
    } catch (\Exception $e) {
        // Ghi log lỗi để debug
        error_log("DateTime Parsing Error in PromotionController: " . $e->getMessage());
        return '<span class="badge bg-danger">Lỗi Hệ Thống</span>';
    }
}

    public function getPromotionsViewData()
    {
        $promotions = $this->promoModel->getAllPromotions();
        $viewData = [];
        foreach ($promotions as $promo) {
            $promo['status_html'] = $this->getPromotionStatusBadge($promo['start_date'], $promo['end_date']);
            $promo['formatted_time'] = date('d/m/Y', strtotime($promo['start_date'])) . ' - ' . date('d/m/Y', strtotime($promo['end_date']));
            $promo['discount_html'] = '<span class="badge bg-danger">' . $promo['discount_percent'] . '%</span>';
            $viewData[] = $promo;
        }
        return $viewData;
    }
    

}
