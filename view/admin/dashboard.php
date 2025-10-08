<?php
// 5. Hàm hỗ trợ hiển thị (Helper Functions)
function format_currency_vn($amount)
{
    if (!is_numeric($amount)) return '0 VNĐ';
    // Sử dụng number_format cho định dạng tiền tệ Việt Nam
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/app.css">

</head>

<body>

    <div id="app">
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>


        <div id="main" class="p-4">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading mb-4">
                <h3>Bảng Điều Khiển</h3>
            </div>

            <section class="section">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card card-hover-shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Tổng Đơn Hàng</h6>
                                        <h6 class="font-extrabold mb-0"><?= number_format($total_orders) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card card-hover-shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Tổng Sản Phẩm</h6>
                                        <h6 class="font-extrabold mb-0"><?= number_format($total_products) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card card-hover-shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Tổng Khách Hàng</h6>
                                        <h6 class="font-extrabold mb-0"><?= number_format($total_users) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card card-hover-shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="fa-solid fa-briefcase"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Tổng Nhân Viên</h6>
                                        <h6 class="font-extrabold mb-0"><?= number_format($total_staff) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Tổng quan Doanh thu 30 ngày qua</h4>
                                        <p class="card-subtitle">Dữ liệu thực tế từ các đơn hàng đã hoàn thành</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="chart-profile-visits"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card bg-success text-white card-hover-shadow">
                            <div class="card-body">
                                <h5 class="card-title text-white">Doanh thu Hôm nay</h5>
                                <h3 class="fw-bold mb-0 text-white"><?= format_currency_vn($total_revenue_today) ?></h3>
                                <p class="card-text text-white-50">Đơn hàng đã hoàn thành trong ngày</p>
                            </div>
                        </div>

                        <div class="card bg-primary text-white card-hover-shadow">
                            <div class="card-body">
                                <h5 class="card-title text-white">Doanh thu Tháng này</h5>
                                <h3 class="fw-bold mb-0 text-white"><?= format_currency_vn($total_revenue_month) ?></h3>
                                <p class="card-text text-white-50">Tổng doanh thu từ đầu tháng</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">5 Đơn Hàng Gần Đây</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Mã ĐH</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_orders)): ?>
                                                <?php foreach ($recent_orders as $order): ?>
                                                    <tr>
                                                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                                                        <td><?= htmlspecialchars($order['full_name']) ?></td>
                                                        <td><?= format_currency_vn($order['total_amount']) ?></td>
                                                        <td><?= display_order_status($order['status']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Chưa có đơn hàng nào gần đây.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="orders.php" class="btn btn-sm btn-outline-primary float-end">Xem tất cả</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">5 Sản Phẩm Mới Thêm</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Kho</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_products)): ?>
                                                <?php foreach ($recent_products as $product): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($product['id']) ?></td>
                                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                                        <td><?= format_currency_vn($product['price']) ?></td>
                                                        <td><?= number_format($product['stock']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Không có sản phẩm mới nào gần đây.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="products.php" class="btn btn-sm btn-outline-primary float-end">Xem tất cả</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        new PerfectScrollbar('#sidebar');
    </script>

    <script src="/HugPog/public/js/main.js"></script>
    <script src="/HugPog/public/js/api_admin.js"></script>

    <script>
        const REVENUE_CHART_DATA = <?php echo json_encode($revenue_chart_data); ?>;
    </script>

</body>

</html>