<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id = NULL;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token không hợp lệ!');
    }

    // 2. Lấy ID và xóa
    $id = intval($_GET['id']);
    $userModel->deleteUserById($id);

    header('Location: list_users.php');
    exit;
}

// if (!empty($_GET['id'])) {
//     $id = $_GET['id'];
//     $userModel->deleteUserById($id);//Delete existing user
// }
header('location: list_users.php');
?>