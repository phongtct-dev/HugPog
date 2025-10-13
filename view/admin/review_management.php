<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/app.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý Đánh giá</h3>
        </div>
        <div class="page-content">
            <?php echo $message; ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <tbody>
                                <?php if (!empty($reviews)): ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td>RV<?= htmlspecialchars(str_pad($review['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                                            <td><?= htmlspecialchars($review['product_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($review['username'] ?? 'N/A') ?></td>
                                            <td><?= displayRatingStars($review['rating']) ?></td>
                                            <td><?= htmlspecialchars(mb_strimwidth($review['comment'], 0, 50, "...")) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($review['created_at']))) ?></td>
                                            <td><?= displayStatusBadge($review['status']) ?></td>
                                            <td>
                                                <?php if ($review['status'] !== 'visible'): ?>
                                                    <button
                                                        class="btn btn-sm btn-success"
                                                        title="Hiển thị"
                                                        onclick="confirmAction(<?php echo $review['id']; ?>, 'visible', 'hiển thị đánh giá này')">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <?php if ($review['status'] === 'visible'): ?>
                                                    <button
                                                        class="btn btn-sm btn-warning text-dark"
                                                        title="Ẩn"
                                                        onclick="confirmAction(<?php echo $review['id']; ?>, 'hidden', 'ẩn đánh giá này')">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có đánh giá nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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