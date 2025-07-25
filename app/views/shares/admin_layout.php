<?php
// Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 3)); // Đi lên 3 cấp từ /app/views/shares
}

// Khởi tạo session và kiểm tra quyền admin
require_once ROOT_PATH . '/app/helpers/SessionHelper.php';
SessionHelper::start();

if (!SessionHelper::isAdmin()) {
    header('Location: /account/login');
    exit();
}

// Nhúng header chung bằng đường dẫn tuyệt đối
include_once ROOT_PATH . '/app/views/shares/header.php';

// Xác định trang hiện tại để làm nổi bật link trên sidebar
$request_uri = $_SERVER['REQUEST_URI'];
$current_page = '';
if (strpos($request_uri, '/Admin/dashboard') !== false) $current_page = 'dashboard';
elseif (strpos($request_uri, '/admin/book') !== false || strpos($request_uri, '/Book/edit') !== false || strpos($request_uri, '/Book/add') !== false) $current_page = 'book';
elseif (strpos($request_uri, '/Subject') !== false) $current_page = 'subject';
elseif (strpos($request_uri, '/Account/manage') !== false) $current_page = 'account';
elseif (strpos($request_uri, '/Loan/manage') !== false) $current_page = 'loan';
elseif (strpos($request_uri, '/Reservation/manage') !== false) $current_page = 'reservation';
elseif (strpos($request_uri, '/Overdue/list') !== false) $current_page = 'overdue';
?>

<!-- Wrapper chính cho bố cục admin -->
<div id="admin-wrapper">
    <!-- Thanh bên (Sidebar) -->
    <nav id="sidebar" class="bg-dark-sidebar">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard') ? 'active' : '' ?>" href="/Admin/dashboard">
                        <i class="fas fa-home fa-fw"></i><span>Bảng điều khiển</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'book') ? 'active' : '' ?>" href="/admin/book">
                        <i class="fas fa-book fa-fw"></i><span>Quản lý sách</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'subject') ? 'active' : '' ?>" href="/Subject/index">
                        <i class="fas fa-tags fa-fw"></i><span>Quản lý chủ đề</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'account') ? 'active' : '' ?>" href="/Account/manage">
                        <i class="fas fa-users fa-fw"></i><span>Quản lý thành viên</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'loan') ? 'active' : '' ?>" href="/Loan/manage">
                        <i class="fas fa-history fa-fw"></i><span>Lưu hành sách</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'reservation') ? 'active' : '' ?>" href="/Reservation/manage">
                        <i class="fas fa-calendar-alt fa-fw"></i><span>Đặt trước</span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'overdue') ? 'active' : '' ?>" href="/Overdue/list">
                        <i class="fas fa-exclamation-circle fa-fw"></i><span>Quá hạn</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Khu vực nội dung chính -->
    <main id="main-content">
        <div class="container-fluid p-4">
            <?php 
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    unset($_SESSION['success_message']);
                }
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    unset($_SESSION['error_message']);
                }
                echo $main_content ?? ''; 
            ?>
        </div>
    </main>
</div>

<?php
// Nhúng footer chung bằng đường dẫn tuyệt đối
include_once ROOT_PATH . '/app/views/shares/footer.php';
?>

<!-- CSS và JavaScript cho thanh bên có thể thu gọn -->
<style>
    body { overflow-x: hidden; }
    #admin-wrapper { display: flex; width: 100%; min-height: calc(100vh - 56px); }
    #sidebar {
        width: 250px; min-width: 250px;
        background-color: #2c3e50; color: white;
        transition: all 0.3s ease-in-out;
    }
    #sidebar .nav-link {
        color: #ecf0f1; padding: 12px 20px;
        transition: all 0.3s ease; display: flex;
        align-items: center; white-space: nowrap;
    }
    #sidebar .nav-link i {
        margin-right: 15px; width: 24px;
        text-align: center; font-size: 1.1rem;
    }
    #sidebar .nav-link:hover, #sidebar .nav-link.active {
        background-color: #3498db; color: white;
    }
    #main-content {
        flex-grow: 1; width: calc(100% - 250px);
        background-color: #f8f9fc; overflow-y: auto;
        transition: all 0.3s ease-in-out;
    }
    /* Trạng thái khi thu gọn */
    #admin-wrapper.sidebar-toggled #sidebar { width: 90px; min-width: 90px; }
    #admin-wrapper.sidebar-toggled #sidebar .nav-link span { display: none; }
    #admin-wrapper.sidebar-toggled #sidebar .nav-link { justify-content: center; }
    #admin-wrapper.sidebar-toggled #sidebar .nav-link i { margin-right: 0; font-size: 1.3rem; }
    #admin-wrapper.sidebar-toggled #main-content { width: calc(100% - 90px); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminWrapper = document.getElementById('admin-wrapper');

    if (localStorage.getItem('sidebarToggled') === 'true') {
        adminWrapper.classList.add('sidebar-toggled');
    }

    if (sidebarToggle && adminWrapper) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            adminWrapper.classList.toggle('sidebar-toggled');
            localStorage.setItem('sidebarToggled', adminWrapper.classList.contains('sidebar-toggled'));
        });
    }
});
</script>
