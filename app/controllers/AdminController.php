<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/BookModel.php');
require_once('app/models/AccountModel.php');
require_once('app/models/SubjectModel.php');

class AdminController {
    private $db;
    private $bookModel;
    private $accountModel;
    private $subjectModel;

    public function __construct($db) {
        $this->db = $db;
        $this->bookModel = new BookModel($db);
        $this->accountModel = new AccountModel($db);
        $this->subjectModel = new SubjectModel($db);

        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function dashboard() {
        $allBooksFromModel = $this->bookModel->getBooks(); // Get all books
        $totalBooks = count($allBooksFromModel); 
        
        $availableBooks = 0;
        foreach ($allBooksFromModel as $book) {
            $availableBooks += $book->available_copies;
        }

        // Placeholder data for other metrics - these would require specific queries
        $activeMembers = 3240; // You'd query active users from AccountModel
        $overdueBooks = 45; // You'd query overdue loans from a LoanModel
        $totalFines = "1.275.000 VND"; // You'd sum fines from a LoanModel
        $growthRate = "18.2%"; // You'd calculate growth rate over time

        // For "Sách phổ biến", assuming the most recent books from getBooks are "popular" for demo
        // In a real app, you'd have a column for borrow count or a separate "popular" query.
        $popularBooks = array_slice($allBooksFromModel, 0, 3); // Get top 3 books for display

        ob_start();
        include 'app/views/admin/dashboard.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}