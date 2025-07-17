<?php
require_once('app/helpers/SessionHelper.php');

class OverdueController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function list() {
        // Here you would fetch overdue books/loans
        $overdueItems = []; // Placeholder

        ob_start();
        include 'app/views/overdue/list.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
?>