-- =================================================================
-- KỊCH BẢN SQL HOÀN CHỈNH ĐỂ THIẾT LẬP CSDL LIBSMART
-- Phiên bản đã được dọn dẹp, tối ưu và hợp nhất.
-- =================================================================

-- Tạo cơ sở dữ liệu nếu chưa tồn tại và chọn nó
CREATE DATABASE IF NOT EXISTS `libsmart_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `libsmart_db`;

-- Tắt kiểm tra khóa ngoại để tạo bảng không bị lỗi thứ tự
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Bảng: accounts (Tài khoản người dùng)
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=chưa xác thực, 1=đã xác thực',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verification_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: main_categories (Danh mục chính)
-- ----------------------------
DROP TABLE IF EXISTS `main_categories`;
CREATE TABLE `main_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: faculties (Khoa)
-- ----------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `main_category_id` (`main_category_id`),
  CONSTRAINT `faculties_ibfk_1` FOREIGN KEY (`main_category_id`) REFERENCES `main_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: subjects (Chủ đề / Môn học)
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `faculty_id` int(11) DEFAULT NULL,
  `main_category_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parent_subject` (`parent_id`),
  KEY `faculty_id` (`faculty_id`),
  KEY `main_category_id` (`main_category_id`),
  CONSTRAINT `fk_parent_subject` FOREIGN KEY (`parent_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`main_category_id`) REFERENCES `main_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: books (Sách)
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `ISBN` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vị trí vật lý của sách trong thư viện',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_books_subjects` (`subject_id`),
  CONSTRAINT `fk_books_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: loans (Lịch sử mượn/trả)
-- ----------------------------
DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `fine_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: reservations (Đặt trước sách)
-- ----------------------------
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: concepts (Khái niệm học thuật)
-- ----------------------------
DROP TABLE IF EXISTS `concepts`;
CREATE TABLE `concepts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `concepts_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Bảng: concept_book_references (Liên kết Khái niệm - Sách)
-- ----------------------------
DROP TABLE IF EXISTS `concept_book_references`;
CREATE TABLE `concept_book_references` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concept_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `relevant_chapters` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relevant_pages` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `concept_id` (`concept_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `concept_book_references_ibfk_1` FOREIGN KEY (`concept_id`) REFERENCES `concepts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `concept_book_references_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =================================================================
-- THÊM DỮ LIỆU MẪU
-- =================================================================

-- 1. Thêm tài khoản admin
INSERT INTO `accounts` (`id`, `username`, `fullname`, `password`, `email`, `role`, `is_verified`) VALUES
(1, 'admin', 'Administrator', '$2y$10$FIn9cObJMCTkKikqVRT1JOflnpfoidy0pGRhBp9VaNT0bNkV2ulPG', 'admin@libsmart.com', 'admin', 1);

-- 2. Thêm danh mục chính
INSERT INTO `main_categories` (`id`, `name`, `description`) VALUES
(1, 'Học tập & Nghiên cứu', 'Tài liệu dành cho các chuyên ngành học thuật, giáo trình, sách tham khảo.'),
(2, 'Văn học & Giải trí', 'Các loại tiểu thuyết, truyện ngắn, thơ, sách giải trí.'),
(3, 'Chính trị & Xã hội', 'Sách về lịch sử, chính trị, pháp luật và các vấn đề xã hội.'),
(4, 'Phát triển bản thân', 'Sách về kỹ năng mềm, kinh doanh, và phát triển cá nhân.');

-- 3. Thêm Khoa
INSERT INTO `faculties` (`id`, `name`, `main_category_id`) VALUES
(1, 'Công nghệ thông tin', 1),
(2, 'Quản trị Kinh doanh', 1),
(3, 'Ngoại ngữ', 1),
(4, 'Du lịch - Khách sạn', 1),
(5, 'Mỹ thuật công nghiệp', 1);

-- 4. Thêm Môn học / Chủ đề
INSERT INTO `subjects` (`id`, `name`, `description`, `faculty_id`, `main_category_id`) VALUES
(1, 'Lập trình Hướng đối tượng', 'Các khái niệm cơ bản và nâng cao về OOP.', 1, NULL),
(2, 'Cấu trúc dữ liệu & Giải thuật', 'Các cấu trúc dữ liệu phổ biến và thuật toán liên quan.', 1, NULL),
(3, 'Marketing căn bản', 'Giới thiệu về các nguyên lý và hoạt động marketing.', 2, NULL),
(4, 'Tiếng Anh chuyên ngành CNTT', 'Thuật ngữ và kỹ năng tiếng Anh cho ngành CNTT.', 3, NULL),
(5, 'Hệ quản trị Cơ sở dữ liệu', 'Kiến thức về thiết kế, quản lý và truy vấn CSDL quan hệ.', 1, NULL),
(6, 'Quản trị Nguồn nhân lực', 'Các quy trình quản lý con người trong một tổ chức.', 2, NULL),
(7, 'Tiểu thuyết Việt Nam', 'Các tác phẩm tiểu thuyết của tác giả Việt Nam.', NULL, 2),
(8, 'Kỹ năng giao tiếp', 'Sách giúp cải thiện kỹ năng giao tiếp và ứng xử.', NULL, 4),
(9, 'Lịch sử Thế giới', 'Tổng quan về các sự kiện lịch sử quan trọng trên thế giới.', NULL, 3);

