<?php
// File: project/includes/controllers/PromotionController.php
require_once __DIR__ . '/../../models/PromotionModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../config.php';

class PromotionController {
    public function listPromotions() {
        $promoModel = new PromotionModel();
        return $promoModel->getAllPromotions();
    }
    
    public function getProductsForForm() {
        $productModel = new ProductModel();
        return $productModel->getAllProductsForAdmin();
    }

    public function handleSavePromotion() {
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
    
    public function handleDeletePromotion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promotion_id'])) {
             $promoModel = new PromotionModel();
             $promoModel->deletePromotion(intval($_POST['promotion_id']));
        }
        header('Location: ' . BASE_URL . 'public/admin/promotions.php');
        exit();
    }
}
?>