<?php
class AccountModel {
    private $conn;
    private $table_name = "accounts";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllAccounts($searchTerm = null, $sortBy = null, $status = null) {
        // Đã loại bỏ cột 'created_at' để tránh lỗi nếu cột không tồn tại
        $query = "SELECT id, username, email, fullname, role, is_verified FROM " . $this->table_name . " WHERE 1=1";
        $params = [];

        if ($searchTerm) {
            $query .= " AND (username LIKE ? OR fullname LIKE ? OR email LIKE ?)";
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
            $params[] = '%' . $searchTerm . '%';
        }
        
        if ($status === 'active' || $status === 'verified') {
            $query .= " AND is_verified = 1";
        } elseif ($status === 'unverified') {
            $query .= " AND is_verified = 0";
        }

        if ($sortBy == 'name_asc') {
            $query .= " ORDER BY fullname ASC";
        } elseif ($sortBy == 'name_desc') {
            $query .= " ORDER BY fullname DESC";
        } else {
            $query .= " ORDER BY id DESC"; // Sắp xếp theo ID mới nhất
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // --- CÁC PHƯƠNG THỨC THỐNG KÊ CHO DASHBOARD (MỚI) ---

    /**
     * Đếm tổng số thành viên đang hoạt động (đã xác thực và không phải admin).
     * @return int
     */
    public function countActiveMembers()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM accounts WHERE is_verified = 1 AND role = 'member'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->total : 0;
    }

    // --- CÁC PHƯƠNG THỨC KHÁC ---

    public function createAccount($username, $password, $email, $fullname, $token, $expiry, $is_verified = 0, $role = 'member') {
        $query = "INSERT INTO " . $this->table_name . "
                    (username, password, email, fullname, role, verification_token, token_expiry, is_verified)
                  VALUES
                    (:username, :password, :email, :fullname, :role, :token, :expiry, :is_verified)";
        
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $email = htmlspecialchars(strip_tags($email));
        $fullname_clean = htmlspecialchars(strip_tags($fullname));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":fullname", $fullname_clean);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":expiry", $expiry);
        $stmt->bindParam(":is_verified", $is_verified, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findAccountByToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE verification_token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function verifyAccount($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_verified = 1, verification_token = NULL, token_expiry = NULL 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateAccount($id, $fullName, $profession, $industry) {
        $query = "UPDATE " . $this->table_name . " SET fullname = :fullname, profession = :profession, industry = :industry WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":fullname", htmlspecialchars(strip_tags($fullName)));
        $stmt->bindParam(":profession", htmlspecialchars(strip_tags($profession)));
        $stmt->bindParam(":industry", htmlspecialchars(strip_tags($industry)));

        return $stmt->execute();
    }

    public function deleteAccount($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateAccountRole($id, $role) {
        $query = "UPDATE " . $this->table_name . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':role', htmlspecialchars(strip_tags($role)));
        return $stmt->execute();
    }
}
