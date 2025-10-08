<?php
// File: project/includes/controllers/PromotionController.php
require_once __DIR__ . '/../../models/PromotionModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../config.php';

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
        if (session_status() == PHP_SESSION_NONE) session_start();
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
        } catch (Exception $e) {
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
        $now = time();
        $start = strtotime($startDate);
        $end = strtotime($endDate . ' 23:59:59');
        if ($start > $now) {
            return '<span class="badge bg-info text-dark">Sắp diễn ra</span>';
        }
        if ($end < $now) {
            return '<span class="badge bg-secondary">Đã kết thúc</span>';
        }
        return '<span class="badge bg-success">Đang diễn ra</span>';
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
