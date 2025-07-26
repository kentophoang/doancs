<?php
// Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// Nạp các file Model cần thiết
require_once ROOT_PATH . '/app/models/BookModel.php';
require_once ROOT_PATH . '/app/models/SubjectModel.php';
require_once ROOT_PATH . '/app/models/LoanModel.php';

class BookController
{
    private $bookModel;
    private $subjectModel;
    private $loanModel;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->bookModel = new BookModel($this->db);
        $this->subjectModel = new SubjectModel($this->db);
        $this->loanModel = new LoanModel($this->db);
    }

    /**
     * [PUBLIC] Hiển thị trang danh mục sách cho người dùng.
     */
    public function index()
    {
        $subjectId = $_GET['subject_id'] ?? null;
        $searchTerm = $_GET['search'] ?? null;
        $sortOrder = $_GET['sort'] ?? null;

        $books = $this->bookModel->getBooks($subjectId, null, null, $searchTerm, $sortOrder);
        $subjects = $this->subjectModel->getSubjects();
        $currentSubject = $subjectId ? $this->subjectModel->getSubjectById($subjectId) : null;

        ob_start();
        include ROOT_PATH . '/app/views/book/index.php'; // Giao diện thẻ bài công khai
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [ADMIN] Hiển thị trang quản lý sách cho admin.
     */
    public function list()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        
        $subjectId = $_GET['subject_id'] ?? null;
        $minYear = $_GET['min_year'] ?? null;
        $maxYear = $_GET['max_year'] ?? null;
        $searchTerm = $_GET['search'] ?? null;
        $sortOrder = $_GET['sort'] ?? null;

        $books = $this->bookModel->getBooks($subjectId, $minYear, $maxYear, $searchTerm, $sortOrder);
        $subjects = $this->subjectModel->getSubjects();

        ob_start();
        include ROOT_PATH . '/app/views/book/list.php'; // Giao diện bảng quản lý
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [PUBLIC] Hiển thị chi tiết một cuốn sách.
     */
    public function show($id)
    {
        $book = $this->bookModel->getBookById($id);
        if (!$book) {
            show_404();
        }

        ob_start();
        include ROOT_PATH . '/app/views/book/show.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [ADMIN] Hiển thị form thêm sách mới.
     */
    public function add()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $subjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($subjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }

        ob_start();
        include_once ROOT_PATH . '/app/views/book/add.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [ADMIN] Xử lý lưu sách mới.
     */
    public function save()
    {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/login');
            exit();
        }
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $author = $_POST['author'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $publication_year = $_POST['publication_year'] ?? '';
        $ISBN = $_POST['ISBN'] ?? '';
        $subject_id = $_POST['subject_id'] ?? null;
        $number_of_copies = $_POST['number_of_copies'] ?? '';
        $location = $_POST['location'] ?? ''; // Thêm location

        $image = "";
        $errors = [];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadFile($_FILES['image']);
            } catch (Exception $e) {
                $errors['image'] = $e->getMessage();
            }
        }

        $addResult = $this->bookModel->addBook($name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies, $location);

        if (is_array($addResult)) {
            $errors = array_merge($errors, $addResult);
            $_SESSION['form_errors'] = $errors;
            $_SESSION['POST_data'] = $_POST;
            header('Location: /Book/add');
        } else if ($addResult) {
            $_SESSION['success_message'] = "Thêm sách thành công!";
            header('Location: /Book/list');
        } else {
            $_SESSION['error_message'] = "Đã xảy ra lỗi khi lưu sách.";
            header('Location: /Book/add');
        }
        exit();
    }

    /**
     * [ADMIN] Hiển thị form sửa sách.
     */
    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        
        $book = $this->bookModel->getBookById($id);
        if (!$book) {
            show_404();
        }

        $allSubjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($allSubjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }

        $data = [
            'book' => $book,
            'subjectsByParent' => $subjectsByParent,
            'allSubjects' => $allSubjects
        ];

        ob_start();
        include ROOT_PATH . '/app/views/book/edit.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [ADMIN] Xử lý cập nhật sách.
     */
    public function update()
    {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/login');
            exit();
        }

        $id = $_POST['id'];
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $author = $_POST['author'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $publication_year = $_POST['publication_year'] ?? '';
        $ISBN = $_POST['ISBN'] ?? '';
        $subject_id = $_POST['subject_id'] ?? null;
        $number_of_copies = $_POST['number_of_copies'] ?? '';
        $location = $_POST['location'] ?? ''; // Thêm location

        $currentBook = $this->bookModel->getBookById($id);
        $image = $currentBook->image ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $newImage = $this->uploadFile($_FILES['image']);
                if ($image && file_exists($image) && $image !== 'uploads/default-book.jpg') {
                    unlink($image);
                }
                $image = $newImage;
            } catch (Exception $e) {
                $_SESSION['form_errors'] = ['image' => $e->getMessage()];
                $_SESSION['POST_data'] = $_POST;
                header('Location: /book/edit/' . $id);
                exit();
            }
        }

        $result = $this->bookModel->updateBook($id, $name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies, $location);

        if ($result) {
            $_SESSION['success_message'] = "Cập nhật sách thành công!";
            header('Location: /Book/list');
        } else {
            $_SESSION['error_message'] = "Cập nhật sách thất bại. Vui lòng thử lại.";
            header('Location: /book/edit/' . $id);
        }
        exit();
    }

    /**
     * [ADMIN] Xử lý xóa sách.
     */
    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $book = $this->bookModel->getBookById($id);
        if ($this->bookModel->deleteBook($id)) {
            if ($book && $book->image && file_exists($book->image) && $book->image !== 'uploads/default-book.jpg') {
                unlink($book->image);
            }
            $_SESSION['success_message'] = "Xóa sách thành công!";
        } else {
            $_SESSION['error_message'] = "Đã xảy ra lỗi khi xóa sách.";
        }
        header('Location: /Book/list');
        exit();
    }

    /**
     * [USER] Xử lý mượn sách.
     */
    public function borrow($book_id) {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $book = $this->bookModel->getBookById($book_id);
        if ($book && $book->available_copies > 0) {
            $borrow_date = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+14 days'));
            $user_id = $_SESSION['user_id'];

            if ($this->bookModel->decreaseAvailableCopies($book_id) && $this->loanModel->createLoan($book_id, $user_id, $borrow_date, $due_date)) {
                $_SESSION['success_message'] = "Bạn đã mượn sách thành công!";
            } else {
                $this->bookModel->increaseAvailableCopies($book_id); // Rollback
                $_SESSION['error_message'] = "Không thể ghi nhận giao dịch mượn.";
            }
        } else {
            $_SESSION['error_message'] = "Sách đã hết hoặc không tồn tại.";
        }
        header('Location: /Book');
        exit();
    }

    /**
     * [USER] Hiển thị danh sách sách đã mượn.
     */
    public function myBorrowedBooks() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $borrowedBooks = $this->loanModel->getAllLoans(null, null, $user_id); 
        
        ob_start();
        include ROOT_PATH . '/app/views/book/borrowed_list.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [HELPER] Xử lý tải file ảnh lên.
     */
    private function uploadFile($file)
    {
        $target_dir = ROOT_PATH . "/public/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $fileName = uniqid() . '-' . basename($file["name"]);
        $target_file = $target_dir . $fileName;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!in_array($fileType, ["jpg", "png", "jpeg", "gif"])) { 
            throw new Exception("Chỉ cho phép các định dạng ảnh (JPG, JPEG, PNG, GIF).");
        }
        if ($file["size"] > 5 * 1024 * 1024) { // Giới hạn 5MB
            throw new Exception("File có kích thước quá lớn (tối đa 5MB).");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên file.");
        }
        // Trả về đường dẫn tương đối để lưu vào CSDL
        return "uploads/" . $fileName;
    }
    /**
     * [USER] Thêm một cuốn sách vào giỏ hàng mượn.
     */
    public function addToCart($book_id)
    {
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['error_message'] = "Vui lòng đăng nhập để mượn sách.";
            header('Location: /account/login');
            exit();
        }

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['borrow_cart'])) {
            $_SESSION['borrow_cart'] = [];
        }

        // Thêm sách vào giỏ nếu chưa có
        if (!in_array($book_id, $_SESSION['borrow_cart'])) {
            $_SESSION['borrow_cart'][] = $book_id;
            $_SESSION['success_message'] = "Đã thêm sách vào giỏ mượn!";
        } else {
            $_SESSION['info_message'] = "Sách này đã có trong giỏ mượn của bạn.";
        }
        
        // Quay lại trang trước đó
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/Book'));
        exit();
    }
    /**
     * [USER] Hiển thị trang Giỏ hàng mượn (Phiếu mượn).
     */
    public function cart()
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }

        $cart_ids = $_SESSION['borrow_cart'] ?? [];
        $booksInCart = [];
        if (!empty($cart_ids)) {
            $booksInCart = $this->bookModel->getBooksByIds($cart_ids);
        }

        $data = ['booksInCart' => $booksInCart];

        ob_start();
        include ROOT_PATH . '/app/views/book/cart.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [USER] Xóa một cuốn sách khỏi giỏ hàng mượn.
     */
    public function removeFromCart($book_id)
    {
        if (isset($_SESSION['borrow_cart'])) {
            // Tìm và xóa book_id khỏi mảng session
            $_SESSION['borrow_cart'] = array_diff($_SESSION['borrow_cart'], [$book_id]);
        }
        header('Location: /book/cart');
        exit();
    }

    /**
     * [USER] Xử lý xác nhận mượn tất cả sách trong giỏ.
     */
    public function processBorrow()
    {
        if (!SessionHelper::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit();
        }

        $cart_ids = $_SESSION['borrow_cart'] ?? [];
        if (empty($cart_ids)) {
            $_SESSION['error_message'] = "Giỏ mượn của bạn đang trống.";
            header('Location: /book/cart');
            exit();
        }
        
        $borrowed_books = [];
        $failed_books = [];
        $user_id = $_SESSION['user_id'];
        $borrow_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+14 days'));

        $booksToBorrow = $this->bookModel->getBooksByIds($cart_ids);

        foreach ($booksToBorrow as $book) {
            if ($book->available_copies > 0) {
                // Mượn sách thành công
                $this->bookModel->decreaseAvailableCopies($book->id);
                $this->loanModel->createLoan($book->id, $user_id, $borrow_date, $due_date);
                $borrowed_books[] = $book->name;
            } else {
                // Sách đã hết khi xác nhận
                $failed_books[] = $book->name;
            }
        }
        
        // Xóa giỏ hàng sau khi xử lý
        unset($_SESSION['borrow_cart']);

        // Tạo thông báo kết quả
        $_SESSION['borrow_results'] = [
            'success' => $borrowed_books,
            'failed' => $failed_books
        ];
        
        header('Location: /book/borrowSuccess');
        exit();
    }

    /**
     * [USER] Hiển thị trang xác nhận mượn sách thành công.
     */
    public function borrowSuccess()
    {
        ob_start();
        include ROOT_PATH . '/app/views/book/borrow_success.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }
    /**
     * [USER] Hiển thị trang xác nhận trả sách.
     */
    public function returnBook($loan_id)
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }

        $loan = $this->loanModel->getLoanById($loan_id);
        if (!$loan || $loan->user_id != $_SESSION['user_id'] || $loan->status === 'returned') {
            $_SESSION['error_message'] = "Không tìm thấy giao dịch mượn hoặc bạn không có quyền thực hiện thao tác này.";
            header('Location: /book/myBorrowedBooks');
            exit();
        }

        // Tính toán phí phạt (nếu có)
        $fine_amount = 0;
        $days_overdue = 0;
        $is_overdue = strtotime(date('Y-m-d')) > strtotime($loan->due_date);
        if ($is_overdue) {
            $days_overdue = (new DateTime())->diff(new DateTime($loan->due_date))->days;
            $fine_amount = $days_overdue * 5000; // Ví dụ: 5000đ/ngày
        }

        $data = [
            'loan' => $loan,
            'fine_amount' => $fine_amount,
            'days_overdue' => $days_overdue,
            'is_overdue' => $is_overdue
        ];

        ob_start();
        include ROOT_PATH . '/app/views/book/return_confirm.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [USER] Xử lý yêu cầu trả sách sau khi xác nhận.
     */
    public function processReturn()
    {
        if (!SessionHelper::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit();
        }

        $loan_id = $_POST['loan_id'];
        $loan = $this->loanModel->getLoanById($loan_id);

        // Kiểm tra lại quyền sở hữu và trạng thái
        if (!$loan || $loan->user_id != $_SESSION['user_id'] || $loan->status === 'returned') {
            $_SESSION['error_message'] = "Giao dịch không hợp lệ.";
            header('Location: /book/myBorrowedBooks');
            exit();
        }

        // Tính lại phí phạt để đảm bảo chính xác
        $return_date = date('Y-m-d');
        $fine_amount = 0;
        if (strtotime($return_date) > strtotime($loan->due_date)) {
            $days_overdue = (new DateTime($return_date))->diff(new DateTime($loan->due_date))->days;
            $fine_amount = $days_overdue * 5000;
        }

        // Tăng lại số lượng sách có sẵn
        if ($this->bookModel->increaseAvailableCopies($loan->book_id)) {
            // Cập nhật bản ghi mượn sách
            if ($this->loanModel->returnLoan($loan_id, $return_date, $fine_amount)) {
                $_SESSION['success_message'] = "Bạn đã trả sách thành công!" . ($fine_amount > 0 ? " Phí trễ hạn của bạn là: " . number_format($fine_amount) . " đ." : "");
            } else {
                // Rollback: Nếu không cập nhật được CSDL, trả lại số lượng sách đã tăng
                $this->bookModel->decreaseAvailableCopies($loan->book_id);
                $_SESSION['error_message'] = "Không thể ghi nhận giao dịch trả sách. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật số lượng sách.";
        }
        
        header('Location: /book/myBorrowedBooks');
        exit();
    }
}
