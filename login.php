<?php
session_start();

$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$nama_db = "login";
$koneksi = mysqli_connect($host_db,$user_db,$pass_db,$nama_db);

$err = "";
$username = "";
$remember = "";

if(isset($_COOKIE['cookie_username'])){
    $coookie_username = $_COOKIE['cookie_username'];
    $coookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from login where username = '$coookie_username'";
    $q1 = mysqli_query($koneksi,$sql1);
    $r1 = mysqli_fetch_array($q1);
    if($r1['password'] == $coookie_password){
        $_SESSION['session_username'] = $coookie_username;
        $_SESSION['session_password'] = $coookie_password;
    }
}

if(isset($_SESSION['session_username'])){
    header("location:anggota.php");
    exit();
}

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];

    if($username == '' or $password == ''){
        $err .= "<li>Silakan masukkan username dan password</li>";
    }else{
        $sql1 = "select * from login where username = '$username'";
        $q1 = mysqli_query($koneksi,$sql1);
        $r1 = mysqli_fetch_array($q1);

        if($r1['username'] == ''){
            $err .= "<li>username <b>$username</b>tidak tersedia</li>";
        }elseif($r1['password'] != md5($password)){
            $err .= "<li>password yang dimasukkan salah</li>";
        }

        if(empty($err)){
            $_SESSION['session_username'] = $username;
            $_SESSION['session_password'] = md5($password);
            
            if($remember == 1){
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60*60*24*30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60*60*24*30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");
            }
            header("location:anggota.php");
        }
    }    
}
?>
<!DOCTYPE html>
<html lange="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" >
</head>
<body>
<div class="container">
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div classs="panel-title">Login Sistem</div>
            </div>
            <div style="padding-top:30px" class="panel-body">
                <?php if($err){ ?>
                    <div id="login-alert" class="alert alert-danger col-sm-12">
                        <ul><?php echo $err ?></ul>
                        </div>
                    <?php } ?>
                    <form id="loginforce" class="form-horizontal" action="" method="post"
                    role="form">
                    <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="username" class="force-control"
                            name="username" placeholder="username">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="force-control"
                            name="password" placeholder="password">
                        </div>
                        <div class="inputgroup">
                            <div class="checkbox">
                            <label>
                                <input id="login-remember" type="checkbox" name="remember" value="1">
                                <?php if($remember =='1') echo "checked"?>Remember
                            </label>
                            </div>
                        </div>
                        <div style="margin-top: 10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <input type="submit" name="login" class="btn btn-success"
                                value="login"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

