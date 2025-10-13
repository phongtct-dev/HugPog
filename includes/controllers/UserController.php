<?php

namespace App\Controllers;
// File: project/includes/controllers/UserController.php


// === THÊM 3 DÒNG NÀY VÀO ===
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// =============================
// Nhúng UserModel vào để sử dụng
use App\Models\UserModel;

class UserController
{
    private $userModel;

    // Hàm khởi tạo, tự động tạo một đối tượng UserModel khi UserController được gọi
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Hàm xử lý toàn bộ logic cho việc đăng ký.
     * @return array - Trả về một mảng chứa các lỗi. Mảng rỗng nếu không có lỗi.
     */
    public function handleRegister()
    {
        $errors = []; // Khởi tạo mảng lỗi

        // Chỉ xử lý khi người dùng nhấn nút submit (phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form và làm sạch
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $fullName = trim($_POST['full_name']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // 1. Kiểm tra dữ liệu (Validation)
            if (empty($username)) $errors[] = "Tên đăng nhập không được để trống.";
            if (empty($email)) $errors[] = "Email không được để trống.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không đúng định dạng.";
            if (empty($password)) $errors[] = "Mật khẩu không được để trống.";
            if (strlen($password) < 6) $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
            if ($password !== $confirmPassword) $errors[] = "Mật khẩu xác nhận không khớp.";
            if ($this->userModel->doesUserExist($username)) $errors[] = "Tên đăng nhập này đã có người sử dụng.";
            if ($this->userModel->doesUserExist($email)) $errors[] = "Email này đã có người sử dụng.";

            // 2. Nếu không có lỗi nào
            if (empty($errors)) {
                // Mã hóa mật khẩu trước khi lưu vào DB
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // 3. Gọi Model để lưu người dùng vào DB
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $fullName,
                    'password' => $password, // Truyền password gốc, vì UserModel sẽ hash nó
                    'phone' => null, // Gán NULL hoặc giá trị mặc định cho các trường không có trong form
                    'birth_date' => null,
                    'status' => 'active', // Thiết lập trạng thái mặc định
                    'rank' => 'silver',    // Thiết lập hạng mặc định
                ];

                // 3. Gọi Model để lưu người dùng vào DB, truyền MẢNG $userData
                // Hàm createUser trong UserModel đã tự động hash mật khẩu
                $success = $this->userModel->createUser($userData);

                if ($success) {
                    // Nếu thành công, lưu thông báo và chuyển hướng sang trang đăng nhập
                    $_SESSION['success_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                    header("Location: login.php");
                    exit(); // Luôn exit sau khi chuyển hướng
                } else {
                    $errors[] = "Có lỗi xảy ra, vui lòng thử lại.";
                }
            }
        }
        // Trả về mảng lỗi để View có thể hiển thị cho người dùng
        return $errors;
    }

    //

