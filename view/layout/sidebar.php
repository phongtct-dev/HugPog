<?php

/**
 * Tệp này bao gồm thanh bên (sidebar) và phần đầu (header) cơ bản cho trang admin.
 * Để đánh dấu mục menu đang active, cần xác định URL của trang hiện tại.
 */

// Lấy đường dẫn của trang hiện tại (ví dụ: /admin/admin_dashboard.php)
// Dùng parse_url để đảm bảo chỉ lấy phần path
$current_url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Tùy chỉnh: Chuẩn hóa đường dẫn để so sánh, loại bỏ tiền tố không cần thiết
// Giả định các liên kết trong sidebar đều có dạng '../admin/*.php'
$base_path_to_check = strtolower(basename($current_url_path));

// Hàm kiểm tra và trả về class 'active' nếu đường dẫn khớp
function is_active_link($link_href, $current_base_path)
{
    // Lấy tên tệp (ví dụ: admin_dashboard.php) từ href của link
    $link_file_name = strtolower(basename($link_href));

    // Xử lý trường hợp đặc biệt cho trang Dashboard/Trang chủ
    // Nếu link là admin_dashboard.php, nó sẽ active khi path hiện tại là nó
    // HOẶC nếu bạn muốn nó active cho thư mục gốc của admin (cần setup phức tạp hơn)
    if ($link_file_name === 'admin_dashboard.php' && ($current_base_path === 'admin_dashboard.php' || $current_base_path === 'admin/')) {
        return 'active';
    }

    // So sánh tên tệp của link với tên tệp của URL hiện tại
    if ($link_file_name === $current_base_path) {
        return 'active';
    }

    return '';
}
?>

<?php
include_once '../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang có Sidebar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">

</head>

<body>
    <div id="sidebar" class="active">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header" style="padding: 20px 40px 0;">
                <div class="d-flex justify-content-between">
                    <div class="logo">
                        <a href="<?= BASE_URL ?>public/admin/dashboard.php"><img
                                src="/HugPog/public/asset/image/logo.png"
                                alt="Logo"
                                class="img-fluid custom-logo-size"
                                style="height: auto !important; width: auto;" /></a>
                    </div>
                    <div class="toggler">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Danh mục</li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/dashboard.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/dashboard.php" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i>
                            <span>Bảng điều khiển</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/revenue_report.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/revenue_report.php" class="sidebar-link">
                            <i class="fa-solid fa-square-poll-vertical"></i>
                            <span>Doanh thu</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/staff.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/staff.php" class="sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span>Nhân viên</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/orders.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/orders.php" class="sidebar-link">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Đơn hàng</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/categories.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/categories.php" class="sidebar-link">
                            <i class="fa-solid fa-folder"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/products.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/products.php" class="sidebar-link">
                            <i class="fa-solid fa-box"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/reviews.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/reviews.php" class="sidebar-link">
                            <i class="fa-regular fa-comment"></i>
                            <span>Đánh giá</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/users.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/users.php" class="sidebar-link">
                            <i class="fa-regular fa-user"></i>
                            <span>Người dùng</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/vouchers.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/vouchers.php" class="sidebar-link">
                            <i class="fa-solid fa-ticket"></i>
                            <span>Phiếu giảm giá</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo is_active_link('<?= BASE_URL ?>public/admin/promotions.php', $base_path_to_check); ?>">
                        <a href="<?= BASE_URL ?>public/admin/promotions.php" class="sidebar-link">
                            <i class="fa-solid fa-rectangle-ad"></i>
                            <span>Khuyến mãi</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a
                            href="<?= BASE_URL ?>public/admin/logout.php"
                            class="sidebar-link"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
                            <i class="fa fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
        </div>
    </div>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script>
        // Bật perfect scrollbar cho sidebar
        new PerfectScrollbar('#sidebar');
    </script>
    <script src="../js/main.js"></script>

</body>

</html>