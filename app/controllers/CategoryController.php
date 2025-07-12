<?php
require_once 'app/models/CategoryModel.php';

class CategoryController
{
    private $categoryModel;
    
    // Khởi tạo đối tượng CategoryController với kết nối DB
    public function __construct($db) {
        // Kiểm tra nếu có đối tượng DB
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        if (is_null($db)) {
            die("Không thể kết nối đến cơ sở dữ liệu.");
        }
        $this->categoryModel = new CategoryModel($db);  // Truyền đối tượng DB vào CategoryModel
    }

    // Hiển thị danh sách danh mục
    public function index()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        try {
            $categories = $this->categoryModel->getCategories();
            include 'app/views/category/index.php';
        } catch (Exception $e) {
            die("Lỗi khi lấy danh mục: " . $e->getMessage());
        }
    }
    

    // Xem thông tin chi tiết của danh mục
    public function view($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        try {
            // Lấy danh mục theo ID
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                die("Danh mục không tồn tại!");
            }
            include 'app/views/category/view.php';  // Hiển thị view của danh mục
        } catch (Exception $e) {
            die("Lỗi khi lấy thông tin danh mục: " . $e->getMessage());
        }
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        include 'app/views/category/create.php';
    }

    // Xử lý thêm danh mục
    public function store()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Kiểm tra nếu tên danh mục không trống
            if (!empty($name)) {
                try {
                    $this->categoryModel->addCategory($name, $description);
                    header("Location: /Category/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi thêm danh mục: " . $e->getMessage());
                }
            } else {
                echo "Tên danh mục không được để trống!";
            }
        }
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            die("Danh mục không tồn tại!");
        }
        include 'app/views/category/edit.php';
    }

    // Xử lý cập nhật danh mục
    public function update($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Kiểm tra nếu tên danh mục không trống
            if (!empty($name)) {
                try {
                    $this->categoryModel->updateCategory($id, $name, $description);
                    header("Location: /Category/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi cập nhật danh mục: " . $e->getMessage());
                }
            } else {
                echo "Tên danh mục không được để trống!";
            }
        }
    }

    // Xử lý xóa danh mục
    public function delete($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa danh mục.");
        }
        try {
            $this->categoryModel->deleteCategory($id);
            header("Location: /Category/index");
            exit;
        } catch (Exception $e) {
            die("Lỗi xóa danh mục: " . $e->getMessage());
        }
    }
}
?>
