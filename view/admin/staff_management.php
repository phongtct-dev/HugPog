<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân viên - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý Nhân viên</h3>
        </div>

        <div class="page-content">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách Nhân viên</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staffModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Nhân viên
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($list_staff)): ?>
                                    <?php foreach ($list_staff as $staff): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($staff['id']); ?></td>
                                            <td><?php echo htmlspecialchars($staff['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($staff['username']); ?></td>
                                            <td><?php echo htmlspecialchars($staff['email']); ?></td>
                                            <td><?php echo htmlspecialchars($staff['role']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($staff['status'] === 'active' ? 'success' : 'danger'); ?>">
                                                    <?php echo ($staff['status'] === 'active' ? 'Đang hoạt động' : 'Đã khóa'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info text-white me-1 edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#staffModal"
                                                    data-id="<?php echo $staff['id']; ?>"
                                                    data-fullname="<?php echo htmlspecialchars($staff['full_name']); ?>"
                                                    data-username="<?php echo htmlspecialchars($staff['username']); ?>" data-email="<?php echo htmlspecialchars($staff['email']); ?>"
                                                    data-role="<?php echo htmlspecialchars($staff['role']); ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>

                                                <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái của nhân viên này?');">
                                                    <input type="hidden" name="action" value="change_status">
                                                    <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                    <input type="hidden" name="new_status" value="<?php echo ($staff['status'] === 'active' ? 'locked' : 'active'); ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo ($staff['status'] === 'active' ? 'warning' : 'success'); ?>"
                                                        title="<?php echo ($staff['status'] === 'active' ? 'locked' : 'Đang hoạt động'); ?>">
                                                        <i class="fa-solid fa-<?php echo ($staff['status'] === 'active' ? 'lock' : 'unlock'); ?>"></i>
                                                    </button>
                                                </form>

                                                <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này? Hành động này không thể hoàn tác!');">
                                                    <input type="hidden" name="action" value="delete_staff">
                                                    <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có nhân viên nào trong hệ thống.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

    <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalLabel">Thêm Nhân viên Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="staffForm" method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="staffAction" value="create_staff">
                        <input type="hidden" name="staff_id" id="staffId">

                        <div class="mb-3">
                            <label for="fullName" class="form-label">Tên đầy đủ</label>
                            <input type="text" class="form-control" id="fullName" name="full_name" required>
                        </div>

                        <div class="mb-3"> <label for="username" class="form-label">Tên đăng nhập (Username)</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="employee">Nhân viên (Employee)</option>
                            </select>
                        </div>
                        <div class="mb-3" id="passwordGroup">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text text-muted" id="passwordHelp">
                                Để trống nếu bạn đang sửa và không muốn thay đổi mật khẩu.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Xử lý modal khi nhấn nút Sửa
        document.addEventListener('DOMContentLoaded', function() {
            var staffModal = document.getElementById('staffModal');
            staffModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var staffId = button.getAttribute('data-id');
                var modalTitle = staffModal.querySelector('.modal-title');
                var staffAction = staffModal.querySelector('#staffAction');
                var staffIdInput = staffModal.querySelector('#staffId');
                var fullNameInput = staffModal.querySelector('#fullName');
                var usernameInput = staffModal.querySelector('#username');
                var emailInput = staffModal.querySelector('#email');
                var roleSelect = staffModal.querySelector('#role');
                var passwordInput = staffModal.querySelector('#password');
                var passwordHelp = staffModal.querySelector('#passwordHelp');
                var submitButton = staffModal.querySelector('#submitButton');

                if (staffId) {
                    // Chế độ Sửa
                    modalTitle.textContent = 'Cập nhật Nhân viên';
                    staffAction.value = 'update_staff';
                    staffIdInput.value = staffId;
                    fullNameInput.value = button.getAttribute('data-fullname');
                    usernameInput.value = button.getAttribute('data-username');
                    emailInput.value = button.getAttribute('data-email');
                    roleSelect.value = button.getAttribute('data-role');
                    passwordInput.removeAttribute('required');
                    passwordInput.value = '';
                    passwordHelp.style.display = 'block';
                    submitButton.textContent = 'Cập nhật';
                } else {
                    // Chế độ Thêm mới
                    modalTitle.textContent = 'Thêm Nhân viên Mới';
                    staffAction.value = 'create_staff';
                    staffIdInput.value = '';
                    fullNameInput.value = '';
                    usernameInput.value = ''; // Đã thêm
                    emailInput.value = '';
                    roleSelect.value = 'employee';
                    passwordInput.setAttribute('required', 'required');
                    passwordInput.value = '';
                    passwordHelp.style.display = 'none';
                    submitButton.textContent = 'Lưu';
                }
            });
        });
    </script>
</body>

</html>