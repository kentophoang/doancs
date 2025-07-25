<?php
class LoanModel {
    private $conn;
    private $table_name = "loans";

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CÁC PHƯƠNG THỨC CRUD CƠ BẢN ---

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
        $query = "SELECT l.*, b.name as book_name, a.fullname as borrower_fullname 
                  FROM " . $this->table_name . " l
                  JOIN books b ON l.book_id = b.id
                  JOIN accounts a ON l.user_id = a.id
                  WHERE l.id = :loan_id";
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
        if ($userId) {
            $query .= " AND l.user_id = ?";
            $params[] = $userId;
        }

        $query .= " ORDER BY l.borrow_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // --- CÁC PHƯƠNG THỨC THỐNG KÊ CHO DASHBOARD ---

    public function countCurrentLoans() {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM " . $this->table_name . " WHERE return_date IS NULL");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->total : 0;
    }

    public function countOverdueBooks() {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM " . $this->table_name . " WHERE return_date IS NULL AND due_date < CURDATE()");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->total : 0;
    }

    public function getLoanStatsForChart($days = 7) {
        $labels = [];
        $loan_data = array_fill(0, $days, 0);
        $return_data = array_fill(0, $days, 0);

        for ($i = $days - 1; $i >= 0; $i--) {
            $labels[] = date('d/m', strtotime("-$i days"));
        }

        $startDate = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));

        $loan_query = "SELECT DATE(borrow_date) as day, COUNT(id) as total 
                       FROM " . $this->table_name . " 
                       WHERE borrow_date >= :startDate 
                       GROUP BY day";
        $loan_stmt = $this->conn->prepare($loan_query);
        $loan_stmt->bindParam(':startDate', $startDate);
        $loan_stmt->execute();
        $loan_results = $loan_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $return_query = "SELECT DATE(return_date) as day, COUNT(id) as total 
                         FROM " . $this->table_name . " 
                         WHERE return_date >= :startDate 
                         GROUP BY day";
        $return_stmt = $this->conn->prepare($return_query);
        $return_stmt->bindParam(':startDate', $startDate);
        $return_stmt->execute();
        $return_results = $return_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        foreach ($labels as $index => $label) {
            $date_key = date('Y-m-d', strtotime(str_replace('/', '-', $label) . '-' . date('Y')));
            if (isset($loan_results[$date_key])) {
                $loan_data[$index] = (int)$loan_results[$date_key];
            }
            if (isset($return_results[$date_key])) {
                $return_data[$index] = (int)$return_results[$date_key];
            }
        }

        return [
            'labels' => $labels,
            'loans' => $loan_data,
            'returns' => $return_data
        ];
    }

    public function getRecentActivities($limit = 5) {
        $query = "(SELECT a.fullname as member_name, b.name as book_title, 'loan' as action, l.borrow_date as timestamp
                  FROM " . $this->table_name . " l
                  JOIN accounts a ON l.user_id = a.id
                  JOIN books b ON l.book_id = b.id)
                  UNION ALL
                  (SELECT a.fullname as member_name, b.name as book_title, 'return' as action, l.return_date as timestamp
                  FROM " . $this->table_name . " l
                  JOIN accounts a ON l.user_id = a.id
                  JOIN books b ON l.book_id = b.id
                  WHERE l.return_date IS NOT NULL)
                  ORDER BY timestamp DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // --- CÁC PHƯƠNG THỨC XỬ LÝ SÁCH QUÁ HẠN ---

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

    /**
     * SỬA LỖI: Thêm lại phương thức bị thiếu.
     * Cập nhật trạng thái của tất cả sách đang mượn đã quá hạn thành 'overdue'.
     */
    public function updateOverdueStatusAll()
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'overdue' WHERE due_date < CURDATE() AND status = 'borrowed'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
