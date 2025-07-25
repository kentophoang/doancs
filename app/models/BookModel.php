<?php

class BookModel
{
    private $conn;
    private $table_name = "books";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả sách với bộ lọc và sắp xếp
    public function getBooks($subjectId = null, $minYear = null, $maxYear = null, $searchTerm = null, $sortOrder = null)
    {
        $query = "SELECT b.id, b.name, b.description, b.author, b.publisher, b.publication_year, b.ISBN, b.image, b.number_of_copies, b.available_copies, s.name as subject_name
                  FROM " . $this->table_name . " b
                  LEFT JOIN subjects s ON b.subject_id = s.id
                  WHERE 1=1";

        $params = [];

        if ($subjectId !== null) {
            $query .= " AND b.subject_id = ?";
            $params[] = $subjectId;
        }
        if ($minYear !== null && is_numeric($minYear)) {
            $query .= " AND b.publication_year >= ?";
            $params[] = (int)$minYear;
        }
        if ($maxYear !== null && is_numeric($maxYear)) {
            $query .= " AND b.publication_year <= ?";
            $params[] = (int)$maxYear;
        }
        if (!empty($searchTerm)) {
            $query .= " AND (b.name LIKE ? OR b.author LIKE ? OR b.description LIKE ? OR b.ISBN LIKE ?)";
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
        }

        // Cập nhật logic sắp xếp
        if ($sortOrder === 'oldest') {
            $query .= " ORDER BY b.publication_year ASC";
        } elseif ($sortOrder === 'newest') {
            $query .= " ORDER BY b.publication_year DESC";
        } elseif ($sortOrder === 'name_asc') {
            $query .= " ORDER BY b.name ASC";
        } elseif ($sortOrder === 'name_desc') {
            $query .= " ORDER BY b.name DESC";
        } else {
            $query .= " ORDER BY b.id DESC"; // Mặc định sắp xếp theo sách mới nhất
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sách theo ID
    public function getBookById($id)
    {
        $query = "SELECT b.*, s.name as subject_name
                  FROM " . $this->table_name . " b
                  LEFT JOIN subjects s ON b.subject_id = s.id
                  WHERE b.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Thêm sách mới
    public function addBook($name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies)
    {
        // ... (Giữ nguyên logic thêm sách của bạn)
        $query = "INSERT INTO " . $this->table_name . " (name, description, author, publisher, publication_year, ISBN, subject_id, image, number_of_copies, available_copies)
                  VALUES (:name, :description, :author, :publisher, :publication_year, :ISBN, :subject_id, :image, :number_of_copies, :number_of_copies)";
        $stmt = $this->conn->prepare($query);
        // ... (Giữ nguyên các bindParam của bạn)
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindParam(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindParam(':author', htmlspecialchars(strip_tags($author)));
        $stmt->bindParam(':publisher', htmlspecialchars(strip_tags($publisher)));
        $stmt->bindParam(':publication_year', $publication_year, PDO::PARAM_INT);
        $stmt->bindParam(':ISBN', htmlspecialchars(strip_tags($ISBN)));
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':image', htmlspecialchars(strip_tags($image)));
        $stmt->bindParam(':number_of_copies', $number_of_copies, PDO::PARAM_INT);
        $stmt->bindParam(':available_copies', $number_of_copies, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cập nhật sách
    public function updateBook($id, $name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies)
    {
        // ... (Giữ nguyên logic cập nhật sách của bạn)
         $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description,
                  author=:author, publisher=:publisher, publication_year=:publication_year,
                  ISBN=:ISBN, subject_id=:subject_id, image=:image, number_of_copies=:number_of_copies
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        // ... (Giữ nguyên các bindParam của bạn)
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindParam(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindParam(':author', htmlspecialchars(strip_tags($author)));
        $stmt->bindParam(':publisher', htmlspecialchars(strip_tags($publisher)));
        $stmt->bindParam(':publication_year', $publication_year, PDO::PARAM_INT);
        $stmt->bindParam(':ISBN', htmlspecialchars(strip_tags($ISBN)));
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':image', htmlspecialchars(strip_tags($image)));
        $stmt->bindParam(':number_of_copies', $number_of_copies, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Xóa sách
    public function deleteBook($id)
    {
        // ... (Giữ nguyên logic xóa sách của bạn)
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // --- CÁC PHƯƠNG THỨC THỐNG KÊ CHO DASHBOARD (MỚI) ---

    /**
     * Đếm tổng số đầu sách trong thư viện.
     * @return int
     */
    public function countTotalBooks()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->total : 0;
    }

    /**
     * Lấy danh sách các sách được mượn nhiều nhất.
     * Yêu cầu phải có bảng 'loans' với cột 'book_id'.
     * @param int $limit Số lượng sách cần lấy.
     * @return array
     */
    public function getPopularBooks($limit = 3)
    {
        // Câu lệnh này giả định bạn có một bảng 'loans' để đếm số lượt mượn
        $query = "SELECT b.id, b.name, b.image, COUNT(l.book_id) as loan_count
                  FROM " . $this->table_name . " b
                  JOIN loans l ON b.id = l.book_id
                  GROUP BY b.id, b.name, b.image
                  ORDER BY loan_count DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // --- CÁC PHƯƠNG THỨC CẬP NHẬT SỐ LƯỢNG ---

    // Giảm số lượng bản sao có sẵn (khi mượn)
    public function decreaseAvailableCopies($bookId) {
        $query = "UPDATE " . $this->table_name . " SET available_copies = available_copies - 1 WHERE id = :id AND available_copies > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Tăng số lượng bản sao có sẵn (khi trả)
    public function increaseAvailableCopies($bookId) {
        $query = "UPDATE " . $this->table_name . " SET available_copies = available_copies + 1 WHERE id = :id AND available_copies < number_of_copies";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
