<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/10
 * Time: 22:43
 */

session_start();
if (isset($_SESSION['username']) && $_SESSION['type'] == 'admin') {

    ?>

    <html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>
        数据管理
    </title>

    <body>

    <div class="container mt-3">

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>数据表</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><a href="data_pages/StudentData.php">学生数据</a></td>
            </tr>
            <tr>
                <td><a href="data_pages/TeacherData.php">教师数据</a></td>
            </tr>
            <tr>
                <td><a href="data_pages/DepartmentData.php">学院数据</a></td>
            </tr>
            <tr>
                <td><a href="data_pages/CourseData.php">课程大纲</a></td>
            </tr>
            <tr>
                <td><a href="data_pages/MajorData.php">专业数据</a></td>
            </tr>
            <tr>
                <td><a href="data_pages/ClassroomData.php">教室数据</a></td>
            </tr>
            </tbody>
        </table>

    </div>
    </div>
    </body>
    </html>

    <?php

} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}