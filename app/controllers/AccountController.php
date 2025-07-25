<?php
// require_once('app/config/database.php'); // Không cần AppDatabase nếu dùng Database.php
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/database/Database.php'); // Đảm bảo đúng đường dẫn

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection(); // Sử dụng lớp Database chính
        $this->accountModel = new AccountModel($this->db);
        SessionHelper::start();
    }

    public function register() {
        // Chỉ cho phép admin thêm tài khoản mới từ trang quản lý, hoặc người dùng tự đăng ký
        if (SessionHelper::isAdmin() || !SessionHelper::isLoggedIn()) {
             // Để biến $errors có sẵn trong view nếu có lỗi từ save() redirect về
            $errors = $_SESSION['registration_errors'] ?? [];
            unset($_SESSION['registration_errors']); // Clear after showing
            include_once 'app/views/account/register.php';
        } else {
            // Nếu người dùng đã đăng nhập và không phải admin, không cho phép truy cập trang đăng ký
            header('Location: /'); // Hoặc trang lỗi phù hợp
            exit();
        }
    }

    public function login() {
        // Biến $_SESSION['login_error'] đã được bạn xử lý trong view
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user'; // Mặc định là 'user'
            $profession = $_POST['profession'] ?? null;
            $industry = $_POST['industry'] ?? null;

            $errors = [];
            if (empty($username)) $errors[] = "Vui lòng nhập tên đăng nhập!";
            if (empty($fullName)) $errors[] = "Vui lòng nhập họ và tên!";
            if (empty($password)) $errors[] = "Vui lòng nhập mật khẩu!";
            if ($password != $confirmPassword) $errors[] = "Mật khẩu và xác nhận chưa khớp!";

            // Chỉ cho phép admin chọn vai trò khác 'user'
            if (!SessionHelper::isAdmin() && $role !== 'user') {
                 $errors[] = "Bạn không có quyền đăng ký với vai trò này.";
                 $role = 'user'; // Đảm bảo vai trò luôn là 'user' nếu không phải admin
            }

            if ($this->accountModel->getAccountByUsername($username)) {
                $errors[] = "Tài khoản này đã được đăng ký!";
            }

            if (count($errors) > 0) {
                $_SESSION['registration_errors'] = $errors; // Lưu lỗi vào session
                $_SESSION['POST_data'] = $_POST; // Giữ lại dữ liệu đã nhập
                header('Location: /account/register'); // Chuyển hướng về trang đăng ký
                exit;
            } else {
                $result = $this->accountModel->save($username, $fullName, $password, $role, $profession, $industry);

                if ($result) {
                    $_SESSION['success_message'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập."; // Thông báo thành công
                    header('Location: /account/login');
                    exit;
                } else {
                    $errors[] = "Đã xảy ra lỗi khi đăng ký tài khoản. Vui lòng thử lại.";
                    $_SESSION['registration_errors'] = $errors;
                    $_SESSION['POST_data'] = $_POST;
                    header('Location: /account/register');
                    exit;
                }
            }
        }
    }

    public function logout() {
        // Unset tất cả các biến session
        $_SESSION = array();

        // Xóa cookie session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Hủy session
        session_destroy();
        header('Location: /');
        exit;
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password)) {
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;
                $_SESSION['user_id'] = $account->id;

                if (SessionHelper::isAdmin()) {
                    header('Location: /Admin/dashboard');
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                $_SESSION['login_error'] = $error;
                header('Location: /account/login');
                exit;
            }
        }
    }

    public function profile() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $username = $_SESSION['username'];
        $account = $this->accountModel->getAccountByUsername($username);
        if ($account) {
            include 'app/views/account/profile.php';
        } else {
            // Thay đổi cách xử lý lỗi, tránh die()
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php'; // Chuyển hướng đến trang 404
            exit();
        }
    }

    public function updateProfile() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $fullName = $_POST['fullname'] ?? '';
            $profession = $_POST['profession'] ?? null;
            $industry = $_POST['industry'] ?? null;
            
            $updateResult = $this->accountModel->updateAccount($userId, $fullName, $profession, $industry);

            if ($updateResult) {
                // Sử dụng flash message hoặc thông báo trong session
                $_SESSION['success_message'] = 'Cập nhật hồ sơ thành công!';
                header('Location: /account/profile');
                exit();
            } else {
                $_SESSION['error_message'] = 'Cập nhật hồ sơ thất bại! Vui lòng thử lại.';
                header('Location: /account/profile');
                exit();
            }
        }
    }

    public function manage() {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login'); // Chuyển hướng về trang đăng nhập nếu không phải admin
            exit();
        }

        $searchTerm = $_GET['search'] ?? null;
        $sortBy = $_GET['sort'] ?? null;
        $status = $_GET['status'] ?? null; 

        $accounts = $this->accountModel->getAllAccounts($searchTerm, $sortBy, $status);
        
        ob_start(); // Bắt đầu bộ đệm đầu ra
        include 'app/views/account/manage.php';
        $main_content = ob_get_clean(); // Lấy nội dung đã đệm

        include 'app/views/shares/admin_layout.php'; // Chèn vào admin layout
    }

    public function view($id) { // Phương thức mới để xem chi tiết tài khoản
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $account = $this->accountModel->getAccountById($id);
        if (!$account) {
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php';
            exit();
        }

        ob_start();
        include 'app/views/account/view.php'; // Cần tạo view này
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function edit($id) { // Phương thức mới để chỉnh sửa tài khoản (admin)
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $account = $this->accountModel->getAccountById($id);
        if (!$account) {
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php';
            exit();
        }

        // Xử lý POST request cho việc cập nhật vai trò/thông tin khác của tài khoản
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = $_POST['fullname'] ?? '';
            $profession = $_POST['profession'] ?? null;
            $industry = $_POST['industry'] ?? null;
            $role = $_POST['role'] ?? $account->role; // Lấy vai trò mới, mặc định là vai trò cũ

            $errors = [];
            // Thêm validation nếu cần

            if (empty($errors)) {
                $updateResult = $this->accountModel->updateAccount($id, $fullName, $profession, $industry);
                $updateRoleResult = $this->accountModel->updateAccountRole($id, $role);

                if ($updateResult && $updateRoleResult) {
                    $_SESSION['success_message'] = 'Cập nhật tài khoản thành công!';
                    header('Location: /Account/manage');
                    exit();
                } else {
                    $_SESSION['error_message'] = 'Cập nhật tài khoản thất bại! Vui lòng thử lại.';
                }
            } else {
                $_SESSION['error_message'] = implode("<br>", $errors);
            }
            header("Location: /Account/edit/{$id}"); // Redirect lại trang edit để hiển thị lỗi/thành công
            exit();
        }

        ob_start();
        include 'app/views/account/edit.php'; // Cần tạo view này
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function delete($id) { // Phương thức mới để xóa tài khoản
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        
        // Kiểm tra không cho phép tự xóa tài khoản của mình
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không thể tự xóa tài khoản của mình!';
            header('Location: /Account/manage');
            exit();
        }

        if ($this->accountModel->deleteAccount($id)) {
            $_SESSION['success_message'] = 'Xóa tài khoản thành công!';
            header('Location: /Account/manage');
            exit();
        } else {
            $_SESSION['error_message'] = 'Xóa tài khoản thất bại! Vui lòng thử lại.';
            header('Location: /Account/manage');
            exit();
        }
    }
}