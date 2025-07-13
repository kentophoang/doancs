<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

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

    // Sửa phương thức save để thêm profession và industry
    public function save($username, $fullName, $password, $role = 'user', $profession = null, $industry = null) {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role, profession=:profession, industry=:industry";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));
        $profession = htmlspecialchars(strip_tags($profession));
        $industry = htmlspecialchars(strip_tags($industry));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":profession", $profession);
        $stmt->bindParam(":industry", $industry);

        return $stmt->execute();
    }

    // Phương thức mới: Cập nhật thông tin tài khoản
    public function updateAccount($id, $fullName, $profession, $industry) {
        $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, profession=:profession, industry=:industry WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":fullname", htmlspecialchars(strip_tags($fullName)));
        $stmt->bindParam(":profession", htmlspecialchars(strip_tags($profession)));
        $stmt->bindParam(":industry", htmlspecialchars(strip_tags($industry)));

        return $stmt->execute();
    }
}
?>