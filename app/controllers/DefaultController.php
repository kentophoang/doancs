<?php
class DefaultController {
    public function index() {
        ob_start();
        include 'app/views/home/index.php';
        $main_content = ob_get_clean();

        // Chỉ nhúng layout công cộng một lần duy nhất
        include 'app/views/shares/public_layout.php';
    }
}
?>