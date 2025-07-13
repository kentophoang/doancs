<?php

class BookModel
{
    private $conn;
    private $table_name = "books"; // Đổi tên bảng từ 'product' thành 'books'

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả sách
    public function getBooks($subjectId = null, $minYear = null, $maxYear = null, $searchTerm = null, $sortOrder = null)
    {
        $query = "SELECT b.id, b.name, b.description, b.author, b.publisher, b.publication_year, b.image, b.number_of_copies, b.available_copies, s.name as subject_name
                  FROM " . $this->table_name . " b
                  LEFT JOIN subjects s ON b.subject_id = s.id
                  WHERE 1=1"; // Bắt đầu với điều kiện luôn đúng để dễ dàng thêm AND

        $params = [];

        // Lọc theo chủ đề/ngành nghề
        if ($subjectId !== null) {
            $query .= " AND b.subject_id = ?";
            $params[] = $subjectId;
        }

        // Lọc theo năm xuất bản
        if ($minYear !== null && is_numeric($minYear)) {
            $query .= " AND b.publication_year >= ?";
            $params[] = (int)$minYear;
        }
        if ($maxYear !== null && is_numeric($maxYear)) {
            $query .= " AND b.publication_year <= ?";
            $params[] = (int)$maxYear;
        }

        // Tìm kiếm theo từ khóa (tên sách, tác giả, mô tả, ISBN)
        if (!empty($searchTerm)) {
            $query .= " AND (b.name LIKE ? OR b.author LIKE ? OR b.description LIKE ? OR b.ISBN LIKE ?)";
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
        }

        // Sắp xếp
        if ($sortOrder === 'oldest') {
            $query .= " ORDER BY b.publication_year ASC";
        } elseif ($sortOrder === 'newest') {
            $query .= " ORDER BY b.publication_year DESC";
        } else {
            $query .= " ORDER BY b.name ASC"; // Mặc định sắp xếp theo tên sách
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
        $errors = [];
        if (empty($name)) $errors['name'] = 'Tên sách không được để trống';
        if (empty($author)) $errors['author'] = 'Tên tác giả không được để trống';
        if (!is_numeric($publication_year) || $publication_year <= 0 || $publication_year > date('Y')) $errors['publication_year'] = 'Năm xuất bản không hợp lệ';
        if (!is_numeric($number_of_copies) || $number_of_copies <= 0) $errors['number_of_copies'] = 'Số lượng bản sao không hợp lệ';
        if (empty($subject_id)) $errors['subject_id'] = 'Chủ đề/Ngành nghề không được để trống';

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, author, publisher, publication_year, ISBN, subject_id, image, number_of_copies, available_copies)
                  VALUES (:name, :description, :author, :publisher, :publication_year, :ISBN, :subject_id, :image, :number_of_copies, :number_of_copies)"; // available_copies ban đầu bằng number_of_copies
        $stmt = $this->conn->prepare($query);

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
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description,
                  author=:author, publisher=:publisher, publication_year=:publication_year,
                  ISBN=:ISBN, subject_id=:subject_id, image=:image, number_of_copies=:number_of_copies
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

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
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

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
?>