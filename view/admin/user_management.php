<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Khách hàng</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-users me-1"></i>
        Danh sách khách hàng
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên tài khoản</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Hạng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst($user['rank']); ?></td>
                        <td>
                            <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $user['status'] === 'active' ? 'Hoạt động' : 'Đã khóa'; ?>
                            </span>
                        </td>
                        <td>
                            <form action="update_user_status.php" method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $user['status']; ?>">
                                <?php if ($user['status'] === 'active'): ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Khóa</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success btn-sm">Mở khóa</button>
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