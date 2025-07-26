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
        $query = "SELECT b.id, b.name, b.description, b.author, b.publisher, b.publication_year, b.ISBN, b.image, b.number_of_copies, b.available_copies, b.location, s.name as subject_name
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

        if ($sortOrder === 'oldest') {
            $query .= " ORDER BY b.publication_year ASC";
        } elseif ($sortOrder === 'newest') {
            $query .= " ORDER BY b.publication_year DESC";
        } elseif ($sortOrder === 'name_asc') {
            $query .= " ORDER BY b.name ASC";
        } elseif ($sortOrder === 'name_desc') {
            $query .= " ORDER BY b.name DESC";
        } else {
            $query .= " ORDER BY b.id DESC";
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

    /**
     * SỬA LỖI: Thêm phương thức bị thiếu.
     * Lấy thông tin của nhiều sách dựa trên một mảng các ID.
     * @param array $ids Mảng chứa các ID của sách.
     * @return array Mảng các đối tượng sách.
     */
    public function getBooksByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }
        // Tạo chuỗi placeholder (?, ?, ?) cho câu lệnh IN
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id IN (" . $placeholders . ")";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Thêm sách mới
    public function addBook($name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies, $location)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description, author, publisher, publication_year, ISBN, subject_id, image, number_of_copies, available_copies, location)
                  VALUES (:name, :description, :author, :publisher, :publication_year, :ISBN, :subject_id, :image, :number_of_copies, :number_of_copies, :location)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':publisher', $publisher);
        $stmt->bindParam(':publication_year', $publication_year);
        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':number_of_copies', $number_of_copies);
        $stmt->bindParam(':location', $location);

        return $stmt->execute();
    }

    // Cập nhật sách
    public function updateBook($id, $name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies, $location)
    {
         $query = "UPDATE " . $this->table_name . " SET 
                    name=:name, description=:description, author=:author, publisher=:publisher, 
                    publication_year=:publication_year, ISBN=:ISBN, subject_id=:subject_id, 
                    image=:image, number_of_copies=:number_of_copies, location=:location
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':publisher', $publisher);
        $stmt->bindParam(':publication_year', $publication_year);
        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':number_of_copies', $number_of_copies);
        $stmt->bindParam(':location', $location);

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

    // --- CÁC PHƯƠNG THỨC THỐNG KÊ CHO DASHBOARD ---

    public function countTotalBooks()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->total : 0;
    }

    public function getPopularBooks($limit = 3)
    {
        $query = "SELECT b.id, b.name, b.image, COUNT(l.book_id) as loan_count 
                  FROM books b 
                  JOIN loans l ON b.id = l.book_id 
                  GROUP BY b.id 
                  ORDER BY loan_count DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getBookCountBySubject()
    {
        $query = "SELECT s.name as subject_name, COUNT(b.id) as book_count
                  FROM books b
                  JOIN subjects s ON b.subject_id = s.id
                  GROUP BY s.name
                  ORDER BY book_count DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        $labels = [];
        $data = [];
        foreach($results as $row) {
            $labels[] = $row->subject_name;
            $data[] = (int)$row->book_count;
        }

        return ['labels' => $labels, 'data' => $data];
    }
    
    // --- CÁC PHƯƠNG THỨC CẬP NHẬT SỐ LƯỢNG ---

    public function decreaseAvailableCopies($bookId) {
        $query = "UPDATE " . $this->table_name . " SET available_copies = available_copies - 1 WHERE id = :id AND available_copies > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function increaseAvailableCopies($bookId) {
        $query = "UPDATE " . $this->table_name . " SET available_copies = available_copies + 1 WHERE id = :id AND available_copies < number_of_copies";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
