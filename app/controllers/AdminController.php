<?php
require_once 'app/helpers/SessionHelper.php';
require_once 'app/models/BookModel.php';
require_once 'app/models/AccountModel.php';
require_once 'app/models/SubjectModel.php';
require_once 'app/models/LoanModel.php';
require_once 'app/models/ReservationModel.php';

class AdminController {
    private $db;
    private $bookModel;
    private $accountModel;
    private $subjectModel;
    private $loanModel;
    private $reservationModel;

    public function __construct($db) {
        $this->db = $db;
        $this->bookModel = new BookModel($db);
        $this->accountModel = new AccountModel($db);
        $this->subjectModel = new SubjectModel($db);
        $this->loanModel = new LoanModel($db);
        $this->reservationModel = new ReservationModel($db);

        SessionHelper::start();
        // Bảo vệ toàn bộ controller, đảm bảo chỉ admin mới có thể truy cập
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    /**
     * Chuẩn bị dữ liệu và hiển thị trang Bảng điều khiển (Dashboard)
     */
    public function dashboard() {
        // 1. Lấy các số liệu thống kê chính
        // Ghi chú: Bạn cần tạo các phương thức này trong Model tương ứng
        $totalBooks = $this->bookModel->countTotalBooks() ?? 0;
        $activeMembers = $this->accountModel->countActiveMembers() ?? 0;
        $loansCount = $this->loanModel->countCurrentLoans() ?? 0;
        $overdueCount = $this->loanModel->countOverdueBooks() ?? 0;

        // 2. Lấy dữ liệu cho biểu đồ (ví dụ: 7 ngày gần nhất)
        // Ghi chú: Bạn cần tạo phương thức getLoanStatsForChart() trong LoanModel
        $chartData = $this->loanModel->getLoanStatsForChart();
        // Nếu chưa có dữ liệu thật, dùng dữ liệu mẫu để giao diện không bị lỗi
        if (empty($chartData)) {
            $chartData = [
                'labels' => ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                'loans' => [5, 8, 3, 10, 7, 12, 9],
                'returns' => [3, 6, 2, 8, 5, 10, 7]
            ];
        }
        $chartDataJson = json_encode($chartData);

        // 3. Lấy các hoạt động gần đây
        // Ghi chú: Bạn cần tạo phương thức getRecentActivities() trong LoanModel
        $recentActivities = $this->loanModel->getRecentActivities(5); // Lấy 5 hoạt động mới nhất

        // 4. Lấy sách được mượn nhiều nhất
        // Ghi chú: Bạn cần tạo phương thức getPopularBooks() trong BookModel
        $popularBooks = $this->bookModel->getPopularBooks(3); // Lấy 3 sách phổ biến nhất

        // 5. Load view và truyền tất cả dữ liệu sang
        ob_start();
        include 'app/views/admin/dashboard.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
