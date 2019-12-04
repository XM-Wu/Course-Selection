<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/1
 * Time: 22:05
 */
session_start();
if(isset($_SESSION["username"])) {
    ?>
    <html>

    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <title>
        选课事务申请
    </title>
    <!-- 导航栏 -->
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link" href="SectionChoosing.php">选课</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="SectionApplication.php">选课事务申请</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="GradeChecking.php">成绩查询</a>
        </li>
    </ul>

    <h2>我的申请历史</h2>
    <div>
        <table class="table table-striped" style="word-break:break-all;">
            <thead>
            <tr>
                <th>申请课程代码</th>
                <th>申请课程名称</th>
                <th>理由</th>
                <th>回复</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <!-- sample php code TODO here -->
            <tr>
                <td>SOFT1101.1</td>
                <td>java程序设计</td>
                <td>陆逸凡傻逼，陆逸凡就你最抽象，陆逸凡胡凑ou森en，陆逸凡你啥时候写代码啊，陆逸凡你怎么天天找女朋友去啊，陆逸凡你能不能先写个pj啊，陆逸凡你代码写到哪了</td>
                <td>说得好！</td>
                <td>通过</td>
            </tr>
            </tbody>
        </table>

    </div>
    <h2>申请</h2>

    </html>
    <?php
} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}
?>
