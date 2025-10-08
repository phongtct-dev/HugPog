<?php

/**
 * Hàm định dạng số thành chuỗi tiền tệ VNĐ có dấu chấm.
 */
function formatCurrencyVN($amount)
{
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Doanh thu - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Báo cáo doanh thu</h3>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card p-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-dong-sign text-success fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 text-muted">Tổng doanh thu</h6>
                                    <h4 class="fw-bold"><?php echo formatCurrencyVN($total_revenue); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card p-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-receipt text-primary fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 text-muted">Tổng đơn hàng</h6>
                                    <h4 class="fw-bold"><?php echo number_format($total_orders); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card p-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-user-plus text-info fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 text-muted">Tổng Khách Hàng</h6>
                                    <h4 class="fw-bold"><?= number_format($total_users) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card p-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-undo text-danger fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 text-muted">Tổng Sản Phẩm</h6>
                                    <h4 class="fw-bold"><?= number_format($total_products) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Doanh thu 30 ngày qua</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-profile-visits"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Sản phẩm bán chạy</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($top_products)): ?>
                                            <?php foreach ($top_products as $product): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                                    <td><?php echo formatCurrencyVN($product['total_revenue']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu sản phẩm bán chạy.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <span class="copyright">
                    Bản quyền &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    Tất cả quyền được bảo lưu | Mẫu này được tạo bởi Hùng & Phong
                </span>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        new PerfectScrollbar('#sidebar');
    </script>

    <script src="../../public/js/main.js"></script>
    <script src="../../public/js/api_admin.js"></script>

    <script>
        // Biến REVENUE_CHART_DATA sẽ chứa dữ liệu doanh thu 30 ngày qua
        const REVENUE_CHART_DATA = <?php echo json_encode($revenue_chart_data); ?>;
    </script>


</body>

</html>