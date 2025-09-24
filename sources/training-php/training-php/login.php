<?php
// Start the session
session_start();
require 'vendor/autoload.php';
use Predis\Client;
// $redis = new Client(['scheme' => 'tcp', 'host' => '127.0.0.1', 'port' => 6379]);
$redis = new Client(['scheme' => 'tcp', 'host' => 'web-redis', 'port' => 6379]);

// Fetch Redis keys
// $keys = $redis->keys('*');
// $keyDetails = [];

// foreach ($keys as $key) {
//     $type = $redis->type($key);
//     $value = '';

//     switch ($type) {
//         case 'string':
//             $value = $redis->get($key);
//             break;
//         case 'list':
//             $value = implode(', ', $redis->lrange($key, 0, -1));
//             break;
//         case 'set':
//             $value = implode(', ', $redis->smembers($key));
//             break;
//         case 'zset':
//             $value = json_encode($redis->zrange($key, 0, -1, ['withscores' => true]));
//             break;
//         case 'hash':
//             $value = json_encode($redis->hgetall($key));
//             break;
//     }

//     $keyDetails[] = ['key' => $key, 'type' => $type, 'value' => $value];
// }

// echo "<pre>";
// var_dump($keyDetails);
// echo "</pre>";
// die();

require_once 'models/UserModel.php';
$userModel = new UserModel();


if (!empty($_POST['submit'])) {

    $users = [
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ];
    $user = NULL;
    if ($user = $userModel->auth($users['username'], $users['password'])) {
        //Login successful
        // $_SESSION['id'] = $user[0]['id'];
        // $_SESSION['message'] = 'Login successful';

        $token = bin2hex(random_bytes(32));

        $_SESSION['csrf_token'] = $token;

        $redis->set("id", $user[0]['id']);
        $redis->set("message", 'Login successful');
        $redis->set("token", $token);

        echo "<script>
            localStorage.setItem('token', '$token');
            window.location.href = 'list_users.php';
        </script>";

        header('location: list_users.php');
    } else {
        //Login failed
        $_SESSION['message'] = 'Login failed';
        //$redis->set("message", 'Login failed');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>

    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                    <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                </div>

                <div style="padding-top:30px" class="panel-body">
                    <form method="post" class="form-horizontal" role="form">
                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>

                        <div class="margin-bottom-25">
                            <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                            <label for="remember"> Remember Me</label>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                                <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 control">
                                Don't have an account!
                                <a href="form_user.php">
                                    Sign Up Here
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>