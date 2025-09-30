<?php
// Start the session
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'models/UserModel.php';
$userModel = new UserModel();


// // Kiểm tra đăng nhập và version
// if (isset($_SESSION['id'])) {
//     $currentVersion = $userModel->getVersion($_SESSION['id']);
//     if (!isset($_SESSION['version']) || $_SESSION['version'] != $currentVersion) {
//         session_destroy();
//         header('Location: login.php');
//         exit;
//     }
//     // Lấy thông tin user đang đăng nhập
//     $currentUser = $userModel->findUserById($_SESSION['id']);
//     $currentUser = $currentUser ? $currentUser[0] : null;
// } else {
//     header('Location: login.php');
//     exit;
// }

if (isset($_SESSION['id'])) {
    $currentUser = $userModel->findUserById($_SESSION['id']);
    $currentUser = $currentUser ? $currentUser[0] : null;
} else {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content']) && isset($_POST['submit_post'])) {
    $userModel->insertPost($_POST['content']);
    $success = true;
}

$posts = [];
if ($currentUser && $currentUser['name'] === 'admin') {
    $db = UserModel::getConnection();
    $result = $db->query('SELECT * FROM post ORDER BY post_id DESC');
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}


$params = [];
if (!empty($_GET['keyword'])) {
    $params['keyword'] = $_GET['keyword'];
}

$users = $userModel->getUsers($params);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>
    <div class="container">
        <?php if (!empty($users)) { ?>
            <div class="alert alert-warning" role="alert">

                <!-- <p id="hackP">Click</p>
                <script src="hack.js"></script> -->
                
                <?php echo $_SESSION['id'] ?>
                <!-- Xin chào: <b><?php echo htmlspecialchars($currentUser['name']); ?></b> (ID: <?php echo $_SESSION['id']; ?>)
                <br>  -->
                List of users! <br>
                Hacker: http://php.local/list_users.php?keyword=ASDF%25%22%3BTRUNCATE+banks%3B%23%23
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <th scope="row"><?php echo $user['id'] ?></th>
                            <td>
                                <?php echo $user['name'] ?>
                            </td>
                            <td>
                                <?php echo $user['fullname'] ?>
                            </td>
                            <td>
                                <?php echo $user['type'] ?>
                            </td>
                            <td>
                                <a href="form_user.php?id=<?php echo $user['id'] ?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true" title="Update"></i>
                                </a>
                                <a href="view_user.php?id=<?php echo $user['id'] ?>">
                                    <i class="fa fa-eye" aria-hidden="true" title="View"></i>
                                </a>
                                <!-- <a href="delete_user.php?id=<?php echo $user['id'] ?>">
                                    <i class="fa fa-eraser" aria-hidden="true" title="Delete"></i>
                                </a> -->
                                <form method="POST" action="delete_user.php?id=<?php echo $user['id']; ?>"
                                    style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;"
                                        onclick="return confirm('Bạn có chắc muốn xóa user này không?')">
                                        <a href="">
                                            <i class="fa fa-eraser" aria-hidden="true" title="Delete"></i>
                                        </a>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-dark" role="alert">
                This is a dark alert—check it out!
            </div>
        <?php } ?>

        <!-- Test -->
        <?php if ($currentUser && $currentUser['name'] === 'admin'): ?>
            <div style="margin-top:40px;">
                <h4>Danh sách bài viết</h4>
                <ul>
                    <?php foreach ($posts as $post): ?>
                        <li>
                            <b><?php echo htmlspecialchars($post['post_title']); ?></b>
                            <?php if (!empty($post['post_url'])): ?>
                                - <a href="<?php echo htmlspecialchars($post['post_url']); ?>" target="_blank">Link</a>
                            <?php endif; ?>
                            <?php if (!empty($post['post_description'])): ?>
                                <br><?php echo htmlspecialchars($post['post_description']); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="margin-top:40px;">
            <h4>POST</h4>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">Đăng bài thành công!</div>
            <?php endif; ?>
            <form method="POST">
                <textarea name="content" rows="4" cols="60" required></textarea><br>
                <button type="submit" name="submit_post">Post</button>
            </form>
        </div>


    </div>
</body>

</html>