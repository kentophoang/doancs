<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/ReservationModel.php');
require_once('app/models/BookModel.php');
require_once('app/models/AccountModel.php');

class ReservationController {
    private $db;
    private $reservationModel;
    private $bookModel;
    private $accountModel;

    public function __construct($db) {
        $this->db = $db;
        $this->reservationModel = new ReservationModel($db);
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

        $reservations = $this->reservationModel->getAllReservations($searchTerm, $status);

        ob_start();
        include 'app/views/reservation/manage.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }

    public function approve($id) {
        $reservation = $this->reservationModel->getReservationById($id);
        if (!$reservation || $reservation->status !== 'pending') {
            $_SESSION['error_message'] = "Yêu cầu đặt trước không hợp lệ hoặc đã được xử lý.";
            header('Location: /Reservation/manage');
            exit();
        }

        // Kiểm tra xem sách có sẵn để duyệt không
        $book = $this->bookModel->getBookById($reservation->book_id);
        if (!$book || $book->available_copies <= 0) {
            $_SESSION['error_message'] = "Sách không có sẵn để duyệt đặt trước.";
            header('Location: /Reservation/manage');
            exit();
        }

        // Cập nhật trạng thái đặt trước và giảm số lượng sách
        if ($this->reservationModel->updateReservationStatus($id, 'fulfilled') && $this->bookModel->decreaseAvailableCopies($book->id)) {
            // Tùy chọn: Tạo bản ghi mượn sách ngay lập tức hoặc để người dùng đến mượn
            // Ví dụ: auto-create loan record
            // $this->loanModel->createLoan($reservation->book_id, $reservation->user_id, date('Y-m-d'), date('Y-m-d', strtotime('+14 days')));

            $_SESSION['success_message'] = "Duyệt yêu cầu đặt trước thành công! Sách đã được ghi nhận.";
        } else {
            $_SESSION['error_message'] = "Không thể duyệt yêu cầu đặt trước. Vui lòng thử lại.";
        }
        header('Location: /Reservation/manage');
        exit();
    }

    public function cancel($id) {
        $reservation = $this->reservationModel->getReservationById($id);
        if (!$reservation || ($reservation->status !== 'pending' && $reservation->status !== 'ready')) {
            $_SESSION['error_message'] = "Yêu cầu đặt trước không hợp lệ hoặc không thể hủy.";
            header('Location: /Reservation/manage');
            exit();
        }

        if ($this->reservationModel->updateReservationStatus($id, 'cancelled')) {
            $_SESSION['success_message'] = "Hủy yêu cầu đặt trước thành công.";
        } else {
            $_SESSION['error_message'] = "Hủy yêu cầu đặt trước thất bại. Vui lòng thử lại.";
        }
        header('Location: /Reservation/manage');
        exit();
    }

    public function delete($id) {
        if ($this->reservationModel->deleteReservation($id)) {
            $_SESSION['success_message'] = "Xóa yêu cầu đặt trước thành công.";
        } else {
            $_SESSION['error_message'] = "Xóa yêu cầu đặt trước thất bại.";
        }
        header('Location: /Reservation/manage');
        exit();
    }
}
?>