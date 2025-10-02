<?php include 'header.php'; ?>

<h1 class="mt-4">Thêm nhân viên mới</h1>
<div class="card">
    <div class="card-body">
        <form action="save_staff.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Tên tài khoản</label>
                <input type="text" class="form-control" name="username" required>
            </div>
             <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
             <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Vai trò</label>
                <select name="role" class="form-select">
                    <option value="employee">Nhân viên (Employee)</option>
                    <option value="admin">Quản trị viên (Admin)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="staff.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>