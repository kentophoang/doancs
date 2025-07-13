<?php
require_once 'app/models/SubjectModel.php';

class SubjectController
{
    private $subjectModel;
    
    public function __construct($db) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        if (is_null($db)) {
            die("Không thể kết nối đến cơ sở dữ liệu.");
        }
        $this->subjectModel = new SubjectModel($db);
    }

    public function index()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        try {
            $subjects = $this->subjectModel->getSubjects();
            include 'app/views/subject/index.php';
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề: " . $e->getMessage());
        }
    }
    
    public function view($id)
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        try {
            $subject = $this->subjectModel->getSubjectById($id);
            if (!$subject) {
                die("Chủ đề/Ngành nghề không tồn tại!");
            }
            include 'app/views/subject/view.php';
        } catch (Exception $e) {
            die("Lỗi khi lấy thông tin chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    public function create()
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        include 'app/views/subject/create.php';
    }

    public function store()
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                try {
                    $this->subjectModel->addSubject($name, $description);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi thêm chủ đề/ngành nghề: " . $e->getMessage());
                }
            } else {
                echo "Tên chủ đề/ngành nghề không được để trống!";
            }
        }
    }

    public function edit($id)
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        $subject = $this->subjectModel->getSubjectById($id);
        if (!$subject) {
            die("Chủ đề/Ngành nghề không tồn tại!");
        }
        include 'app/views/subject/edit.php';
    }

    public function update($id)
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                try {
                    $this->subjectModel->updateSubject($id, $name, $description);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi cập nhật chủ đề/ngành nghề: " . $e->getMessage());
                }
            } else {
                echo "Tên chủ đề/ngành nghề không được để trống!";
            }
        }
    }

    public function delete($id)
    {
  
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            die("Bạn không có quyền chỉnh sửa chủ đề/ngành nghề.");
        }
        try {
            $this->subjectModel->deleteSubject($id);
            header("Location: /Subject/index");
            exit;
        } catch (Exception $e) {
            die("Lỗi xóa chủ đề/ngành nghề: " . $e->getMessage());
        }
    }
}
?>