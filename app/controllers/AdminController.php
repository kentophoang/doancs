<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/BookModel.php');
require_once('app/models/AccountModel.php');
require_once('app/models/SubjectModel.php');
require_once('app/models/LoanModel.php'); // Thêm LoanModel
require_once('app/models/ReservationModel.php'); // Thêm ReservationModel

class AdminController {
    private $db;
    private $bookModel;
    private $accountModel;
    private $subjectModel;
    private $loanModel; // Khai báo
    private $reservationModel; // Khai báo

    public function __construct($db) {
        $this->db = $db;
        $this->bookModel = new BookModel($db);
        $this->accountModel = new AccountModel($db);
        $this->subjectModel = new SubjectModel($db);
        $this->loanModel = new LoanModel($db); // Khởi tạo
        $this->reservationModel = new ReservationModel($db); // Khởi tạo

        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function dashboard() {
        // Lấy dữ liệu thực tế
        $allBooksFromModel = $this->bookModel->getBooks(); 
        $totalBooks = count($allBooksFromModel); 
        
        $availableBooksCount = 0;
        foreach ($allBooksFromModel as $book) {
            $availableBooksCount += $book->available_copies;
        }

        $activeMembers = $this->accountModel->getAllAccounts(null, null, 'active'); // Giả định có cột status
        $totalActiveMembers = count($activeMembers);

        $borrowedBooksCount = $this->loanModel->countBorrowedBooks(); // Tổng số sách đang lưu hành
        $overdueBooksCount = $this->loanModel->countOverdueBooks(); // Tổng số sách quá hạn
        
        // Cần thêm phương thức vào LoanModel để tính tổng phí phạt
        // Ví dụ: $totalFines = $this->loanModel->getTotalFines();
        $totalFines = "Đang phát triển"; // Placeholder

        // Tốc độ tăng trưởng - cần tính toán phức tạp hơn
        $growthRate = "Đang phát triển"; // Placeholder

        // Sách phổ biến - cần thêm logic để xác định sách phổ biến (ví dụ: dựa vào số lượt mượn)
        // Hiện tại chỉ lấy 3 sách đầu tiên như một placeholder
        $popularBooks = array_slice($allBooksFromModel, 0, 3); 

        ob_start();
        // Đổi tên biến để khớp với dashboard.php
        $totalBooks = $totalBooks;
        $activeMembers = $totalActiveMembers; // Cập nhật biến này
        $availableBooks = $availableBooksCount; // Cập nhật biến này
        $overdueBooks = $overdueBooksCount; // Cập nhật biến này
        
        include 'app/views/admin/dashboard.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}