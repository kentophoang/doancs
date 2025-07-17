<?php
session_start();
require_once 'app/models/BookModel.php';
require_once 'app/models/SubjectModel.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/database/Database.php';

$db = (new Database())->getConnection();

// GLOBAL HEADER INCLUDED ONCE FOR THE ENTIRE APPLICATION
include 'app/views/shares/header.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'BookController';

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

// Removed the problematic global admin redirection.
// Each admin controller/action will now manage its own access and layout inclusion.

// Instantiate controller
if ($controllerName === 'DefaultController' || $controllerName === 'AccountController') {
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

// GLOBAL FOOTER INCLUDED ONCE FOR THE ENTIRE APPLICATION
include 'app/views/shares/footer.php';
?>