<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/LoanModel.php');
require_once('app/models/BookModel.php'); // Cần BookModel để lấy tên sách
require_once('app/models/AccountModel.php'); // Cần AccountModel để lấy tên người dùng

class LoanController {
    private $db;
    private $loanModel;
    private $bookModel;
    private $accountModel;

    public function __construct($db) {
        $this->db = $db;
        $this->loanModel = new LoanModel($db);
        $this->bookModel = new BookModel($db);
        $this->accountModel = new AccountModel($db);
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function manage() {
        $searchTerm = $_GET['search'] ?? null;
        $status = $_GET['status'] ?? null;

        $loans = $this->loanModel->getAllLoans($searchTerm, $status);

        ob_start();
        include 'app/views/loan/manage.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }

    // Phương thức để xem chi tiết một giao dịch mượn
    public function view($id) {
        $loan = $this->loanModel->getLoanById($id);
        if (!$loan) {
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php';
            exit();
        }
        $book = $this->bookModel->getBookById($loan->book_id);
        $user = $this->accountModel->getAccountById($loan->user_id);

        ob_start();
        include 'app/views/loan/view.php'; // Cần tạo view này
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    // Phương thức để admin xử lý trả sách (trong trường hợp người dùng không tự trả qua nút)
    public function adminReturn($loan_id) {
        $loan = $this->loanModel->getLoanById($loan_id);
        if (!$loan || $loan->status !== 'borrowed') {
            $_SESSION['error_message'] = "Giao dịch không hợp lệ hoặc đã được trả.";
            header('Location: /Loan/manage');
            exit();
        }

        $return_date = date('Y-m-d');
        $fine_amount = 0;
        if (strtotime($return_date) > strtotime($loan->due_date)) {
            $days_overdue = (new DateTime($return_date))->diff(new DateTime($loan->due_date))->days;
            $fine_amount = $days_overdue * 5000; // Ví dụ: 5000 VND/ngày
        }

        // Tăng số lượng bản sao có sẵn trong bảng books
        if ($this->bookModel->increaseAvailableCopies($loan->book_id)) {
            if ($this->loanModel->returnLoan($loan_id, $return_date, $fine_amount)) {
                $_SESSION['success_message'] = "Đã ghi nhận trả sách thành công!" . ($fine_amount > 0 ? " Phí trễ hạn: " . number_format($fine_amount, 0, ',', '.') . " đ." : "");
            } else {
                $this->bookModel->decreaseAvailableCopies($loan->book_id); // Rollback
                $_SESSION['error_message'] = "Không thể ghi nhận trả sách. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['error_message'] = "Không thể cập nhật số lượng sách có sẵn.";
        }
        header('Location: /Loan/manage');
        exit();
    }

    // Phương thức để xóa một giao dịch mượn (chỉ admin và cẩn thận khi sử dụng)
    public function delete($id) {
        $loan = $this->loanModel->getLoanById($id);
        if (!$loan) {
            $_SESSION['error_message'] = "Không tìm thấy giao dịch để xóa.";
            header('Location: /Loan/manage');
            exit();
        }

        if ($this->loanModel->deleteLoan($id)) { // Cần thêm phương thức deleteLoan vào LoanModel
            // Nếu giao dịch đang ở trạng thái 'borrowed', cần tăng lại số lượng sách có sẵn
            if ($loan->status === 'borrowed') {
                $this->bookModel->increaseAvailableCopies($loan->book_id);
            }
            $_SESSION['success_message'] = "Xóa giao dịch mượn thành công!";
            header('Location: /Loan/manage');
            exit();
        } else {
            $_SESSION['error_message'] = "Xóa giao dịch mượn thất bại.";
            header('Location: /Loan/manage');
            exit();
        }
    }
}
?>