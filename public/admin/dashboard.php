<?php
// File: project/public/admin/dashboard.php
require_once '../../includes/controllers/ReportController.php';
$reportController = new ReportController();
$stats = $reportController->prepareRevenueReportData()['stats']; // Chỉ lấy phần stats

include '../../View/admin/dashboard.php';
?>