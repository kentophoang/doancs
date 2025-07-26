<?php
// Sử dụng các lớp từ thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// SỬA LỖI: Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

require_once ROOT_PATH . '/app/models/AccountModel.php';

class AccountController {
    private $accountModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->accountModel = new AccountModel($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $email = trim($_POST['email']);
            $fullname = trim($_POST['fullname']);
            $confirmPassword = $_POST['confirmpassword'];
            $role = (SessionHelper::isAdmin() && isset($_POST['role']) && $_POST['role'] === 'admin') ? 'admin' : 'member';

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

            if ($role === 'admin') {
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
                $token = bin2hex(random_bytes(50));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                try {
                    $this->accountModel->createAccount($username, $password, $email, $fullname, $token, $expiry, 0, 'member');
                    $this->sendVerificationEmail($email, $token);

                    // --- CẬP NHẬT CHO DEMO ---
                    // Lưu email VÀ TOKEN vào session để trang sau có thể sử dụng
                    $_SESSION['verification_email'] = $email;
                    $_SESSION['verification_token_for_demo'] = $token; 

                    // Chuyển hướng đến trang thông báo chờ xác thực
                    header('Location: /account/verificationSent');
                    exit();
                } catch (Exception $e) {
                    error_log("Lỗi đăng ký member: " . $e->getMessage());
                    die("Đã có lỗi xảy ra trong quá trình đăng ký.");
                }
            }
        }
        
        ob_start();
        include ROOT_PATH . '/app/views/account/register.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    // ... (Các phương thức khác như login, verify, manage... giữ nguyên)
    public function verificationSent() {
        ob_start();
        include ROOT_PATH . '/app/views/account/verification_sent.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

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
        
        include ROOT_PATH . '/app/views/account/verify_status.php';
        exit();
    }

    public function login() {
        ob_start();
        include ROOT_PATH . '/app/views/account/login.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

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

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function profile() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $account = $this->accountModel->getAccountById($_SESSION['user_id']);
        if (!$account) {
            show_404();
        }
        
        ob_start();
        include ROOT_PATH . '/app/views/account/profile.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    public function updateProfile() {
        if (!SessionHelper::isLoggedIn() || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /account/login');
            exit();
        }
       
        $userId = $_SESSION['user_id'];
        $fullName = $_POST['fullname'] ?? '';
        $profession = $_POST['profession'] ?? null;
        $industry = $_POST['industry'] ?? null;
        
        if ($this->accountModel->updateAccount($userId, $fullName, $profession, $industry)) {
            $_SESSION['success_message'] = 'Cập nhật hồ sơ thành công!';
        } else {
            $_SESSION['error_message'] = 'Cập nhật hồ sơ thất bại!';
        }
        header('Location: /account/profile');
        exit();
    }

    public function manage() {
        if (!SessionHelper::isAdmin()) {
            header('Location: /');
            exit();
        }

        $searchTerm = $_GET['search'] ?? null;
        $sortBy = $_GET['sort'] ?? null;
        $status = $_GET['status'] ?? null;

        $accounts = $this->accountModel->getAllAccounts($searchTerm, $sortBy, $status);
        
        ob_start();
        include ROOT_PATH . '/app/views/account/manage.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    public function view($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: /');
            exit();
        }
        $account = $this->accountModel->getAccountById($id);
        if (!$account) {
            show_404();
        }

        ob_start();
        include ROOT_PATH . '/app/views/account/view.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }
    
    public function edit($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: /');
            exit();
        }
        
        $account = $this->accountModel->getAccountById($id);
        if (!$account) {
            show_404();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role = $_POST['role'] ?? 'member';
            $this->accountModel->updateAccountRole($id, $role);
            $_SESSION['success_message'] = 'Cập nhật vai trò thành công!';
            header('Location: /Account/manage');
            exit();
        }

        ob_start();
        include ROOT_PATH . '/app/views/account/edit.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    public function delete($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: /');
            exit();
        }
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không thể tự xóa tài khoản của mình!';
            header('Location: /Account/manage');
            exit();
        }

        if ($this->accountModel->deleteAccount($id)) {
            $_SESSION['success_message'] = 'Xóa tài khoản thành công!';
        } else {
            $_SESSION['error_message'] = 'Xóa tài khoản thất bại!';
        }
        header('Location: /Account/manage');
        exit();
    }

    private function sendVerificationEmail($email, $token) {
        // ... (phần này giữ nguyên)
    }
}
