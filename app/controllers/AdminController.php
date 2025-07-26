<?php
// Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// Nạp các file Model cần thiết
require_once ROOT_PATH . '/app/helpers/SessionHelper.php';
require_once ROOT_PATH . '/app/models/BookModel.php';
require_once ROOT_PATH . '/app/models/AccountModel.php';
require_once ROOT_PATH . '/app/models/SubjectModel.php';
require_once ROOT_PATH . '/app/models/LoanModel.php';

class AdminController {
    private $db;
    private $bookModel;
    private $accountModel;
    private $subjectModel;
    private $loanModel;

    public function __construct($db) {
        $this->db = $db;
        $this->bookModel = new BookModel($db);
        $this->accountModel = new AccountModel($db);
        $this->subjectModel = new SubjectModel($db);
        $this->loanModel = new LoanModel($db);

        // Bảo vệ toàn bộ controller, chỉ admin mới có thể truy cập
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function dashboard() {
        // ... (Logic cho trang dashboard của bạn giữ nguyên)
        $totalBooks = $this->bookModel->countTotalBooks() ?? 0;
        $activeMembers = $this->accountModel->countActiveMembers() ?? 0;
        $loansCount = $this->loanModel->countCurrentLoans() ?? 0;
        $overdueCount = $this->loanModel->countOverdueBooks() ?? 0;
        $chartData = $this->loanModel->getLoanStatsForChart();
        $chartDataJson = json_encode($chartData);
        $recentActivities = $this->loanModel->getRecentActivities(5);
        $popularBooks = $this->bookModel->getPopularBooks(3);
        $bookCountBySubject = $this->bookModel->getBookCountBySubject();
        $bookCountBySubjectJson = json_encode($bookCountBySubject);

        ob_start();
        include ROOT_PATH . '/app/views/admin/dashboard.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * THÊM MỚI: Phương thức để hiển thị trang quản lý sách.
     * Sẽ được gọi khi truy cập /admin/book
     */
    public function book() {
        // Lấy các tham số lọc từ URL
        $subjectId = $_GET['subject_id'] ?? null;
        $minYear = $_GET['min_year'] ?? null;
        $maxYear = $_GET['max_year'] ?? null;
        $searchTerm = $_GET['search'] ?? null;
        $sortOrder = $_GET['sort'] ?? null;

        // Lấy dữ liệu từ các model
        $books = $this->bookModel->getBooks($subjectId, $minYear, $maxYear, $searchTerm, $sortOrder);
        $subjects = $this->subjectModel->getSubjects();

        // Nạp giao diện quản lý sách
        ob_start();
        include ROOT_PATH . '/app/views/book/list.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }
}
