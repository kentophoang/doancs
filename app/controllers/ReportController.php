<?php
require_once('app/helpers/SessionHelper.php');

class ReportController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function view() {
        // Here you would fetch data for various reports
        $reports = []; // Placeholder for report list

        ob_start();
        include 'app/views/report/view.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
?>