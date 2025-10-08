<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý người dùng</h3>
        </div>

        <div class="page-content">
            <?= $message ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Danh sách người dùng</h4>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                            <i class="fa-solid fa-user-plus me-2"></i>Thêm người dùng
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>Tổng chi tiêu</th>
                                    <th>Cấp bậc</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $index => $user):
                                        $is_active = $user['status'] === 'active';
                                        $data_attributes = [
                                            'data-id' => $user['id'],
                                            'data-username' => htmlspecialchars($user['username']),
                                            'data-email' => htmlspecialchars($user['email']),
                                            'data-rank' => htmlspecialchars($user['rank'] ?? 'silver'),
                                            'data-status' => htmlspecialchars($user['status'] ?? 'active'),
                                            'data-fullname' => htmlspecialchars($user['full_name'] ?? ''),
                                            'data-phone' => htmlspecialchars($user['phone'] ?? ''),
                                            'data-total-spent' => htmlspecialchars($user['total_spent'] ?? '0'), // ĐÃ THÊM
                                        ];
                                    ?>
                                        <tr data-id="<?= $user['id'] ?>">
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= formatCurrency($user['total_spent'] ?? 0) ?></td>
                                            <td><?= displayRankBadge($user['rank'] ?? 'silver') ?></td>
                                            <td><?= displayStatusBadge($user['status'] ?? 'active') ?></td>
                                            <td>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-info edit-user-btn me-2"
                                                    title="Chỉnh sửa"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal"
                                                    <?php foreach ($data_attributes as $key => $value) {
                                                        echo $key . '="' . $value . '" ';
                                                    } ?>>
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <?php if ($is_active): ?>
                                                    <button
                                                        class="btn btn-sm btn-warning"
                                                        title="Khóa tài khoản"
                                                        onclick="confirmStatusToggle(<?= $user['id'] ?>, '<?= $user['status'] ?>', 'Khóa')">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        class="btn btn-sm btn-success"
                                                        title="Kích hoạt tài khoản"
                                                        onclick="confirmStatusToggle(<?= $user['id'] ?>, '<?= $user['status'] ?>', 'Kích hoạt')">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Chưa có người dùng nào trong hệ thống.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Thêm người dùng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="users.php" method="POST">
                    <input type="hidden" name="action_type" value="add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="userStatus" class="form-label">Trạng thái</label>
                            <select class="form-select" id="userStatus" name="userStatus" required>
                                <option value="active">Hoạt động</option>
                                <option value="locked">Khóa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Họ và tên (Tùy chọn)</label>
                            <input type="text" class="form-control" id="fullName" name="fullName">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại (Tùy chọn)</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="userRank" class="form-label">Cấp bậc</label>
                            <select class="form-select" id="userRank" name="userRank" required>
                                <option value="diamond">Kim cương</option>
                                <option value="gold">Vàng</option>
                                <option value="silver">Bạc</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm người dùng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="users.php" method="POST" id="editUserForm">
                    <input type="hidden" name="action_type" value="update">
                    <input type="hidden" name="userId" id="editUserId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFullName" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="editFullName" name="fullName">
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="editPhone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Mật khẩu (Để trống nếu không đổi)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="editUserRank" class="form-label">Cấp bậc</label>
                            <select class="form-select" id="editUserRank" name="userRank" required>
                                <option value="diamond">Kim cương</option>
                                <option value="gold">Vàng</option>
                                <option value="silver">Bạc</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUserStatus" class="form-label">Trạng thái</label>
                            <select class="form-select" id="editUserStatus" name="userStatus" required>
                                <option value="active">Hoạt động</option>
                                <option value="locked">Khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="statusToggleForm" action="users.php" method="POST" style="display: none;">
        <input type="hidden" name="action_type" value="status_toggle">
        <input type="hidden" name="userId" id="toggleUserId">
        <input type="hidden" name="currentStatus" id="toggleCurrentStatus">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic điền dữ liệu vào Modal Chỉnh sửa
            const editModal = document.getElementById('editUserModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    // Lấy dữ liệu từ các thuộc tính data-* trên nút
                    const id = button.getAttribute('data-id');
                    const username = button.getAttribute('data-username');
                    const email = button.getAttribute('data-email');
                    // BỎ: const role = button.getAttribute('data-role');
                    const rank = button.getAttribute('data-rank');
                    const status = button.getAttribute('data-status');
                    const fullName = button.getAttribute('data-fullname');
                    const phone = button.getAttribute('data-phone');
                    // BỎ: const totalSpent = button.getAttribute('data-total-spent'); // Chỉ để xem, không chỉnh sửa

                    // Điền dữ liệu vào các trường trong modal
                    editModal.querySelector('#editUserId').value = id;
                    editModal.querySelector('#editUsername').value = username;
                    editModal.querySelector('#editEmail').value = email;
                    editModal.querySelector('#editFullName').value = fullName;
                    editModal.querySelector('#editPhone').value = phone;

                    // Chọn đúng option Cấp bậc, Trạng thái
                    // BỎ: editModal.querySelector('#editUserRole').value = role;
                    editModal.querySelector('#editUserRank').value = rank;
                    editModal.querySelector('#editUserStatus').value = status;
                });
            }
        });

        // Logic xác nhận chuyển đổi trạng thái (Khóa/Kích hoạt)
        function confirmStatusToggle(userId, currentStatus, actionText) {
            if (confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản này không?`)) {
                document.getElementById('toggleUserId').value = userId;
                document.getElementById('toggleCurrentStatus').value = currentStatus;
                document.getElementById('statusToggleForm').submit();
            }
        }
    </script>
</body>

</html>