<?php

class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách danh mục
    public function getCategories()
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy danh mục: " . $e->getMessage());
        }
    }

    // Lấy một danh mục theo ID
    public function getCategoryById($id)
    {
        try {
            $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Lỗi khi lấy danh mục: " . $e->getMessage());
        }
    }

    // Thêm danh mục mới
    public function addCategory($name, $description)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi thêm danh mục: " . $e->getMessage());
        }
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name, $description)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi cập nhật danh mục: " . $e->getMessage());
        }
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Lỗi khi xóa danh mục: " . $e->getMessage());
        }
    }
}

?>
