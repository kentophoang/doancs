
```php
<?php

class SubjectModel
{
    private $conn;
    private $table_name = "subjects";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách tất cả chủ đề/ngành nghề, bao gồm parent_id
    public function getSubjects()
    {
        try {
            $query = "SELECT id, name, description, parent_id FROM " . $this->table_name . " ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Lấy một chủ đề/ngành nghề theo ID
    public function getSubjectById($id)
    {
        try {
            $query = "SELECT id, name, description, parent_id FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy thông tin chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Lấy danh sách chủ đề/ngành nghề cấp cao nhất (parent_id IS NULL)
    public function getParentSubjects() {
        try {
            $query = "SELECT id, name FROM " . $this->table_name . " WHERE parent_id IS NULL ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề cha: " . $e->getMessage());
        }
    }

    // Thêm chủ đề/ngành nghề mới
    public function addSubject($name, $description, $parentId = null)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (name, description, parent_id) VALUES (:name, :description, :parentId)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi thêm chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Cập nhật chủ đề/ngành nghề
    public function updateSubject($id, $name, $description, $parentId = null)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description, parent_id = :parentId WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi cập nhật chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    // Xóa chủ đề/ngành nghề
    public function deleteSubject($id)
    {
        try {
            // Khi xóa một chủ đề, các chủ đề con của nó sẽ có parent_id thành NULL (do ON DELETE SET NULL)
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