<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/1
 * Time: 22:13
 */
session_start();
if (isset($_SESSION["username"])) {
    ?>
    <html>

    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>成绩查询</title>
    <!-- 导航栏 -->
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link" href="SectionChoosing.php">选课</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="SectionApplication.php">选课事务申请</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="GradeChecking.php">成绩查询</a>
        </li>
    </ul>

    <div>
        <table class="table">
            <thead>
            <tr>
                <th>课程代码</th>
                <th>课程名称</th>
                <th>成绩</th>
            </tr>
            </thead>
            <tbody>
            <!-- sample php code TODO here -->
            <tr>
                <td>SOFT1101.1</td>
                <td>java程序设计</td>
                <td>B+</td>
            </tr>
            </tbody>
        </table>

    </div>
    </html>
    <?php
} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}