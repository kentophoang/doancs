<?php
class ChatbotModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * NÂNG CẤP: Tìm kiếm sách trong kho tri thức cục bộ.
     */
    public function findBooksByConcept($concept_name) {
        $query = "SELECT 
                    b.name as book_name, b.author, b.location, b.available_copies,
                    r.relevant_chapters, r.relevant_pages, r.notes
                  FROM concept_book_references r
                  JOIN books b ON r.book_id = b.id
                  JOIN concepts c ON r.concept_id = c.id
                  WHERE c.name LIKE :search_term 
                     OR b.name LIKE :search_term 
                     OR b.description LIKE :search_term";
        
        $stmt = $this->conn->prepare($query);
        $search_term = '%' . $concept_name . '%';
        $stmt->bindParam(':search_term', $search_term);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * MỚI: Tìm sách trong thư viện dựa trên mã ISBN.
     */
    public function findBookByIsbn($isbn) {
        $query = "SELECT name, available_copies, location FROM books WHERE ISBN = :isbn LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
