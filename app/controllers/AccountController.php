<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() { // Không nhận $db ở đây nếu bạn muốn khởi tạo Database bên trong
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $profession = $_POST['profession'] ?? null;
            $industry = $_POST['industry'] ?? null;

            $errors = [];
            if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ và tên!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";

            if (!in_array($role, ['admin', 'user'])) $role = 'user';
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $result = $this->accountModel->save($username, $fullName, $password, $role, $profession, $industry);

                if ($result) {
                    header('Location: /account/login');
                    exit;
                } else {
                    $errors['db_error'] = "Đã xảy ra lỗi khi đăng ký tài khoản. Vui lòng thử lại.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    public function logout() {
 
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['user_id']); // Xóa user_id khi logout
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

                header('Location: /Book');
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
  // Bắt đầu session nếu chưa bắt đầu
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
            header('Location: /account/login');
            exit();
        }
        $username = $_SESSION['username'];
        $account = $this->accountModel->getAccountByUsername($username);
        if ($account) {
            include 'app/views/account/profile.php';
        } else {
            echo "Không tìm thấy thông tin hồ sơ.";
        }
    }

    public function updateProfile() {
  // Bắt đầu session nếu chưa bắt đầu
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
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
                header('Location: /account/profile');
                exit();
            } else {
                echo "<script>alert('Cập nhật hồ sơ thất bại!'); window.location.href='/account/profile';</script>";
            }
        }
    }
}
?>