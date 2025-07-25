<?php
// Bắt đầu bộ đệm đầu ra (Output Buffering) ở ĐẦU TIÊN của tệp.
// Điều này sẽ giữ lại tất cả đầu ra HTML cho đến khi tất cả các tiêu đề HTTP đã được xử lý xong.
ob_start();

session_start();
require_once 'app/models/BookModel.php';
require_once 'app/models/SubjectModel.php';
require_once 'app/models/AccountModel.php';
require_once 'app/models/LoanModel.php'; // Thêm LoanModel
require_once 'app/models/ReservationModel.php'; // Thêm ReservationModel
require_once 'app/helpers/SessionHelper.php';
require_once 'app/database/Database.php';
require_once 'app/controllers/DefaultController.php';

$db = (new Database())->getConnection();

// GLOBAL HEADER INCLUDED ONCE FOR THE ENTIRE APPLICATION
// Nội dung của header.php sẽ được đệm chứ không gửi ngay lập tức.
include 'app/views/shares/header.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định controller
// Xác định controller, nếu URL rỗng thì mặc định là DefaultController
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

// Xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Đường dẫn đến file controller
$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    // Các hàm header() ở đây sẽ hoạt động vì đầu ra đang được đệm
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("Lỗi: Controller '$controllerName' không tồn tại!");
}

// Khởi tạo controller
// AccountController và DefaultController không nhận $db trong constructor ban đầu
// Các Controller khác nhận $db
// Instantiate controller
$controller = new $controllerName($db);
if (!method_exists($controller, $action)) {
    // Các hàm header() ở đây cũng sẽ hoạt động
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

$params = array_slice($url, 2);
call_user_func_array([$controller, $action], $params);

// GLOBAL FOOTER INCLUDED ONCE FOR THE ENTIRE APPLICATION
// Nội dung của footer.php cũng sẽ được đệm.
include 'app/views/shares/footer.php';

// Kết thúc bộ đệm đầu ra và gửi tất cả nội dung đã đệm đến trình duyệt.
ob_end_flush();
?>
