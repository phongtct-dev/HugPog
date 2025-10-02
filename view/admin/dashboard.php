<?php include 'header.php'; ?>

<h1 class="mt-4">Bảng điều khiển</h1>
<p>Tổng quan về hoạt động kinh doanh của cửa hàng.</p>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h4><?php echo $stats['pending_orders'] ?? 0; ?></h4>
                <p>Đơn hàng chờ xử lý</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h4><?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?> VNĐ</h4>
                <p>Tổng doanh thu</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h4><?php echo $stats['total_customers'] ?? 0; ?></h4>
                <p>Tổng số khách hàng</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-secondary text-white mb-4">
            <div class="card-body">
                <h4><?php echo $stats['total_products'] ?? 0; ?></h4>
                <p>Tổng số sản phẩm</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-chart-bar me-1"></i>Sản phẩm bán chạy nhất</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Lấy dữ liệu sản phẩm bán chạy từ ReportController
                        $reportController = new ReportController();
                        $bestSelling = $reportController->prepareRevenueReportData()['best_selling'];
                        foreach($bestSelling as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['total_sold']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        </div>
</div>

<?php include 'footer.php'; ?>