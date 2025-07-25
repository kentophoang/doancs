<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/models/BookModel.php');
require_once('app/models/SubjectModel.php');
require_once('app/models/AccountModel.php');
require_once('app/models/LoanModel.php'); // Thêm LoanModel
require_once('app/models/ReservationModel.php'); // Thêm ReservationModel

class BookController
{
    private $bookModel;
    private $subjectModel;
    private $accountModel; 
    private $loanModel; // Khai báo LoanModel
    private $reservationModel; // Khai báo ReservationModel
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->bookModel = new BookModel($this->db);
        $this->subjectModel = new SubjectModel($this->db);
        $this->accountModel = new AccountModel($this->db);
        $this->loanModel = new LoanModel($this->db); // Khởi tạo LoanModel
        $this->reservationModel = new ReservationModel($this->db); // Khởi tạo ReservationModel
    }

    public function index()
    {
        SessionHelper::start();
        
        $subjectId = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;
        $minYear = isset($_GET['min_year']) ? $_GET['min_year'] : null;
        $maxYear = isset($_GET['max_year']) ? $_GET['max_year'] : null;
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : null;

        $books = $this->bookModel->getBooks($subjectId, $minYear, $maxYear, $searchTerm, $sortOrder);

        $subjects = $this->subjectModel->getSubjects();

        ob_start(); 
        include 'app/views/book/list.php';
        $main_content = ob_get_clean(); 

        // Sửa đổi logic render layout
        if (SessionHelper::isAdmin()) {
            include 'app/views/shares/admin_layout.php'; 
        } else {
            // Đối với người dùng thông thường, list.php đã bao gồm header/footer
            // Tuy nhiên, vì header/footer được include toàn cục trong index.php, 
            // và list.php được thiết kế để hoạt động trong admin_layout,
            // nên cần tạo một public_layout riêng nếu muốn phức tạp hơn,
            // hoặc chấp nhận việc header/footer được lồng (nếu list.php cũng include)
            // Hiện tại, do index.php đã include header/footer,
            // việc echo $main_content (tức list.php) sẽ không có header/footer của riêng nó,
            // nhưng sẽ nằm trong header/footer toàn cục.
            // Để nhất quán, nếu có admin_layout, nên có public_layout.
            // Tạm thời, giữ nguyên cách cũ của bạn, vì nó đã bao gồm header/footer.
            echo $main_content; 
        }
    }

    public function show($id)
    {
        SessionHelper::start();
        $book = $this->bookModel->getBookById($id);
        ob_start();
        if ($book) {
            include 'app/views/book/show.php';
        } else {
            // Thay đổi cách xử lý lỗi, tránh die()
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php';
            exit();
        }
        $main_content = ob_get_clean();

        if (SessionHelper::isAdmin()) {
            include 'app/views/shares/admin_layout.php';
        } else {
            echo $main_content;
        }
    }

    public function add()
    {
        SessionHelper::start(); 
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
        include_once 'app/views/book/add.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function save()
    {
        SessionHelper::start(); 
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $author = $_POST['author'] ?? '';
            $publisher = $_POST['publisher'] ?? '';
            $publication_year = $_POST['publication_year'] ?? '';
            $ISBN = $_POST['ISBN'] ?? '';
            $subject_id = $_POST['subject_id'] ?? null;
            $number_of_copies = $_POST['number_of_copies'] ?? '';

            $image = "";
            $errors = [];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadFile($_FILES['image']);
                } catch (Exception $e) {
                    $errors['image'] = $e->getMessage();
                }
            }

            $addResult = $this->bookModel->addBook($name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies);

            if (is_array($addResult)) { // Nếu có lỗi validation từ Model
                $errors = array_merge($errors, $addResult);
                // Lưu lỗi và dữ liệu POST vào session để hiển thị lại
                $_SESSION['form_errors'] = $errors;
                $_SESSION['POST_data'] = $_POST;
                header('Location: /Book/add');
                exit();
            } else if ($addResult) { // Thêm sách thành công
                $_SESSION['success_message'] = "Thêm sách thành công!";
                header('Location: /Book');
                exit();
            } else { // Lỗi khi thực thi query
                $errors['db_error'] = "Đã xảy ra lỗi khi lưu sách vào cơ sở dữ liệu.";
                $_SESSION['form_errors'] = $errors;
                $_SESSION['POST_data'] = $_POST;
                header('Location: /Book/add');
                exit();
            }
        }
    }

    public function edit($id)
    {
        SessionHelper::start(); 
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $book = $this->bookModel->getBookById($id);
        if (!$book) {
            header("HTTP/1.0 404 Not Found");
            include 'app/views/errors/404.php';
            exit();
        }
        $subjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($subjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        ob_start();
        include 'app/views/book/edit.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function update()
    {
        SessionHelper::start(); 
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $author = $_POST['author'] ?? '';
            $publisher = $_POST['publisher'] ?? '';
            $publication_year = $_POST['publication_year'] ?? '';
            $ISBN = $_POST['ISBN'] ?? '';
            $subject_id = $_POST['subject_id'] ?? null;
            $number_of_copies = $_POST['number_of_copies'] ?? '';

            $currentBook = $this->bookModel->getBookById($id);
            $image = $currentBook->image ?? ''; // Giữ ảnh hiện tại
            $errors = [];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $newImage = $this->uploadFile($_FILES['image']);
                    // Xóa ảnh cũ nếu có ảnh mới và ảnh cũ không phải là default
                    if ($image && $image !== 'uploads/default-book.jpg' && file_exists($image)) {
                        unlink($image);
                    }
                    $image = $newImage;
                } catch (Exception $e) {
                    $errors['image'] = $e->getMessage();
                }
            }

            $updateResult = $this->bookModel->updateBook($id, $name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies);

            if ($updateResult) {
                $_SESSION['success_message'] = "Cập nhật sách thành công!";
                header('Location: /Book');
                exit();
            } else {
                $_SESSION['error_message'] = "Đã xảy ra lỗi khi cập nhật sách.";
                header('Location: /Book/edit/' . $id);
                exit();
            }
        }
    }

    public function delete($id)
    {
        SessionHelper::start(); 
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        // Lấy thông tin sách để xóa file ảnh
        $book = $this->bookModel->getBookById($id);
        if ($this->bookModel->deleteBook($id)) {
            // Xóa file ảnh liên quan nếu tồn tại và không phải ảnh mặc định
            if ($book && $book->image && $book->image !== 'uploads/default-book.jpg' && file_exists($book->image)) {
                unlink($book->image);
            }
            $_SESSION['success_message'] = "Xóa sách thành công!";
            header('Location: /Book');
            exit();
        } else {
            $_SESSION['error_message'] = "Đã xảy ra lỗi khi xóa sách.";
            header('Location: /Book');
            exit();
        }
    }

    private function uploadFile($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $fileName = uniqid() . '-' . basename($file["name"]); // Đảm bảo tên file là duy nhất
        $target_file = $target_dir . $fileName;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Chỉ cho phép các định dạng ảnh
        if (!in_array($fileType, ["jpg", "png", "jpeg", "gif"])) { 
            throw new Exception("Chỉ cho phép các định dạng ảnh (JPG, JPEG, PNG, GIF).");
        }
        if ($file["size"] > 20 * 1024 * 1024) { 
            throw new Exception("File có kích thước quá lớn (tối đa 20MB).");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên file.");
        }
        return $target_file;
    }

    public function borrow($book_id) { // Đổi $id thành $book_id để rõ ràng
        SessionHelper::start(); 
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $book = $this->bookModel->getBookById($book_id);
        if (!$book) {
            $_SESSION['error_message'] = "Không tìm thấy sách để mượn.";
            header('Location: /Book');
            exit();
        }

        if ($book->available_copies > 0) {
            // Ngày mượn là hôm nay
            $borrow_date = date('Y-m-d');
            // Ngày đến hạn là 14 ngày sau (ví dụ)
            $due_date = date('Y-m-d', strtotime('+14 days'));
            $user_id = $_SESSION['user_id'];

            // Giảm số lượng bản sao có sẵn trong bảng books
            if ($this->bookModel->decreaseAvailableCopies($book_id)) {
                // Tạo một bản ghi mượn sách mới trong bảng loans
                if ($this->loanModel->createLoan($book_id, $user_id, $borrow_date, $due_date)) {
                    $_SESSION['success_message'] = "Bạn đã mượn sách thành công!";
                } else {
                    // Nếu tạo bản ghi loan thất bại, tăng lại số lượng sách (rollback)
                    $this->bookModel->increaseAvailableCopies($book_id);
                    $_SESSION['error_message'] = "Không thể ghi nhận giao dịch mượn. Vui lòng thử lại.";
                }
            } else {
                $_SESSION['error_message'] = "Không thể giảm số lượng sách có sẵn. Sách có thể đã hết.";
            }
        } else {
            $_SESSION['error_message'] = "Sách đã hết. Vui lòng chờ đợt sau hoặc đặt trước.";
        }
        header('Location: /Book');
        exit();
    }

    public function returnBook($loan_id) { // Đổi $id thành $loan_id vì bạn muốn trả một giao dịch mượn cụ thể
        SessionHelper::start(); 
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }

        $loan = $this->loanModel->getLoanById($loan_id);
        if (!$loan || $loan->user_id !== $_SESSION['user_id'] || $loan->status !== 'borrowed') {
            $_SESSION['error_message'] = "Không tìm thấy giao dịch mượn hoặc bạn không có quyền trả sách này.";
            header('Location: /Book/myBorrowedBooks'); // Chuyển hướng về danh sách sách đã mượn
            exit();
        }

        $return_date = date('Y-m-d');
        $fine_amount = 0;
        // Tính phí phạt nếu quá hạn
        if (strtotime($return_date) > strtotime($loan->due_date)) {
            $days_overdue = (new DateTime($return_date))->diff(new DateTime($loan->due_date))->days;
            // Ví dụ: 5000 VND/ngày quá hạn
            $fine_amount = $days_overdue * 5000; 
        }

        // Tăng số lượng bản sao có sẵn trong bảng books
        if ($this->bookModel->increaseAvailableCopies($loan->book_id)) {
            // Cập nhật trạng thái và phí phạt trong bảng loans
            if ($this->loanModel->returnLoan($loan_id, $return_date, $fine_amount)) {
                $_SESSION['success_message'] = "Bạn đã trả sách thành công!" . ($fine_amount > 0 ? " Phí trễ hạn: " . number_format($fine_amount, 0, ',', '.') . " đ." : "");
            } else {
                // Nếu cập nhật bản ghi loan thất bại, giảm lại số lượng sách (rollback)
                $this->bookModel->decreaseAvailableCopies($loan->book_id);
                $_SESSION['error_message'] = "Không thể ghi nhận giao dịch trả sách. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['error_message'] = "Không thể tăng số lượng sách có sẵn. Sách có thể đã đạt tối đa bản sao.";
        }
        header('Location: /Book/myBorrowedBooks'); // Chuyển hướng về danh sách sách đã mượn
        exit();
    }

    public function myBorrowedBooks() {
        SessionHelper::start(); 
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /account/login');
            exit();
        }
        $user_id = $_SESSION['user_id'];
        // Lấy danh sách sách đã mượn từ LoanModel
        $borrowedBooks = $this->loanModel->getAllLoans(null, null, $user_id); 
        
        ob_start();
        include 'app/views/book/borrowed_list.php';
        $main_content = ob_get_clean();
        
        // Vẫn giữ logic cũ của bạn, hoặc tạo public_layout.php riêng
        if (SessionHelper::isAdmin()) {
            include 'app/views/shares/admin_layout.php';
        } else {
            echo $main_content; 
        }
    }

    // Các phương thức Cart, CheckOut, processCheckout nếu bạn giữ lại logic mua bán
    // Cần phải định nghĩa rõ ràng lại hoặc loại bỏ chúng nếu chỉ là hệ thống mượn/trả.
    // Tôi sẽ loại bỏ chúng ở đây để tránh nhầm lẫn.
    // Nếu bạn muốn triển khai "giỏ hàng mượn sách", bạn cần thay đổi logic trong Cart.php, CheckOut.php
    // và tạo các phương thức tương ứng trong BookController để xử lý yêu cầu mượn nhiều sách.
    /*
    public function cart() {
        SessionHelper::start();
        $cart = SessionHelper::getCart();
        // Cần truyền dữ liệu sách đầy đủ cho cart, không chỉ price
        // include 'app/views/book/Cart.php';
    }

    public function checkout() {
        SessionHelper::start();
        // include 'app/views/book/CheckOut.php';
    }

    public function processCheckout() {
        SessionHelper::start();
        // Logic xử lý giỏ hàng/yêu cầu mượn nhiều sách
        // include 'app/views/book/orderConfirmation.php';
    }
    */
}