-- 5. Thêm Sách
INSERT INTO `books` (`id`, `name`, `description`, `author`, `publisher`, `publication_year`, `ISBN`, `subject_id`, `image`, `number_of_copies`, `available_copies`, `location`) VALUES
(1, 'Lập trình Hướng đối tượng với Java', 'Giáo trình đầy đủ về OOP sử dụng ngôn ngữ Java, từ cơ bản đến nâng cao.', 'GS. TS. Nguyễn Thanh Thủy', 'NXB Đại học Quốc gia', 2022, '978-604-32-0123-4', 1, 'uploads/giaotrinh.jpg', 10, 10, 'Kệ A1, Tầng 2'),
(2, 'Cấu trúc dữ liệu và giải thuật - C++', 'Cung cấp kiến thức về các cấu trúc dữ liệu như danh sách liên kết, cây, đồ thị và các giải thuật sắp xếp, tìm kiếm.', 'Lê Minh Hoàng', 'NXB Khoa học và Kỹ thuật', 2021, '978-604-95-3456-7', 2, 'uploads/toithayhoavang.jpg', 15, 15, 'Kệ A2, Tầng 2'),
(3, 'Nguyên lý Marketing', 'Sách gối đầu giường cho các marketer, bao gồm các khái niệm từ A-Z về Marketing.', 'Philip Kotler', 'NXB Lao động', 2020, '978-604-33-7890-1', 3, 'uploads/doraemon.png', 20, 20, 'Kệ B1, Tầng 3'),
(4, 'Head First Design Patterns', 'Một cuốn sách kinh điển về các mẫu thiết kế trong lập trình hướng đối tượng.', 'Eric Freeman & Elisabeth Robson', 'OReilly', 2018, '978-059-60-0712-6', 1, 'uploads/iphone15.jpg', 5, 5, 'Kệ A1, Tầng 2'),
(5, 'Hệ quản trị CSDL SQL Server', 'Từ thiết kế, triển khai đến quản trị CSDL với Microsoft SQL Server.', 'Trần Công Án', 'NXB Thống kê', 2023, '978-604-35-1111-1', 5, 'uploads/default-book.jpg', 12, 12, 'Kệ A3, Tầng 2'),
(6, 'Quản trị nhân sự hiện đại', 'Cẩm nang về tuyển dụng, đào tạo, đánh giá và giữ chân nhân tài.', 'Nguyễn Hữu Thân', 'NXB Tổng hợp TP.HCM', 2022, '978-604-34-5555-5', 6, 'uploads/default-book.jpg', 18, 18, 'Kệ B2, Tầng 3'),
(7, 'Clean Code', 'Một cuốn sách phải đọc cho bất kỳ lập trình viên nghiêm túc nào về cách viết mã sạch, dễ đọc và dễ bảo trì.', 'Robert C. Martin', 'Prentice Hall', 2008, '978-013-23-5088-4', 1, 'uploads/default-book.jpg', 8, 8, 'Kệ A1, Tầng 2');

-- 6. Thêm Khái niệm
INSERT INTO `concepts` (`id`, `subject_id`, `name`, `description`) VALUES
(1, 1, 'Tính Đa hình (Polymorphism)', 'Khả năng một đối tượng có thể thể hiện dưới nhiều hình thức khác nhau.'),
(2, 1, 'Tính Kế thừa (Inheritance)', 'Cho phép một lớp (class con) kế thừa các thuộc tính và phương thức từ một lớp khác (class cha).'),
(3, 2, 'Danh sách liên kết đơn', 'Một cấu trúc dữ liệu tuyến tính trong đó các phần tử không được lưu trữ tại các vị trí bộ nhớ liền kề.'),
(4, 5, 'Chuẩn hóa Dữ liệu (Normalization)', 'Quy trình tổ chức các cột và bảng trong CSDL quan hệ để giảm thiểu sự dư thừa dữ liệu.'),
(5, 6, 'Quy trình Tuyển dụng', 'Các bước để tìm kiếm, sàng lọc, phỏng vấn và lựa chọn ứng viên phù hợp.'),
(6, 1, 'Tính Đóng gói (Encapsulation)', 'Kỹ thuật che giấu dữ liệu và các phương thức xử lý bên trong một đối tượng.');

-- 7. Thêm Liên kết Khái niệm - Sách
INSERT INTO `concept_book_references` (`concept_id`, `book_id`, `relevant_chapters`, `relevant_pages`, `notes`) VALUES
(1, 1, 'Chương 5', 'tr. 120-145', 'Phần này giải thích rất chi tiết về đa hình động và đa hình tĩnh trong Java.'),
(1, 4, 'Chapter 1, 3', 'p. 25-30', 'Giới thiệu về Strategy Pattern, một ví dụ điển hình của tính đa hình.'),
(2, 1, 'Chương 4', 'tr. 90-119', 'Bao gồm các ví dụ về kế thừa đơn, đa tầng và cách sử dụng từ khóa `super`.'),
(3, 2, 'Chương 3', 'tr. 55-80', 'Mô tả đầy đủ về cách triển khai, thêm, xóa, duyệt một danh sách liên kết đơn bằng C++.'),
(4, 5, 'Chương 6, 7, 8', 'tr. 150-210', 'Trình bày chi tiết về các dạng chuẩn 1NF, 2NF, 3NF và BCNF kèm ví dụ thực tế.'),
(5, 6, 'Chương 3', 'tr. 45-70', 'Mô tả quy trình tuyển dụng từ A-Z, từ việc đăng tin đến phỏng vấn và onboarding.'),
(6, 1, 'Chương 3', 'tr. 65-89', 'Giải thích về access modifiers (public, private, protected) và cách áp dụng để che giấu dữ liệu.'),
(6, 7, 'Chapter 6', NULL, 'Bàn về Objects and Data Structures, nhấn mạnh tầm quan trọng của việc che giấu thông tin triển khai.');

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS=1;
