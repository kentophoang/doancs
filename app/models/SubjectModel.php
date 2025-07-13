<?php

class SubjectModel // Đổi tên lớp
{
    private $conn;
    private $table_name = "subjects"; // Đổi tên bảng từ 'category' thành 'subjects'

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách chủ đề/ngành nghề
    public function getSubjects() // Đổi tên phương thức
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Lấy một chủ đề/ngành nghề theo ID
    public function getSubjectById($id) // Đổi tên phương thức
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Thêm chủ đề/ngành nghề mới
    public function addSubject($name, $description) // Đổi tên phương thức
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi thêm chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Cập nhật chủ đề/ngành nghề
    public function updateSubject($id, $name, $description) // Đổi tên phương thức
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi cập nhật chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Xóa chủ đề/ngành nghề
    public function deleteSubject($id) // Đổi tên phương thức
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi xóa chủ đề/ngành nghề: " . $e->getMessage());
        }
    }
}
?>