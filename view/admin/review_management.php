<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../includes/controllers/ReviewController.php';

$reviewController = new ReviewController();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controller xử lý POST request và tự động REDIRECT/EXIT
    $reviewController->handleAdminReviewAction();
}

$reviews = $reviewController->listReviewsForAdmin();

// Lấy thông báo từ session
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}


/**
 * Hiển thị ngôi sao dựa trên xếp hạng.
 * @param int $rating Điểm xếp hạng (1-5)
 * @return string HTML của các biểu tượng ngôi sao
 */
function displayRatingStars($rating)
{
    $html = '<div class="text-warning">';
    $rating = (int)$rating;
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star"></i>'; // Ngôi sao vàng
        } else {
            $html .= '<i class="far fa-star"></i>'; // Ngôi sao rỗng
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Hiển thị badge trạng thái.
 */
function displayStatusBadge($status)
{
    switch ($status) {
        case 'visible':
            return '<span class="badge bg-success">Đang hiển thị</span>';
        case 'hidden':
            return '<span class="badge bg-danger">Đã ẩn</span>';
        default:
            return '<span class="badge bg-secondary">Trạng thái khác</span>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>


    <div id="main" class="p-4">
        <div class="row">
            <div class="col-12">
                <div
                    class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="card-title mb-0">Quản lý Đánh giá</h4>
                </div>

                <?= $message ?> <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Mã Đánh giá</th>
                                        <th scope="col">Sản phẩm</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Xếp hạng</th>
                                        <th scope="col">Nội dung</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reviews)): ?>
                                        <?php foreach ($reviews as $review):
                                            // Dữ liệu từ Controller/Model: username, product_name, comment, created_at, status
                                        ?>
                                            <tr>
                                                <td>RV<?= htmlspecialchars(str_pad($review['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                                                <td><?= htmlspecialchars($review['product_name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($review['username'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?= displayRatingStars($review['rating']) ?>
                                                </td>
                                                <td><?= htmlspecialchars(mb_strimwidth($review['comment'], 0, 50, "...")) ?></td>
                                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($review['created_at']))) ?></td>
                                                <td>
                                                    <?= displayStatusBadge($review['status']) ?>
                                                </td>
                                                <td>
                                                    <?php if ($review['status'] !== 'visible'): ?>
                                                        <button
                                                            class="btn btn-sm btn-success"
                                                            title="Hiển thị"
                                                            onclick="confirmAction(<?= $review['id'] ?>, 'visible', 'hiển thị đánh giá này')">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($review['status'] === 'visible'): ?>
                                                        <button
                                                            class="btn btn-sm btn-warning text-dark"
                                                            title="Ẩn"
                                                            onclick="confirmAction(<?= $review['id'] ?>, 'hidden', 'ẩn đánh giá này')">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Chưa có đánh giá nào trong hệ thống.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="reviewActionForm" action="reviews.php" method="POST" style="display: none;">
        <input type="hidden" name="reviewId" id="actionReviewId">
        <input type="hidden" name="action_type" id="actionType">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /**
         * Xác nhận và gửi yêu cầu hành động (Hiển thị/Ẩn).
         */
        function confirmAction(reviewId, actionType, actionText) {
            if (confirm(`Bạn có chắc chắn muốn ${actionText} không?`)) {
                document.getElementById('actionReviewId').value = reviewId;
                document.getElementById('actionType').value = actionType;

                document.getElementById('reviewActionForm').submit();
            }
        }
    </script>
</body>

</html>