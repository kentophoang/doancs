<?php
session_start();
require_once 'app/models/BookModel.php';    // Đổi từ ProductModel
require_once 'app/models/SubjectModel.php'; // Đổi từ CategoryModel
require_once 'app/models/AccountModel.php'; // Đảm bảo AccountModel được load
require_once 'app/helpers/SessionHelper.php';
require_once 'app/database/Database.php';

$db = (new Database())->getConnection();

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'BookController'; // Mặc định là BookController

// Xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Đường dẫn đến file controller
$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("Lỗi: Controller '$controllerName' không tồn tại!");
}

// Kiểm tra xem controller có cần kết nối DB không (hầu hết đều cần)
// DefaultController không cần DB, các controller khác cần
if ($controllerName === 'DefaultController') {
    $controller = new $controllerName();
} else {
    $controller = new $controllerName($db);
}

if (!method_exists($controller, $action)) {
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

$params = array_slice($url, 2);
call_user_func_array([$controller, $action], $params);
?>