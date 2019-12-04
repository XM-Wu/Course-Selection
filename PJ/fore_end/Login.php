<?php
include("../util/AccountHandle.php");
include("../util/Connection.php");
header('Content-type:text/html; charset=utf-8'); //网页编码

session_start();

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (($username == '') || ($password == '')) {
        header('refresh:3; url=Login.html');
        echo "用户名或密码不能为空!";
        exit;
    } else {
        $mysqli = connect();
        if (correct_account($mysqli, $username, $password)) {
            $_SESSION['username'] = $username;
            $_SESSION['type'] = getAccountType($mysqli, $username);
            // 设置Cookie并设置保留7天
            if ($_POST['remember'] == "true") {
                setcookie('username', $username, time() + 7 * 24 * 60 * 60);
                setcookie('code', md5($username . md5($password)), time() + 7 * 24 * 60 * 60);
            } else {
                setcookie('username', '', time() - 999);
                setcookie('code', '', time() - 999);
            }
            header('location:Home.php');
            $mysqli->close();
        } else {
            header('refresh:3; url=Login.html');
            echo "用户名或密码错误!";
            $mysqli->close();
            exit;
        }
    }
}