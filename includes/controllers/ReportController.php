<?php
// File: project/includes/controllers/ReportController.php

require_once __DIR__ . '/../../models/ReportModel.php';

class ReportController {
    private $reportModel;

    public function __construct() {
        $this->reportModel = new ReportModel();
    }

    /**
     * Chuẩn bị dữ liệu cho trang báo cáo doanh thu.
     * @return array
     */
    public function prepareRevenueReportData() {
        $data = [];
        $data['stats'] = $this->reportModel->getHomepageStats();
        $data['best_selling'] = $this->reportModel->getBestSellingProducts(5);
        // Sau này có thể thêm các báo cáo khác như sản phẩm bán ế, doanh thu theo ngày...
        
        return $data;
    }
}
?>