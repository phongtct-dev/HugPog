<?php
// File: generate_all_hashes.php

$admin_pass = "admin123";
$user_pass = "user123";
$employee_pass = "nhanvien123";

echo "<h3>Hash cho Admin (mật khẩu: admin123):</h3>";
echo "<textarea rows='2' cols='70' readonly>" . password_hash($admin_pass, PASSWORD_DEFAULT) . "</textarea>";

echo "<hr>";

echo "<h3>Hash cho tất cả User (mật khẩu: user123):</h3>";
echo "<textarea rows='2' cols='70' readonly>" . password_hash($user_pass, PASSWORD_DEFAULT) . "</textarea>";

echo "<hr>";

echo "<h3>Hash cho tất cả Nhân viên (mật khẩu: nhanvien123):</h3>";
echo "<textarea rows='2' cols='70' readonly>" . password_hash($employee_pass, PASSWORD_DEFAULT) . "</textarea>";
?>