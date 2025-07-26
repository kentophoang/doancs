<?php
// Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

require_once ROOT_PATH . '/app/models/ChatbotModel.php';
require_once ROOT_PATH . '/app/helpers/GoogleBooksAPI.php';

class ChatbotController {
    private $chatbotModel;

    public function __construct($db) {
        $this->chatbotModel = new ChatbotModel($db);
    }

    public function getRecommendation() {
        $input = json_decode(file_get_contents('php://input'), true);
        $user_query = $input['query'] ?? '';

        if (empty($user_query)) {
            $this->sendReply('Xin lỗi, tôi chưa nhận được câu hỏi của bạn.');
            return;
        }

        // Bước 1: Ưu tiên tìm kiếm trong kho tri thức của thư viện.
        $localResults = $this->chatbotModel->findBooksByConcept($user_query);

        if (!empty($localResults)) {
            $response = $this->formatLocalReply($user_query, $localResults);
            $this->sendReply($response);
            return;
        }

        // Bước 2: Nếu không có, tìm kiếm bên ngoài qua Google Books.
        $externalBooks = GoogleBooksAPI::search($user_query, 3);

        if (empty($externalBooks)) {
            $this->sendReply("Rất tiếc, tôi không tìm thấy tài liệu nào phù hợp với yêu cầu '" . htmlspecialchars($user_query) . "'.");
            return;
        }

        // Bước 3: Kiểm tra và chỉ dẫn vị trí nếu có.
        $response = $this->formatExternalReply($externalBooks);
        $this->sendReply($response);
    }

    private function formatLocalReply($query, $books) {
        $response = "Chào bạn, với yêu cầu về **" . htmlspecialchars($query) . "**, tôi đã tìm thấy các tài liệu chuyên sâu ngay trong thư viện:\n\n";
        foreach ($books as $book) {
            $response .= "📘 **" . htmlspecialchars($book->book_name) . "**\n";
            $response .= "   - **Nội dung:** " . htmlspecialchars($book->notes ?? 'Thông tin liên quan') . "\n";
            if (!empty($book->relevant_chapters)) $response .= "   - **Đọc tại:** " . htmlspecialchars($book->relevant_chapters) . "\n";
            if (!empty($book->relevant_pages)) $response .= "   - **Trang cụ thể:** " . htmlspecialchars($book->relevant_pages) . "\n";
            $response .= "   - ✅ **Tình trạng:** Có sẵn (" . htmlspecialchars($book->available_copies) . " quyển)\n";
            $response .= "   - 📍 **Vị trí:** " . htmlspecialchars($book->location ?? 'Chưa cập nhật') . "\n\n";
        }
        return $response;
    }

    private function formatExternalReply($books) {
        $response = "Chào bạn, tôi đã tìm thấy một vài cuốn sách liên quan trên Internet. Bạn có thể kiểm tra thông tin và tình trạng của chúng tại thư viện:\n\n";
        foreach ($books as $book) {
            $response .= "📘 **" . htmlspecialchars($book['title']) . "**\n";
            $response .= "   - Tác giả: " . htmlspecialchars($book['author']) . "\n";
            $localBook = !empty($book['isbn']) ? $this->chatbotModel->findBookByIsbn($book['isbn']) : null;
            if ($localBook) {
                $response .= "   - ✅ **Tình trạng:** Có sẵn trong thư viện!\n";
                $response .= "   - 📍 **Vị trí:** " . htmlspecialchars($localBook->location ?? 'Chưa cập nhật') . "\n";
            } else {
                $response .= "   - ❌ **Tình trạng:** Hiện không có trong thư viện.\n";
            }
            $response .= "\n";
        }
        return $response;
    }

    private function sendReply($message) {
        header('Content-Type: application/json');
        echo json_encode(['reply' => $message]);
    }
}
