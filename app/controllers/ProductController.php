<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
class ProductController
{
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);  // Khởi tạo model Category
    }

    public function index()
{
    $categories = $this->categoryModel->getCategories();
    $products = $this->productModel->getProducts(); 

    // Lọc theo danh mục
    $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;
    if ($categoryId) {
        $products = $this->productModel->getProductsByCategory($categoryId);
    }

    // Lọc theo giá nếu có tham số price_range
    $minPrice = 0;
    $maxPrice = null;
    if (isset($_GET['price_range']) && $_GET['price_range'] !== "all") {
        $priceRange = $_GET['price_range'];
        if (strpos($priceRange, '-') !== false) {
            list($minPrice, $maxPrice) = explode('-', $priceRange);
            $minPrice = is_numeric($minPrice) ? (float)$minPrice : 0;
            $maxPrice = is_numeric($maxPrice) ? (float)$maxPrice : null;
        }
    }
        // Áp dụng bộ lọc giá cho danh mục cụ thể (nếu có)
        if ($categoryId) {
            $products = $this->productModel->getProductsByPrice($minPrice, $maxPrice, $categoryId);
        } else {
            $products = $this->productModel->getProductsByPrice($minPrice, $maxPrice);
        }

    // Sắp xếp theo giá
    if (isset($_GET['sort'])) {
        $sortOrder = $_GET['sort'] === 'high' ? 'DESC' : 'ASC';
        $products = $this->productModel->getProductsSorted($sortOrder, $minPrice, $maxPrice, $categoryId);
    }
    include 'app/views/product/list.php';

    }

    



    
    public function search()
{
    // Kiểm tra xem có từ khóa tìm kiếm trong URL không
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Lấy tất cả danh mục và sản phẩm
    $categories = $this->categoryModel->getCategories();

    // Lọc sản phẩm theo từ khóa tìm kiếm
    if (!empty($searchTerm)) {
        $products = $this->productModel->searchProducts($searchTerm);  // Tìm kiếm theo từ khóa
    } else {
        $products = $this->productModel->getProducts(); // Hiển thị tất cả sản phẩm nếu không có từ khóa
    }

    include 'app/views/product/list.php';  // Hiển thị kết quả tìm kiếm
}



public function updateCart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }

        // Tính tổng tiền mới
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        echo json_encode(['success' => true, 'total' => number_format($total, 0, ',', '.') . ' đ']);
        exit;
    }
}
//xóa sản phẩm trong gio hang

public function removeFromCart() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['product_id'] ?? null;
        
        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        // Tính tổng tiền mới sau khi xóa sản phẩm
        $total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        echo json_encode(['success' => true, 'total' => number_format($total, 0, ',', '.') . ' đ']);
        exit;
    }
}




    public function getProductsByCategory($categoryId)
    {
        $categories = $this->categoryModel->getCategories();
        $products = $this->productModel->getProductsByCategory($categoryId);
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function list()
    {
        $this->index(); // Gọi lại index để hiển thị danh sách sản phẩm
    }

    public function add()
    {
        if ($_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền thêm sản phẩm.";
            return;
        }
        $categories = $this->categoryModel->getCategories();  // Lấy danh sách danh mục
        include_once 'app/views/product/add.php'; // Chuyển đến trang thêm sản phẩm
    }

    public function save()
    {
        if ($_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền thêm sản phẩm.";
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $errors = $result;
                $categories = $this->categoryModel->getCategories();  // Lấy lại danh mục
                include 'app/views/product/add.php';
            } else {
                header('Location: /Product');
            }
        }
    }

    public function edit($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền sửa sản phẩm.";
            return;
        }
        $product = $this->productModel->getProductById($id);
        $categories = $this->categoryModel->getCategories();  // Lấy danh mục
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: /Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền xóa sản phẩm.";
         return;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }
            $this->db->beginTransaction();
            try {
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }
                unset($_SESSION['cart']);
                $this->db->commit();
                header('Location: /Product/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
