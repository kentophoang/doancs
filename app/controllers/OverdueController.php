<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/LoanModel.php'); // Cần LoanModel để lấy sách quá hạn

class OverdueController {
    private $db;
    private $loanModel;

    public function __construct($db) {
        $this->db = $db;
        $this->loanModel = new LoanModel($db);
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function list() {
        // Cập nhật trạng thái 'overdue' cho các khoản mượn đã quá hạn nhưng chưa được đánh dấu
        // (Có thể chạy định kỳ hoặc khi load trang này)
        $this->loanModel->updateOverdueStatusAll(); // Cần thêm phương thức này vào LoanModel

        $overdueItems = $this->loanModel->getOverdueLoans();

        ob_start();
        include 'app/views/overdue/list.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }

    // Các chức năng khác như gửi nhắc nhở có thể được thêm tại đây
}
?>