<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Nhân viên</h1>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-shield me-1"></i> Danh sách tài khoản nhân viên</span>
        <a href="staff_form.php" class="btn btn-primary">Thêm nhân viên mới</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên tài khoản</th>
                    <th>Họ tên</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($staff_list as $staff): ?>
                <tr>
                    <td><?php echo $staff['id']; ?></td>
                    <td><?php echo htmlspecialchars($staff['username']); ?></td>
                    <td><?php echo htmlspecialchars($staff['full_name']); ?></td>
                    <td><span class="badge bg-secondary"><?php echo ucfirst($staff['role']); ?></span></td>
                    <td>
                        <span class="badge <?php echo $staff['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $staff['status'] === 'active' ? 'Hoạt động' : 'Đã khóa'; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($staff['id'] != $_SESSION['staff_id']): // Không hiển thị nút cho chính mình ?>
                        <form action="update_staff_status.php" method="POST">
                            <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                            <input type="hidden" name="current_status" value="<?php echo $staff['status']; ?>">
                            <?php if ($staff['status'] === 'active'): ?>
                                <button type="submit" class="btn btn-danger btn-sm">Khóa</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-success btn-sm">Mở khóa</button>
                            <?php endif; ?>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>