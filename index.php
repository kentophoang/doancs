<?php
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/database/Database.php';

// Tạo đối tượng kết nối cơ sở dữ liệu
$db = (new Database())->getConnection();

// Xử lý URL
$url = $_GET['url'] ?? ''; 
$url = rtrim($url, '/'); 
$url = filter_var($url, FILTER_SANITIZE_URL); 
$url = explode('/', $url); 

// Xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Đường dẫn đến file controller
$controllerFile = 'app/controllers/' . $controllerName . '.php';

// Kiểm tra controller có tồn tại không
if (!file_exists($controllerFile)) {
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

require_once $controllerFile;

// Khởi tạo controller
if (!class_exists($controllerName)) {
    die("Lỗi: Controller '$controllerName' không tồn tại!");
}

$controller = new $controllerName($db);

// Kiểm tra action có tồn tại trong controller không
if (!method_exists($controller, $action)) {
    header("HTTP/1.0 404 Not Found");
    include 'app/views/errors/404.php';
    exit;
}

// Gọi action với tham số (nếu có)
$params = array_slice($url, 2);
call_user_func_array([$controller, $action], $params);
