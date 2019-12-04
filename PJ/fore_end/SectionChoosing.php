<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/11/16
 * Time: 11:02
 */
include("../util/Connection.php");
$conn = connect();
mysqli_close($conn);

?>
<html>

<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>


<title>
    选课
</title>

<body clss="container">
<!-- 导航栏 -->
<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link active" href="SectionChoosing.php">选课</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="SectionApplication.php">选课事务申请</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="GradeChecking.php">成绩查询</a>
    </li>
</ul>

<!--
    <div >
        <table class="table">
            <thead>
            <tr>
                <th>节次\周次</th>
                <th>周一</th>
                <th>周二</th>
                <th>周三</th>
                <th>周四</th>
                <th>周五</th>
                <th>周六</th>
                <th>周日</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>一</td>
            </tr>
            <tr>
                <td>二</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>三</td>
                <td>July</td>
                <td>Dooley</td>
                <td>july@example.com</td>
            </tr>
            <tr>
                <td>四</td>
                <td>July</td>
                <td>Dooley</td>
                <td>july@example.com</td>
            </tr>
            <tr>
                <td>五</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>六</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>七</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>八</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>九</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>十</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>十一</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            <tr>
                <td>十二</td>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
            </tr>
            </tbody>
        </table>
    </div>
-->
    <h2>已选课程</h2>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th>课程代码</th>
                <th>课程名称</th>
                <th>教师</th>
                <th>时间</th>
                <th>地点</th>
                <th>已选人数</th>
            </tr>
            </thead>
            <tbody>
            <!-- sample php code TODO here -->
            <tr>
                <td>SOFT1101.1</td>
                <td>java程序设计</td>
                <td>陆逸凡</td>
                <td>周日 2-3</td>
                <td>Z2204</td>
                <td>90/100</td>
            </tr>
            </tbody>
        </table>

    </div>

    <h2>课程搜素</h2>
    <form class="form-inline">
        <label for="course_code">课程代码</label>
        <input type="text" class="form-control" id="course_code" placeholder="输入课程代码">
        <label for="course_name">课程名称</label>
        <input type="text" class="form-control" id="course_name" placeholder="输入课程名称">
        <button type="submit" class="btn btn-primary">搜索</button>
    </form>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th>课程代码</th>
                <th>课程名称</th>
                <th>教师</th>
                <th>时间</th>
                <th>地点</th>
                <th>已选人数</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <!-- sample php code TODO here -->
            <tr>
                <td>SOFT1101.1</td>
                <td>java程序设计</td>
                <td>陆逸凡</td>
                <td>周日 2-3</td>
                <td>Z2204</td>
                <td>90/100</td>
                <td>
                    <button>选课</button>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</body>

</html>
