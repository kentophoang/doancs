<?php
class SubjectModel
{
    private $conn;
    private $table_name = "subjects";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả các chủ đề/môn học.
     */
    public function getSubjects()
    {
        $sql = "SELECT s.*, f.name as faculty_name 
                FROM " . $this->table_name . " s
                LEFT JOIN faculties f ON s.faculty_id = f.id
                ORDER BY s.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy một chủ đề cụ thể bằng ID.
     */
    public function getSubjectById($id)
    {
        $sql = "SELECT * FROM subjects WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Thêm một chủ đề mới.
     * Cần được cập nhật để lưu cả faculty_id.
     */
    public function addSubject($name, $description, $parent_id, $faculty_id)
    {
        $sql = "INSERT INTO subjects (name, description, parent_id, faculty_id) VALUES (:name, :description, :parent_id, :faculty_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':parent_id', $parent_id);
        $stmt->bindParam(':faculty_id', $faculty_id);
        return $stmt->execute();
    }

    /**
     * Cập nhật một chủ đề.
     * Cần được cập nhật để lưu cả faculty_id.
     */
    public function updateSubject($id, $name, $description, $parent_id, $faculty_id)
    {
        $sql = "UPDATE subjects SET name = :name, description = :description, parent_id = :parent_id, faculty_id = :faculty_id WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':parent_id', $parent_id);
        $stmt->bindParam(':faculty_id', $faculty_id);
        return $stmt->execute();
    }

    /**
     * Xóa một chủ đề.
     */
    public function deleteSubject($id)
    {
        $sql = "DELETE FROM subjects WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Lấy danh sách tất cả các Danh mục chính.
     */
    public function getMainCategories()
    {
        $query = "SELECT * FROM main_categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh sách tất cả các Khoa.
     * Hàm này chỉ được định nghĩa MỘT LẦN.
     */
    public function getFaculties()
    {
        $query = "SELECT * FROM faculties ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
