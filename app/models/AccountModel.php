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

    // New method to get all accounts
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
            $query .= " ORDER BY username ASC"; // Changed default sort to username
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user', $profession = null, $industry = null) {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " (username, fullname, password, role, profession, industry) VALUES (:username, :fullname, :password, :role, :profession, :industry)"; // Added column names explicitly
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