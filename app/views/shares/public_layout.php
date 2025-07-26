<?php
// Nhúng header chung của trang
include_once __DIR__ . '/header.php';

// Hiển thị nội dung chính được truyền từ controller
echo $main_content ?? '';

// Nhúng footer chung của trang
include_once __DIR__ . '/footer.php';
?>

<!-- =============================================== -->
<!-- BẮT ĐẦU PHẦN TÍCH HỢP CHATBOT AI -->
<!-- =============================================== -->

<!-- 1. Cấu trúc HTML của Chatbot -->
<button id="chatbox-toggle" class="btn btn-primary rounded-circle shadow-lg" title="Trợ lý AI">
    <i class="fas fa-robot"></i>
</button>

<div id="chatbox-window" class="card shadow-lg rounded-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-robot me-2"></i> Trợ lý Thư viện AI</h6>
        <button id="chatbox-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
    </div>
    <div id="chatbox-body" class="card-body">
        <div class="chat-message bot-message">
            <div class="msg-bubble">
                Chào bạn! Tôi là trợ lý AI của thư viện LIBSMART. Bạn cần hỗ trợ tìm sách về chủ đề gì? Ví dụ: "Tìm sách về đa hình trong OOP".
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        <form id="chatbox-form" autocomplete="off">
            <div class="input-group">
                <input type="text" id="chatbox-input" class="form-control" placeholder="Nhập câu hỏi của bạn..." required>
                <button type="submit" class="btn btn-primary" id="chatbox-submit"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>
</div>


<!-- 2. CSS để tạo kiểu cho Chatbot (ĐÃ CẬP NHẬT) -->
<style>
    #chatbox-toggle {
        position: fixed; bottom: 25px; right: 25px;
        width: 60px; height: 60px; z-index: 1050;
        font-size: 1.5rem; transition: all 0.3s ease;
    }
    #chatbox-toggle:hover { transform: scale(1.1); }

    #chatbox-window {
        position: fixed; bottom: 100px; right: 25px;
        width: 370px; height: 550px; z-index: 1050;
        display: none; flex-direction: column; border: none;
    }

    #chatbox-body {
        overflow-y: auto; flex-grow: 1; padding: 1rem;
        background-color: #f8f9fa;
    }

    .chat-message { margin-bottom: 1rem; display: flex; }
    .user-message { justify-content: flex-end; }
    .bot-message { justify-content: flex-start; }

    /* --- CẢI TIẾN PHÔNG CHỮ VÀ ĐỊNH DẠNG --- */
    .msg-bubble {
        max-width: 85%;
        padding: 10px 15px;
        border-radius: 1.1rem;
        line-height: 1.6; /* Tăng khoảng cách dòng */
        font-size: 0.95rem; /* Tăng kích thước chữ */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Font chữ dễ đọc */
    }
    .msg-bubble strong, .msg-bubble b {
        font-weight: 600; /* Làm chữ in đậm rõ hơn */
    }
    /* --- KẾT THÚC CẢI TIẾN --- */

    .user-message .msg-bubble {
        background-color: #0d6efd; color: white;
        border-bottom-right-radius: 0;
    }
    .bot-message .msg-bubble {
        background-color: #e9ecef; color: #212529;
        border-bottom-left-radius: 0;
    }
    .typing-indicator { display: flex; align-items: center; }
    .typing-indicator span {
        height: 8px; width: 8px; background-color: #6c757d;
        border-radius: 50%; display: inline-block; margin: 0 2px;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1.0); }
    }
</style>


<!-- 3. JavaScript để Chatbot hoạt động (ĐÃ CẬP NHẬT) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbox-toggle');
    const closeBtn = document.getElementById('chatbox-close');
    const chatWindow = document.getElementById('chatbox-window');
    const chatBody = document.getElementById('chatbox-body');
    const chatForm = document.getElementById('chatbox-form');
    const chatInput = document.getElementById('chatbox-input');
    const submitBtn = document.getElementById('chatbox-submit');

    toggleBtn.addEventListener('click', () => {
        chatWindow.style.display = (chatWindow.style.display === 'flex') ? 'none' : 'flex';
    });
    closeBtn.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const userMessage = chatInput.value.trim();
        if (!userMessage) return;

        appendMessage(userMessage, 'user');
        chatInput.value = '';
        chatInput.focus();
        showTypingIndicator();

        fetch('/chatbot/getRecommendation', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: userMessage })
        })
        .then(response => response.json())
        .then(data => {
            removeTypingIndicator();
            appendMessage(data.reply, 'bot'); // Truyền thẳng message gốc
        })
        .catch(error => {
            console.error('Lỗi khi gọi API Chatbot:', error);
            removeTypingIndicator();
            appendMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
        });
    });

    /**
     * CẢI TIẾN: Hàm này sẽ định dạng lại văn bản trước khi hiển thị
     * @param {string} text - Nội dung tin nhắn
     * @returns {string} - Chuỗi HTML đã được định dạng
     */
    function formatChatMessage(text) {
        // Chuyển đổi các ký tự HTML đặc biệt để tránh XSS
        let safeText = text.replace(/</g, "&lt;").replace(/>/g, "&gt;");

        // 1. Chuyển đổi **bold** thành <strong>bold</strong>
        let formattedText = safeText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // 2. Chuyển đổi \n thành <br>
        formattedText = formattedText.replace(/\\n/g, '<br>');
        
        return formattedText;
    }

    function appendMessage(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}-message`;
        // Sử dụng hàm formatChatMessage để xử lý nội dung
        messageDiv.innerHTML = `<div class="msg-bubble">${formatChatMessage(message)}</div>`;
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot-message';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = `
            <div class="msg-bubble">
                <div class="typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>
        `;
        chatBody.appendChild(typingDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
        submitBtn.disabled = true;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.remove();
        }
        submitBtn.disabled = false;
    }
});
</script>

<!-- =============================================== -->
<!-- KẾT THÚC PHẦN TÍCH HỢP CHATBOT AI -->
<!-- =============================================== -->
