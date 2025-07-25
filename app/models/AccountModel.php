<?php
class AccountModel {
    private $conn;
    private $table_name = "accounts"; // Đã đổi tên bảng để khớp với SQL schema

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountById($id) { // Thêm phương thức này để lấy thông tin tài khoản bằng ID
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllAccounts($searchTerm = null, $sortBy = null, $status = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];

        if ($searchTerm) {
            $query .= " AND (username LIKE ? OR fullname LIKE ?)";
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
        }

        // Add more filters (e.g., status) if your database supports it
        // if ($status) {
        //     $query .= " AND status = ?";
        //     $params[] = $status;
        // }

        if ($sortBy == 'name_asc') {
            $query .= " ORDER BY fullname ASC";
        } elseif ($sortBy == 'name_desc') {
            $query .= " ORDER BY fullname DESC";
        } else {
            $query .= " ORDER BY username ASC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user', $profession = null, $industry = null) {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " (username, fullname, password, role, profession, industry) VALUES (:username, :fullname, :password, :role, :profession, :industry)";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Sử dụng biến đã băm
        $role = htmlspecialchars(strip_tags($role));
        $profession = htmlspecialchars(strip_tags($profession));
        $industry = htmlspecialchars(strip_tags($industry));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $hashedPassword); // Bind mật khẩu đã băm
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":profession", $profession);
        $stmt->bindParam(":industry", $industry);

        return $stmt->execute();
    }

    public function updateAccount($id, $fullName, $profession, $industry) {
        $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, profession=:profession, industry=:industry WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":fullname", htmlspecialchars(strip_tags($fullName)));
        $stmt->bindParam(":profession", htmlspecialchars(strip_tags($profession)));
        $stmt->bindParam(":industry", htmlspecialchars(strip_tags($industry)));

        return $stmt->execute();
    }

    public function deleteAccount($id) { // Thêm phương thức xóa tài khoản
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateAccountRole($id, $role) { // Thêm phương thức cập nhật vai trò
        $query = "UPDATE " . $this->table_name . " SET role=:role WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':role', htmlspecialchars(strip_tags($role)));
        return $stmt->execute();
    }
}
?>