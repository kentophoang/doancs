<?php
class SessionHelper {
    // Khởi động session nếu chưa bắt đầu
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Kiểm tra người dùng đã đăng nhập chưa
    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['username']);
    }
    public static function getCart() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    // Kiểm tra người dùng có phải admin không
    public static function isAdmin() {
        self::start();
        return isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Kiểm tra quyền chỉnh sửa sản phẩm & danh mục (chỉ admin)
    public static function canEdit() {
        return self::isAdmin(); // Chỉ admin mới có quyền sửa
    }

    // Lấy vai trò của người dùng, mặc định là 'guest'
    public static function getRole() {
        self::start();
        return $_SESSION['role'] ?? 'guest';
    }
}
?>