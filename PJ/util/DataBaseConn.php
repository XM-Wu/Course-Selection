<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/1
 * Time: 21:54
 */
function connect() {
    $dbhost = 'localhost';  // mysql服务器主机地址
    $dbuser = 'root';            // mysql用户名
    $dbpass = '';          // mysql用户名密码
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
    if(! $conn )
    {
        die('Could not connect: ' . mysqli_error());
    }
    return $conn;
}