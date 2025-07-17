<?php
require_once('app/helpers/SessionHelper.php');

class ReservationController {
    private $db; // Assuming you'll need DB connection for actual reservation data

    public function __construct($db) {
        $this->db = $db;
        SessionHelper::start();
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
    }

    public function manage() {
        // Here you would fetch reservation data
        $reservations = []; // Placeholder for actual reservation data

        ob_start();
        include 'app/views/reservation/manage.php';
        $main_content = ob_get_clean();
        
        include 'app/views/shares/admin_layout.php';
    }
}
?>