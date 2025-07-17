<?php
require_once('app/config/database.php'); // Uses AppDatabase if needed, but the main Database class is in app/database/Database.php
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection(); // Ensure it uses the main Database class
        $this->accountModel = new AccountModel($this->db);
        SessionHelper::start();
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
        unset($_SESSION['user_id']);
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
                    header('Location: /Admin/dashboard'); // Redirect admin to dashboard
                } else {
                    header('Location: /Book'); // Redirect regular users to book list
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
            echo "Không tìm thấy thông tin hồ sơ.";
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
                header('Location: /account/profile');
                exit();
            } else {
                echo "<script>alert('Cập nhật hồ sơ thất bại!'); window.location.href='/account/profile';</script>";
            }
        }
    }

    // New method for managing accounts (admin view)
    public function manage() {
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            die("Bạn không có quyền quản lý thành viên."); // Access denied if not admin
        }

        $searchTerm = $_GET['search'] ?? null;
        $sortBy = $_GET['sort'] ?? null;
        $status = $_GET['status'] ?? null; // Assuming a 'status' filter might be added later

        $accounts = $this->accountModel->getAllAccounts($searchTerm, $sortBy, $status);
        
        include 'app/views/account/manage.php';
    }
}