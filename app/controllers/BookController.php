<?php
require_once('app/config/database.php');
require_once('app/models/BookModel.php');
require_once('app/models/SubjectModel.php');
require_once('app/models/AccountModel.php'); 
// require_once('app/helpers/SessionHelper.php'); 

class BookController
{
    private $bookModel;
    private $subjectModel;
    private $accountModel; 
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->bookModel = new BookModel($this->db);
        $this->subjectModel = new SubjectModel($this->db);
        $this->accountModel = new AccountModel($this->db);
    }

    public function index()
    {
        // Lấy tất cả chủ đề để truyền vào header (để hiển thị dropdown phân cấp)
        $subjects = $this->subjectModel->getSubjects(); 
        
        $subjectId = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;
        $minYear = isset($_GET['min_year']) ? $_GET['min_year'] : null;
        $maxYear = isset($_GET['max_year']) ? $_GET['max_year'] : null;
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : null;

        $books = $this->bookModel->getBooks($subjectId, $minYear, $maxYear, $searchTerm, $sortOrder);

        include 'app/views/book/list.php';
    }

    public function show($id)
    {
        $book = $this->bookModel->getBookById($id);
        if ($book) {
            include 'app/views/book/show.php';
        } else {
            echo "Không tìm thấy sách.";
        }
    }

    public function add()
    {
 
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền thêm sách.";
            return;
        }
        $subjects = $this->subjectModel->getSubjects(); // Lấy tất cả chủ đề
        $subjectsByParent = [];
        foreach ($subjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        include_once 'app/views/book/add.php';
    }

    public function save()
    {
 
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền thêm sách.";
            return;
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

            if (is_array($addResult)) {
                $errors = array_merge($errors, $addResult);
                $subjects = $this->subjectModel->getSubjects(); 
                $subjectsByParent = [];
                foreach ($subjects as $sub) {
                    $parentId = $sub->parent_id ?? 0;
                    $subjectsByParent[$parentId][] = $sub;
                }
                include 'app/views/book/add.php';
            } else if ($addResult) {
                header('Location: /Book');
                exit();
            } else {
                 $errors['db_error'] = "Đã xảy ra lỗi khi lưu sách vào cơ sở dữ liệu.";
                 $subjects = $this->subjectModel->getSubjects(); 
                 $subjectsByParent = [];
                 foreach ($subjects as $sub) {
                     $parentId = $sub->parent_id ?? 0;
                     $subjectsByParent[$parentId][] = $sub;
                 }
                 include 'app/views/book/add.php';
            }
        }
    }

    public function edit($id)
    {
 
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền sửa sách.";
            return;
        }
        $book = $this->bookModel->getBookById($id);
        $subjects = $this->subjectModel->getSubjects(); // Lấy tất cả chủ đề
        $subjectsByParent = [];
        foreach ($subjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        if ($book) {
            include 'app/views/book/edit.php';
        } else {
            echo "Không tìm thấy sách.";
        }
    }

    public function update()
    {
 
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền sửa sách.";
            return;
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

            $image = $_POST['existing_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadFile($_FILES['image']);
                } catch (Exception $e) {
                    // Xử lý lỗi tải lên ảnh
                }
            }

            $edit = $this->bookModel->updateBook($id, $name, $description, $author, $publisher, $publication_year, $ISBN, $subject_id, $image, $number_of_copies);
            if ($edit) {
                header('Location: /Book');
                exit();
            } else {
                echo "Đã xảy ra lỗi khi lưu sách.";
            }
        }
    }

    public function delete($id)
    {
 
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "Bạn không có quyền xóa sách.";
            return;
        }
        if ($this->bookModel->deleteBook($id)) {
            header('Location: /Book');
            exit();
        } else {
            echo "Đã xảy ra lỗi khi xóa sách.";
        }
    }

    private function uploadFile($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!in_array($fileType, ["jpg", "png", "jpeg", "gif", "pdf", "doc", "docx", "txt"])) { 
            throw new Exception("Chỉ cho phép các định dạng ảnh (JPG, JPEG, PNG, GIF) và tài liệu (PDF, DOC, DOCX, TXT).");
        }
        if ($file["size"] > 20 * 1024 * 1024) { 
            throw new Exception("File có kích thước quá lớn (tối đa 20MB).");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên file.");
        }
        return $target_file;
    }

    // --- Các phương thức mới cho chức năng thư viện ---

    public function borrow($id) {
 
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
            header('Location: /account/login');
            exit();
        }
        $book = $this->bookModel->getBookById($id);
        if (!$book) {
            echo "Không tìm thấy sách.";
            return;
        }

        if ($book->available_copies > 0) {
            if ($this->bookModel->decreaseAvailableCopies($id)) {
                echo "<script>alert('Bạn đã mượn sách thành công!'); window.location.href='/Book';</script>";
            } else {
                echo "<script>alert('Không thể mượn sách. Vui lòng thử lại.'); window.location.href='/Book';</script>";
            }
        } else {
            echo "<script>alert('Sách đã hết. Vui lòng chờ đợt sau.'); window.location.href='/Book';</script>";
        }
    }

    public function returnBook($id) {
 
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
            header('Location: /account/login');
            exit();
        }

        if ($this->bookModel->increaseAvailableCopies($id)) {
            echo "<script>alert('Bạn đã trả sách thành công!'); window.location.href='/Book';</script>";
        } else {
            echo "<script>alert('Không thể trả sách. Vui lòng thử lại.'); window.location.href='/Book';</script>";
        }
    }

    public function myBorrowedBooks() {
 
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
            header('Location: /account/login');
            exit();
        }
        $borrowedBooks = []; 
        
        include 'app/views/book/borrowed_list.php';
    }
}
?>