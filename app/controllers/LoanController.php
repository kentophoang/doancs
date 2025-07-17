<?php
require_once('app/helpers/SessionHelper.php');

class LoanController {
    private $db; // Assuming you'll need DB connection for actual loan data

    public function __construct($db) {
        $this->db = $db;
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function manage() {
        // Here you would fetch loan data
        $loans = []; // Placeholder for actual loan data

        ob_start();
        include 'app/views/loan/manage.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
?>