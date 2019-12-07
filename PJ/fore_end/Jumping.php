<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/7
 * Time: 17:06
 */

include_once ("Constants.php");
session_start();
if (isset($_GET["course_id"])) {
    include_once ("../util/Course.php");
    unset($_SESSION['map']);

    quit_course($_SESSION['username'], $_GET["course_id"], $_GET["section_id"], $current_year, $current_semester);
    echo "<script> alert('" . $_GET["course_id"] . " 退课成功'); </script>";
    echo '<script> window.location.href="SectionChoosing.php"; </script>';
}