<?php
// Tệp: generate_hash.php
$password = "1"; // Mật khẩu bạn muốn băm
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo "Chuỗi băm cho mật khẩu '" . $password . "' là: " . $hashedPassword;
?>