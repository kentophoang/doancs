<?php
require_once('app/helpers/SessionHelper.php');

class AnalyticsController {
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
        // Here you would fetch data for analytics (e.g., popular books, user activity)
        $analyticsData = []; // Placeholder

        ob_start();
        include 'app/views/analytics/view.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
?>