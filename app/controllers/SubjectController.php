<?php
require_once 'app/models/SubjectModel.php';
require_once 'app/helpers/SessionHelper.php';

class SubjectController
{
    private $subjectModel;
    
    public function __construct($db) {
        if (is_null($db)) {
            die("Không thể kết nối đến cơ sở dữ liệu.");
        }
        $this->subjectModel = new SubjectModel($db);
        SessionHelper::start(); // Ensure session is started for all SubjectController actions
        if (!SessionHelper::isAdmin()) { // Restrict access for non-admins
            die("Bạn không có quyền truy cập trang quản lý chủ đề/ngành nghề.");
        }
    }

    public function index()
    {
        try {
            $subjects = $this->subjectModel->getSubjects();
            $subjectsByParent = [];
            foreach ($subjects as $sub) {
                $parentId = $sub->parent_id ?? 0;
                $subjectsByParent[$parentId][] = $sub;
            }
            ob_start();
            include 'app/views/subject/index.php';
            $main_content = ob_get_clean();
            include 'app/views/shares/admin_layout.php';
        } catch (Exception $e) {
            die("Lỗi khi lấy chủ đề/ngành nghề: " . $e->getMessage());
        }
    }
    
    public function view($id)
    {
        try {
            $subject = $this->subjectModel->getSubjectById($id);
            if (!$subject) {
                die("Chủ đề/Ngành nghề không tồn tại!");
            }
            $parentSubjectName = null;
            if ($subject->parent_id) {
                $parentSubject = $this->subjectModel->getSubjectById($subject->parent_id);
                if ($parentSubject) {
                    $parentSubjectName = $parentSubject->name;
                }
            }
            ob_start();
            include 'app/views/subject/view.php';
            $main_content = ob_get_clean();
            include 'app/views/shares/admin_layout.php';
        } catch (Exception $e) {
            die("Lỗi khi lấy thông tin chủ đề/ngành nghề: " . $e->getMessage());
        }
    }

    public function create()
    {
        $parentSubjects = $this->subjectModel->getSubjects(); // Use getSubjects to get all for hierarchical display
        $subjectsByParent = [];
        foreach ($parentSubjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        ob_start();
        include 'app/views/subject/create.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id'];

            if (!empty($name)) {
                try {
                    $this->subjectModel->addSubject($name, $description, $parentId);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    $errors = ["Lỗi thêm chủ đề/ngành nghề: " . $e->getMessage()];
                    $parentSubjects = $this->subjectModel->getSubjects();
                    $subjectsByParent = [];
                    foreach ($parentSubjects as $sub) {
                        $parentId = $sub->parent_id ?? 0;
                        $subjectsByParent[$parentId][] = $sub;
                    }
                    ob_start();
                    include 'app/views/subject/create.php';
                    $main_content = ob_get_clean();
                    include 'app/views/shares/admin_layout.php';
                }
            } else {
                $errors = ["Tên chủ đề/ngành nghề không được để trống!"];
                $parentSubjects = $this->subjectModel->getSubjects();
                $subjectsByParent = [];
                foreach ($parentSubjects as $sub) {
                    $parentId = $sub->parent_id ?? 0;
                    $subjectsByParent[$parentId][] = $sub;
                }
                ob_start();
                include 'app/views/subject/create.php';
                $main_content = ob_get_clean();
                include 'app/views/shares/admin_layout.php';
            }
        }
    }

    public function edit($id)
    {
        $subject = $this->subjectModel->getSubjectById($id);
        if (!$subject) {
            die("Chủ đề/Ngành nghề không tồn tại!");
        }
        $parentSubjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($parentSubjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        ob_start();
        include 'app/views/subject/edit.php';
        $main_content = ob_get_clean();
        include 'app/views/shares/admin_layout.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id'];

            if (!empty($name)) {
                try {
                    $this->subjectModel->updateSubject($id, $name, $description, $parentId);
                    header("Location: /Subject/index");
                    exit;
                } catch (Exception $e) {
                    $errors = ["Lỗi cập nhật chủ đề/ngành nghề: " . $e->getMessage()];
                    $subject = $this->subjectModel->getSubjectById($id);
                    $parentSubjects = $this->subjectModel->getSubjects();
                    $subjectsByParent = [];
                    foreach ($parentSubjects as $sub) {
                        $parentId = $sub->parent_id ?? 0;
                        $subjectsByParent[$parentId][] = $sub;
                    }
                    ob_start();
                    include 'app/views/subject/edit.php';
                    $main_content = ob_get_clean();
                    include 'app/views/shares/admin_layout.php';
                }
            } else {
                $errors = ["Tên chủ đề/ngành nghề không được để trống!"];
                $subject = $this->subjectModel->getSubjectById($id);
                $parentSubjects = $this->subjectModel->getSubjects();
                $subjectsByParent = [];
                foreach ($parentSubjects as $sub) {
                    $parentId = $sub->parent_id ?? 0;
                    $subjectsByParent[$parentId][] = $sub;
                }
                ob_start();
                include 'app/views/subject/edit.php';
                $main_content = ob_get_clean();
                include 'app/views/shares/admin_layout.php';
            }
        }
    }

    public function delete($id)
    {
        try {
            $this->subjectModel->deleteSubject($id);
            header("Location: /Subject/index");
            exit;
        } catch (Exception $e) {
            die("Lỗi xóa chủ đề/ngành nghề: " . $e->getMessage());
        }
    }
}