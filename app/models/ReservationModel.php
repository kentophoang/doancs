<?php
class ReservationModel {
    private $conn;
    private $table_name = "reservations";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createReservation($book_id, $user_id, $reservation_date) {
        $query = "INSERT INTO " . $this->table_name . " (book_id, user_id, reservation_date, status) VALUES (:book_id, :user_id, :reservation_date, 'pending')";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':reservation_date', $reservation_date);

        return $stmt->execute();
    }

    public function getReservationById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllReservations($searchTerm = null, $status = null) {
        $query = "SELECT r.*, b.name as book_name, b.ISBN, a.username as user_username, a.fullname as user_fullname
                  FROM " . $this->table_name . " r
                  JOIN books b ON r.book_id = b.id
                  JOIN accounts a ON r.user_id = a.id
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
            $query .= " AND r.status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY r.reservation_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateReservationStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function deleteReservation($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>