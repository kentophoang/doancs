<?php
/**
 * File điều phối chính (Router) của ứng dụng.
 * Tất cả các yêu cầu đều đi qua file này.
 */

// --- SỬA LỖI: Định nghĩa hằng số ROOT_PATH để có đường dẫn tuyệt đối ---
// __DIR__ trả về thư mục hiện tại (C:\laragon\www\LibSmart), là thư mục gốc.
define('ROOT_PATH', __DIR__);

// Bắt đầu session ở một nơi duy nhất
session_start();

// Tải các file lõi bằng đường dẫn tuyệt đối
require_once ROOT_PATH . '/app/database/Database.php';
require_once ROOT_PATH . '/app/helpers/SessionHelper.php';

// Khởi tạo kết nối CSDL
try {
    $db = (new Database())->getConnection();
} catch (PDOException $e) {
    // Hiển thị lỗi thân thiện nếu không kết nối được CSDL
    die("Lỗi kết nối cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình.");
}

/**
 * Hàm hiển thị trang 404 một cách nhất quán.
 */
function show_404() {
    http_response_code(404);
    // SỬA LỖI: Sử dụng đường dẫn tuyệt đối để gọi file 404
    include ROOT_PATH . '/app/views/errors/404.php';
    exit();
}

// Phân tích URL để xác định controller và action
$url = $_SERVER['REQUEST_URI'];
$url_parts = explode('/', trim(parse_url($url, PHP_URL_PATH), '/'));

// Xác định controller, action, và tham số
// Ví dụ: /book/show/1 => Controller: BookController, Action: show, Params: [1]
$controllerName = !empty($url_parts[0]) ? ucfirst(strtolower($url_parts[0])) . 'Controller' : 'DefaultController';
$actionName = $url_parts[1] ?? 'index';
$params = array_slice($url_parts, 2);

// Tải tệp controller tương ứng
$controllerFile = ROOT_PATH . '/app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Kiểm tra xem lớp controller và phương thức action có tồn tại không
    if (class_exists($controllerName) && method_exists($controllerName, $actionName)) {
        // Tạo đối tượng controller và gọi phương thức, truyền CSDL và tham số vào
        $controller = new $controllerName($db);
        call_user_func_array([$controller, $actionName], $params);
    } else {
        // Lớp hoặc phương thức không tồn tại -> Lỗi 404
        show_404();
    }
} else {
    // Tệp controller không tồn tại -> Lỗi 404
    show_404();
}
