<?php
class DefaultController {
    public function index() {
        // Ob_start để chứa nội dung của view vào một biến
        ob_start();
        include 'app/views/home/index.php';
        $main_content = ob_get_clean();

        // Chuyển nội dung vào layout chính
        include 'app/views/shares/public_layout.php';
    }
}
?>