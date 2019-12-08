<html>
<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
    a.nl {
        text-decoration: none
    }

    a.nl:link {
        color: gray;
    }

    a.nl:visited {
        color: gray;
    }
</style>

<title>首页</title>
<body>
<?php
session_start();
if (isset($_SESSION['username'])) {
    ?>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="#">
            <img src="../resource/pic/user.jpg" alt="logo" style="width:40px;">
        </a>

        <!-- Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <!--                <form action="Logout.php" method="post">-->
                <!--                    <input type="submit" value="登出">-->
                <!--                </form>-->
                <a href="Logout.php" class="nl">登出</a>
            </li>
        </ul>
    </nav>

    <?php
    if ($_SESSION["type"] == "student") {
        ?>
        <h1><a href="SectionChoosing.php">选课与查看课程表</a></h1>
        <h1><a href="SectionApplication.php">选课事务申请</a></h1>
        <h1><a href="GradeChecking.php">查看成绩</a></h1>
        <?php
    } elseif ($_SESSION["type"] == "admin") {
        ?>
        <h1><a href="#">查询与修改</a></h1>
        <h1><a href="InputData.php">数据导入</a></h1>
        <?php

    } elseif ($_SESSION["type"] == "teacher") {
        ?>
        <h1><a href="SectionMultiplication.php">课程操作</a></h1>
        <h1><a href="SectionApplication.php">选课事务申请处理</a></h1>
        <?php
    }
} else {
    echo isset($_SESSION['username']);
    ?>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="Login.html">
                    登录
                </a>
            </li>
        </ul>
    </nav>
    <?php
}
?>

</body>
</html>
