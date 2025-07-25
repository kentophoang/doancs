<?php
class LoanModel {
    private $conn;
    private $table_name = "loans"; // Tên bảng là 'loans' hoặc 'borrow_records'

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createLoan($book_id, $user_id, $borrow_date, $due_date) {
        $query = "INSERT INTO " . $this->table_name . " (book_id, user_id, borrow_date, due_date, status) VALUES (:book_id, :user_id, :borrow_date, :due_date, 'borrowed')";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':borrow_date', $borrow_date);
        $stmt->bindParam(':due_date', $due_date);

        return $stmt->execute();
    }

    public function returnLoan($loan_id, $return_date, $fine_amount = 0) {
        $query = "UPDATE " . $this->table_name . " SET return_date = :return_date, status = 'returned', fine_amount = :fine_amount WHERE id = :loan_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':fine_amount', $fine_amount, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getLoanById($loan_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :loan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllLoans($searchTerm = null, $status = null, $userId = null) {
        $query = "SELECT l.*, b.name as book_name, b.ISBN, a.username as borrower_username, a.fullname as borrower_fullname
                  FROM " . $this->table_name . " l
                  JOIN books b ON l.book_id = b.id
                  JOIN accounts a ON l.user_id = a.id
                  WHERE 1=1";
        $params = [];

        if ($searchTerm) {
            $query .= " AND (b.name LIKE ? OR a.username LIKE ? OR a.fullname LIKE ? OR b.ISBN LIKE ?)";
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
        }
        if ($status) {
            $query .= " AND l.status = ?";
            $params[] = $status;
        }
        if ($userId) { // Dùng cho MyBorrowedBooks của người dùng
            $query .= " AND l.user_id = ?";
            $params[] = $userId;
        }

        $query .= " ORDER BY l.borrow_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOverdueLoans() {
        $query = "SELECT l.*, b.name as book_name, b.ISBN, a.username as borrower_username, a.fullname as borrower_fullname
                  FROM " . $this->table_name . " l
                  JOIN books b ON l.book_id = b.id
                  JOIN accounts a ON l.user_id = a.id
                  WHERE l.due_date < CURDATE() AND l.status = 'borrowed'
                  ORDER BY l.due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Phương thức để cập nhật trạng thái quá hạn
    public function updateOverdueStatus($loan_id) {
        $query = "UPDATE " . $this->table_name . " SET status = 'overdue' WHERE id = :loan_id AND due_date < CURDATE() AND status = 'borrowed'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Phương thức MỚI để cập nhật trạng thái quá hạn cho TẤT CẢ các khoản vay
    public function updateOverdueStatusAll()
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'overdue' WHERE due_date < CURDATE() AND status = 'borrowed'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }


    // Phương thức để lấy số lượng sách đang lưu hành
    public function countBorrowedBooks() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE status = 'borrowed'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Phương thức để lấy số lượng sách quá hạn
    public function countOverdueBooks() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE due_date < CURDATE() AND status = 'borrowed'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>