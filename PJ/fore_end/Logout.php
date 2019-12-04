<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/4
 * Time: 17:42
 */

session_start();
unset($_SESSION['username']);
session_destroy();
header('location:Home.php');