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
            // Tổ chức subjects theo parent_id để hiển thị phân cấp
            $subjectsByParent = [];
            foreach ($subjects as $sub) {
                $parentId = $sub->parent_id ?? 0; // Sử dụng 0 cho các chủ đề cấp cao nhất
                $subjectsByParent[$parentId][] = $sub;
            }
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
            // Lấy tên chủ đề cha nếu có
            $parentSubjectName = null;
            if ($subject->parent_id) {
                $parentSubject = $this->subjectModel->getSubjectById($subject->parent_id);
                if ($parentSubject) {
                    $parentSubjectName = $parentSubject->name;
                }
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
        $parentSubjects = $this->subjectModel->getParentSubjects(); // Lấy danh sách các chủ đề cha
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
            $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id']; // Lấy parent_id, chuyển rỗng thành null

            if (!empty($name)) {
                try {
                    $this->subjectModel->addSubject($name, $description, $parentId);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi thêm chủ đề/ngành nghề: " . $e->getMessage());
                }
            } else {
                // Nếu có lỗi, bạn có thể truyền biến errors và parentSubjects trở lại view
                $errors = ["Tên chủ đề/ngành nghề không được để trống!"];
                $parentSubjects = $this->subjectModel->getParentSubjects();
                include 'app/views/subject/create.php';
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
        $parentSubjects = $this->subjectModel->getParentSubjects(); // Lấy danh sách các chủ đề cha
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
            $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id']; // Lấy parent_id, chuyển rỗng thành null

            if (!empty($name)) {
                try {
                    $this->subjectModel->updateSubject($id, $name, $description, $parentId);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    die("Lỗi cập nhật chủ đề/ngành nghề: " . $e->getMessage());
                }
            } else {
                // Nếu có lỗi, bạn có thể truyền biến errors, subject và parentSubjects trở lại view
                $errors = ["Tên chủ đề/ngành nghề không được để trống!"];
                $subject = $this->subjectModel->getSubjectById($id);
                $parentSubjects = $this->subjectModel->getParentSubjects();
                include 'app/views/subject/edit.php';
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