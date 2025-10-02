<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Đánh giá</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-comments me-1"></i>
        Danh sách đánh giá của khách hàng
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Người đánh giá</th>
                    <th>Sản phẩm</th>
                    <th>Điểm</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['username']); ?></td>
                        <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                        <td class="text-warning"><?php echo str_repeat('★', $review['rating']); ?></td>
                        <td><?php echo htmlspecialchars($review['comment']); ?></td>
                        <td>
                            <span class="badge <?php echo $review['status'] === 'visible' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $review['status'] === 'visible' ? 'Đang hiển thị' : 'Đã ẩn'; ?>
                            </span>
                        </td>
                        <td>
                            <form action="update_review_status.php" method="POST">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $review['status']; ?>">
                                <?php if ($review['status'] === 'visible'): ?>
                                    <button type="submit" class="btn btn-secondary btn-sm">Ẩn</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-info btn-sm">Hiện</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>