    /**
     * Hàm xử lý toàn bộ logic cho việc đăng nhập.
     * @return array - Trả về mảng lỗi, rỗng nếu đăng nhập thành công.
     */
    public function handleLogin()
    {
        $errors = [];

        // Chỉ xử lý khi người dùng nhấn nút submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // 1. Kiểm tra dữ liệu đầu vào
            if (empty($username) || empty($password)) {
                $errors[] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
            } else {
                // 2. Gọi Model để tìm người dùng trong DB
                $user = $this->userModel->findUserByUsername($username);

                // 3. Xác thực người dùng
                // password_verify() sẽ so sánh mật khẩu người dùng nhập với chuỗi hash trong DB
                if ($user && password_verify($password, $user['password_hash'])) {

                    // Kiểm tra tài khoản có bị khóa không
                    if ($user['status'] === 'locked') {
                        $errors[] = "Tài khoản của bạn hiện đang bị khóa.";
                    } else {
                        // Đăng nhập thành công!
                        // Đăng nhập thành công!
                        // 4. Lưu thông tin cần thiết vào SESSION
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username']; // Tên đăng nhập

                        // LƯU THÊM THÔNG TIN PROFILE VÀO SESSION
                        $_SESSION['user_profile'] = [
                            'full_name' => $user['full_name'],
                            'phone'     => $user['phone'],
                            'address'   => $user['address']
                        ];

                        // Đặt lại username hiển thị trên header là full_name cho đẹp
                        $_SESSION['username'] = $user['full_name'];

                        // Chuyển hướng tới trang chủ
                        header("Location:" . BASE_URL . "public/index.php");
                        exit();
                    }
                } else {
                    // Nếu không tìm thấy user hoặc sai mật khẩu
                    $errors[] = "Tên đăng nhập hoặc mật khẩu không chính xác.";
                }
            }
        }
        return $errors;
    }

    /**
     * Hàm xử lý đăng xuất
     */
    public function handleLogout()
    {
        // Xóa tất cả các biến session
        session_unset();
        // Hủy session
        session_destroy();
        // Chuyển hướng về trang đăng nhập
        header("Location: " . BASE_URL . 'public/login.php');
        exit();
    }

    //


    /**
     * Lấy danh sách người dùng cho trang admin.
     * @return array
     */
    public function listUsersForAdmin()
    {
        return $this->userModel->getAllUsers();
    }

    /**
     * Xử lý việc cập nhật trạng thái người dùng từ admin.
     */
    public function handleUpdateUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['current_status'])) {
            $userId = intval($_POST['user_id']);
            $currentStatus = $_POST['current_status'];

            // Logic đảo ngược trạng thái: nếu đang active thì khóa, và ngược lại
            $newStatus = ($currentStatus === 'active') ? 'locked' : 'active';

            $this->userModel->updateUserStatus($userId, $newStatus);
        }
        // Chuyển hướng về lại trang quản lý người dùng
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Xử lý các hành động quản trị (Add, Update, Status Toggle) trên người dùng.
     */
    public function handleAdminUserAction()
    {


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action_type = $_POST['action_type'] ?? 'add';
        $user_id = $_POST['userId'] ?? null;
        $redirect_url = 'users.php';
        $result = false;
        $message_content = '';
        $message_type = 'error';

        try {
            // XỬ LÝ CHUYỂN ĐỔI TRẠNG THÁI NHANH (Active <-> Locked)
            if ($action_type === 'status_toggle' && !empty($user_id)) {
                $current_status = $_POST['currentStatus'] ?? 'active';
                $newStatus = ($current_status === 'active') ? 'locked' : 'active';

                $result = $this->userModel->updateUserStatus($user_id, $newStatus);
                $action_text = ($newStatus === 'locked') ? 'Khóa' : 'Kích hoạt';
                $message_content = $result ? '✅ ' . $action_text . ' tài khoản thành công!' : '❌ Lỗi: ' . $action_text . ' tài khoản thất bại.';
            }
            // XỬ LÝ THÊM HOẶC CẬP NHẬT
            else {
                $birth_date_input = trim($_POST['birthDate'] ?? '');
                $birth_date_safe = empty($birth_date_input) ? NULL : $birth_date_input;

                $data = [
                    'username' => trim($_POST['username'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'full_name' => trim($_POST['fullName'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'birth_date' => $birth_date_safe,
                    'status' => $_POST['userStatus'] ?? 'active',
                    'rank' => $_POST['userRank'] ?? 'silver',
                ];

                // Validation cơ bản
                $error = '';
                if (empty($data['username']) || empty($data['email'])) {
                    $error = 'Vui lòng điền đầy đủ Tên đăng nhập và Email.';
                } else if ($action_type === 'add' && empty($_POST['password'])) {
                    $error = 'Vui lòng nhập mật khẩu cho người dùng mới.';
                }

                if (empty($error)) {
                    if ($action_type === 'add') {
                        // XỬ LÝ THÊM (registerUser)
                        $data['password'] = $_POST['password'];
                        $result = $this->userModel->createUser($data);
                        $message_content = $result ? '✅ Thêm người dùng thành công!' : '❌ Lỗi: Thêm người dùng thất bại (Có thể tên đăng nhập/email đã tồn tại).';
                    } else if ($action_type === 'update' && !empty($user_id)) {
                        // XỬ LÝ CẬP NHẬT (updateUser)
                        if (!empty($_POST['password'])) {
                            $data['password'] = $_POST['password']; // Cập nhật mật khẩu nếu có
                        }
                        $result = $this->userModel->updateUser($user_id, $data);
                        $message_content = $result ? '✅ Cập nhật người dùng thành công!' : '❌ Lỗi: Cập nhật người dùng thất bại.';
                    } else {
                        $message_content = '❌ Lỗi: Hành động không hợp lệ.';
                    }
                } else {
                    $message_content = '❌ Lỗi: ' . $error;
                }
            }
        } catch (\Exception $e) {
            $message_content = '❌ Lỗi hệ thống: ' . $e->getMessage();
        }

        // Lưu kết quả vào Session và REDIRECT
        $message_type = $result ? 'success' : 'error';
        $_SESSION['message'] = ['type' => $message_type, 'content' => $message_content];

        header("Location: " . $redirect_url);
        exit();
    }

    /**
     * Xử lý việc cập nhật thông tin hồ sơ người dùng.
     * @return array Mảng chứa thông báo ('type' và 'text').
     */
    public function handleUpdateProfile()
    {
        // Chỉ xử lý khi người dùng đã đăng nhập và gửi form
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'address'   => trim($_POST['address'] ?? '')
        ];

        // Xử lý cập nhật mật khẩu nếu người dùng nhập
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return ['type' => 'danger', 'text' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'];
            }
            if ($newPassword !== $confirmPassword) {
                return ['type' => 'danger', 'text' => 'Mật khẩu xác nhận không khớp.'];
            }
            $data['password'] = $newPassword; // Controller sẽ hash mật khẩu này
        }

        // Gọi Model để cập nhật thông tin
        if ($this->userModel->updateUser($userId, $data)) {
            // Cập nhật lại session nếu tên thay đổi
            $_SESSION['username'] = $data['full_name'];
            // ĐỒNG BỘ LẠI TOÀN BỘ PROFILE TRONG SESSION
            $_SESSION['user_profile']['full_name'] = $data['full_name'];
            $_SESSION['user_profile']['phone'] = $data['phone'];
            $_SESSION['user_profile']['address'] = $data['address'];

            return ['type' => 'success', 'text' => 'Cập nhật hồ sơ thành công!'];
        } else {
            return ['type' => 'danger', 'text' => 'Đã có lỗi xảy ra, vui lòng thử lại.'];
        }
    }


    /**
     * Xử lý yêu cầu quên mật khẩu.
     * @return array|null
     */
    public function handleForgotPassword()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null; // Chỉ xử lý khi người dùng nhấn nút gửi form
        }

        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['type' => 'danger', 'text' => 'Địa chỉ email không hợp lệ.'];
        }

        // Tìm người dùng trong CSDL bằng email
        $user = $this->userModel->findUserByEmail($email);
        if (!$user) {
            // Luôn trả về thông báo thành công để tránh hacker dò email
            return ['type' => 'success', 'text' => 'Nếu email của bạn tồn tại trong hệ thống, một hướng dẫn đặt lại mật khẩu đã được gửi đi.'];
        }

        // Tạo token ngẫu nhiên, an toàn và thời gian hết hạn
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token có hiệu lực trong 1 giờ

        // Lưu token vào bảng password_resets
        if (!$this->userModel->storePasswordResetToken($email, $token, $expires)) {
            return ['type' => 'danger', 'text' => 'Lỗi hệ thống, không thể tạo yêu cầu. Vui lòng thử lại.'];
        }

        // Tạo link để người dùng nhấp vào
        $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . BASE_URL . 'public/reset_password.php?token=' . $token;

        // Bắt đầu quá trình gửi email
        $mail = new PHPMailer(true);

        try {
            // ----- CẤU HÌNH SMTP - BẠN CẦN THAY THÔNG TIN CỦA MÌNH VÀO ĐÂY -----
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';         // Server mail của Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tranphong1318@gmail.com';   // Email Gmail của bạn
            $mail->Password   = 'hjau zrbz ykza gsea';      // **Mật khẩu ứng dụng** 16 ký tự
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';
            // --------------------------------------------------------------------

            // Người gửi và người nhận
            $mail->setFrom('tranphong1318@gmail.com', 'HugPog Store'); // Email và Tên người gửi
            $mail->addAddress($email, $user['full_name']);        // Email và Tên người nhận

            // Nội dung email
            $mail->isHTML(true);
            $mail->Subject = 'Yeu cau dat lai mat khau cho tai khoan HugPog'; // Tiêu đề không dấu
            $mail->Body    = "Xin chào " . htmlspecialchars($user['full_name']) . ",<br><br>"
                . "Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.<br>"
                . "Vui lòng nhấp vào liên kết dưới đây để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau 1 giờ.<br><br>"
                . "<a href='" . $resetLink . "' style='padding: 10px 15px; background-color: #D10024; color: white; text-decoration: none; border-radius: 5px;'><b>Đặt lại mật khẩu của tôi</b></a><br><br>"
                . "Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.<br><br>"
                . "Trân trọng,<br>Đội ngũ HugPog";
            $mail->AltBody = 'Để đặt lại mật khẩu, vui lòng truy cập link sau: ' . $resetLink; // Nội dung dạng text thuần

            $mail->send();
            return ['type' => 'success', 'text' => 'Nếu email của bạn tồn tại trong hệ thống, một hướng dẫn đặt lại mật khẩu đã được gửi đi.'];
        } catch (\Exception $e) { // Thêm dấu \ vào trước Exception
            // Ghi log lỗi để debug, không hiển thị chi tiết cho người dùng
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return ['type' => 'danger', 'text' => 'Không thể gửi email. Vui lòng liên hệ quản trị viên.'];
        }
    }


    /**
     * Xử lý việc đặt lại mật khẩu mới.
     * @param string $token
     * @return array|null
     */
    public function handleResetPassword($token)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirm'];

        if (empty($password) || empty($passwordConfirm)) {
            return ['type' => 'danger', 'text' => 'Vui lòng nhập đầy đủ mật khẩu.'];
        }
        if (strlen($password) < 6) {
            return ['type' => 'danger', 'text' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
        }
        if ($password !== $passwordConfirm) {
            return ['type' => 'danger', 'text' => 'Mật khẩu xác nhận không khớp.'];
        }

        $resetRequest = $this->userModel->findPasswordResetByToken($token);
        if (!$resetRequest) {
            // Token không hợp lệ hoặc đã hết hạn
            return ['type' => 'danger', 'text' => 'Yêu cầu không hợp lệ hoặc đã hết hạn. Vui lòng thử lại.'];
        }

        // Cập nhật mật khẩu trong bảng users
        if ($this->userModel->updateUserPassword($resetRequest['email'], $password)) {
            // Xóa token đã sử dụng
            $this->userModel->deletePasswordResetToken($token);
            $_SESSION['success_message'] = 'Đặt lại mật khẩu thành công! Bây giờ bạn có thể đăng nhập.';
            header('Location: ' . BASE_URL . 'public/login.php');
            exit();
        } else {
            return ['type' => 'danger', 'text' => 'Đã có lỗi xảy ra. Vui lòng thử lại.'];
        }
    }
}
