<?php
// Sử dụng các lớp từ thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Nạp các file cần thiết
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/database/Database.php';
require_once 'vendor/autoload.php'; // Nạp thư viện Composer (cho PHPMailer)

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        SessionHelper::start();
    }

    /**
     * Hiển thị form đăng ký (GET) và xử lý đăng ký (POST).
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý dữ liệu từ form
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $email = trim($_POST['email']);
            $fullname = trim($_POST['fullname']);
            $confirmPassword = $_POST['confirmpassword'];
            $role = (SessionHelper::isAdmin() && isset($_POST['role']) && $_POST['role'] === 'admin') ? 'admin' : 'member';

            // --- Kiểm tra dữ liệu đầu vào ---
            $errors = [];
            if (empty($username)) $errors[] = "Vui lòng nhập tên đăng nhập!";
            if (empty($password)) $errors[] = "Vui lòng nhập mật khẩu!";
            if ($password != $confirmPassword) $errors[] = "Mật khẩu và xác nhận chưa khớp!";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ!";
            if ($this->accountModel->getAccountByUsername($username)) $errors[] = "Tên đăng nhập này đã tồn tại!";
            
            if (!empty($errors)) {
                $_SESSION['registration_errors'] = $errors;
                $_SESSION['POST_data'] = $_POST;
                header('Location: /account/register');
                exit;
            }
            // --- Kết thúc kiểm tra ---

            if ($role === 'admin') {
                // TẠO TÀI KHOẢN ADMIN: Kích hoạt ngay
                try {
                    $this->accountModel->createAccount($username, $password, $email, $fullname, null, null, 1, 'admin');
                    $_SESSION['success_message'] = "Tạo tài khoản quản trị viên thành công!";
                    header('Location: /Account/manage');
                    exit();
                } catch (Exception $e) {
                    error_log("Lỗi tạo tài khoản admin: " . $e->getMessage());
                    die("Có lỗi xảy ra khi tạo tài khoản quản trị.");
                }
            } else {
                // TẠO TÀI KHOẢN MEMBER: Yêu cầu xác thực email
                $token = bin2hex(random_bytes(50));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                try {
                    $this->accountModel->createAccount($username, $password, $email, $fullname, $token, $expiry, 0, 'member');
                    $this->sendVerificationEmail($email, $token);

                    // SỬA LỖI: Chuyển hướng đến trang chờ xác thực
                    $_SESSION['verification_email'] = $email;
                    header('Location: /account/verificationSent');
                    exit();
                } catch (Exception $e) {
                    error_log("Lỗi đăng ký member: " . $e->getMessage());
                    die("Đã có lỗi xảy ra trong quá trình đăng ký.");
                }
            }
        }
        
        // Nếu là GET request, hiển thị trang đăng ký
        ob_start();
        include 'app/views/account/register.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/public_layout.php';
    }

    /**
     * Hiển thị trang thông báo đã gửi email xác thực.
     */
    public function verificationSent() {
        ob_start();
        include 'app/views/account/verification_sent.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/public_layout.php';
    }

    /**
     * Xử lý yêu cầu xác thực và hiển thị giao diện thông báo.
     */
    public function verify() {
        $token = $_GET['token'] ?? '';
        $header_text = '';
        $message = '';
        $icon_class = '';
        $icon_color = '';

        if (empty($token)) {
            $header_text = 'Lỗi Xác thực';
            $message = 'Yêu cầu không hợp lệ do không tìm thấy token xác thực.';
            $icon_class = 'fas fa-times-circle';
            $icon_color = '#dc3545';
        } else {
            $user = $this->accountModel->findAccountByToken($token);
            if ($user && strtotime($user->token_expiry) > time()) {
                $this->accountModel->verifyAccount($user->id);
                $header_text = 'Thành Công!';
                $message = 'Tài khoản của bạn đã được xác thực. Bây giờ bạn có thể đăng nhập.';
                $icon_class = 'fas fa-check-circle';
                $icon_color = '#198754';
            } else {
                $header_text = 'Xác thực Thất bại';
                $message = 'Liên kết xác thực này không hợp lệ hoặc đã hết hạn. Vui lòng thử đăng ký lại.';
                $icon_class = 'fas fa-exclamation-triangle';
                $icon_color = '#ffc107';
            }
        }
        
        // Load giao diện thông báo (trang độc lập, không cần layout)
        include 'app/views/account/verify_status.php';
        exit();
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function login() {
        ob_start();
        include 'app/views/account/login.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/public_layout.php';
    }

    /**
     * Xử lý logic đăng nhập
     */
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password)) {
                if ($account->is_verified == 0) {
                    $error = "Tài khoản của bạn chưa được xác thực. Vui lòng kiểm tra email.";
                    header('Location: /account/login?error=' . urlencode($error));
                    exit();
                }

                $_SESSION['user_id'] = $account->id;
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;

                if (SessionHelper::isAdmin()) {
                    header('Location: /Admin/dashboard');
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                header('Location: /account/login');
                exit;
            }
        }
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    // ... (Các phương thức quản lý khác như profile, manage, edit, delete... giữ nguyên) ...

    /**
     * Gửi email xác thực (phương thức nội bộ)
     */
    private function sendVerificationEmail($email, $token) {
        $mail = new PHPMailer(true);
        $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/account/verify?token=$token";

        try {
            // --- THAY THÔNG TIN CỦA BẠN VÀO ĐÂY ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';
            $mail->Password   = 'your_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('your_email@gmail.com', 'Thư viện LIBSMART');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Xác thực tài khoản LIBSMART';
            $mail->Body    = "Chào bạn,<br><br>Cảm ơn bạn đã đăng ký. Vui lòng nhấn vào liên kết dưới đây để xác thực tài khoản:<br><br><a href='$verification_link' style='padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Xác thực tài khoản</a><br><br>Trân trọng,<br>Đội ngũ LIBSMART";
            $mail->AltBody = "Vui lòng truy cập liên kết sau để xác thực: $verification_link";

            $mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: {$mail->ErrorInfo}");
        }
    }
}
