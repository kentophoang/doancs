<?php
// Tệp này sẽ bao gồm header và footer
include 'app/views/shares/header.php';
?>

<div class="container-fluid admin-layout-wrapper">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark-sidebar sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/Admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === '/Admin') ? 'active' : '' ?>" aria-current="page" href="/Admin/dashboard">
                            <i class="fas fa-home"></i> Bảng điều khiển
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/Book') !== false && strpos($_SERVER['REQUEST_URI'], '/Book/myBorrowedBooks') === false && strpos($_SERVER['REQUEST_URI'], '/Book/add') === false && strpos($_SERVER['REQUEST_URI'], '/Book/edit') === false) ? 'active' : '' ?>" href="/Book/">
                            <i class="fas fa-book"></i> Quản lý sách
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/Subject') !== false) ? 'active' : '' ?>" href="/Subject/index">
                            <i class="fas fa-tags"></i> Quản lý chủ đề
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/Account/manage') !== false) ? 'active' : '' ?>" href="/Account/manage">
                            <i class="fas fa-users"></i> Quản lý thành viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Loan/manage"> <i class="fas fa-history"></i> Lưu hành sách
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Reservation/manage"> <i class="fas fa-calendar-alt"></i> Đặt trước
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Analytics/view"> <i class="fas fa-chart-line"></i> Phân tích
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Report/view"> <i class="fas fa-file-alt"></i> Báo cáo
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/Overdue/list"> <i class="fas fa-exclamation-circle"></i> Quá hạn
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/Upcoming/list"> <i class="fas fa-calendar-check"></i> Sắp ra mắt <span class="badge badge-danger ml-2">NEW</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Settings/edit"> <i class="fas fa-cog"></i> Cài đặt
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <?php echo $main_content; ?>
        </main>
    </div>
</div>

<?php
// Footer được bao gồm sau khi nội dung chính được hiển thị
include 'app/views/shares/footer.php';
?>

<style>
    body {
        background-color: #f8f9fc;
        font-family: 'Arial', sans-serif;
    }
    .admin-layout-wrapper {
        padding-top: 56px; /* Offset for the fixed header height */
        display: flex;
        min-height: calc(100vh - 56px); /* Adjust to fill remaining viewport height */
        box-sizing: border-box; /* Include padding in element's total width and height */
    }
    .row {
        width: 100%;
        margin: 0;
        flex-wrap: nowrap; /* Prevent wrapping columns on smaller screens if sidebar is fixed */
    }
    .sidebar {
        height: calc(100vh - 56px); /* Adjusted for fixed header height */
        background-color: #2c3e50 !important; /* Darker blue-gray from screenshot */
        color: white;
        padding-top: 20px;
        position: fixed;
        top: 56px; /* Position below the fixed header */
        bottom: 0;
        left: 0;
        z-index: 1000;
        padding-right: 0;
        padding-left: 0;
        overflow-y: auto; /* Enable scrolling for long sidebars */
    }
    .sidebar .nav-item {
        width: 100%;
    }
    .sidebar .nav-link {
        color: #ecf0f1 !important;
        padding: 10px 15px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        border-radius: 0;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        color: white !important;
        background-color: #3498db;
    }
    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    main {
        padding-top: 20px;
        margin-left: calc(16.666667% + 15px); /* Offset for sidebar width (col-md-2) + some margin */
        width: calc(100% - 16.666667% - 15px); /* Adjust main content width */
    }
    /* General Admin Card Styles */
    .card {
        border-left: .25rem solid !important;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .card-body {
        padding: 1.25rem;
    }
    .text-xs {
        font-size: 0.7rem;
    }
    .font-weight-bold {
        font-weight: 700 !important;
    }
    /* Custom colors to match screenshot */
    .text-primary { color: #4e73df !important; }
    .text-success { color: #1cc88a !important; }
    .text-info { color: #36b9cc !important; }
    .text-warning { color: #f6c23e !important; }
    .text-danger { color: #e74a3b !important; }
    .text-blue { color: #3498db !important; }
    .text-dark-blue { color: #2c3e50 !important; }


    .border-left-primary { border-left-color: #4e73df !important; }
    .border-left-success { border-left-color: #1cc88a !important; }
    .border-left-info { border-left-color: #36b9cc !important; }
    .border-left-warning { border-left-color: #f6c23e !important; }
    .border-left-danger { border-left-color: #e74a3b !important; }
    .border-left-blue { border-left-color: #3498db !important; }


    .h5.mb-0 {
        font-size: 1.25rem;
    }
    .fa-2x {
        font-size: 2em;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .list-group-item {
        border: 1px solid rgba(0,0,0,.125);
        margin-bottom: -1px;
        transition: background-color 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f0f2f5;
    }
    .list-group-item:first-child {
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }
    .list-group-item:last-child {
        margin-bottom: 0;
        border-bottom-right-radius: .25rem;
        border-bottom-left-radius: .25rem;
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .media img {
        border: 1px solid #eee;
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
    }
    .badge-danger {
        background-color: #e74a3b;
        color: white;
    }
</style>