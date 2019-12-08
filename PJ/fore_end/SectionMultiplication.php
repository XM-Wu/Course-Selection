<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/7
 * Time: 17:44
 */

session_start();
if (isset($_SESSION["username"])) {
    ?>
    <html>
    <head>
        <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
        <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>课程代码</th>
                <th>课程名称</th>
                <th>年份</th>
                <th>学期</th>
                <th>已选人数</th>
                <th>查看花名册</th>
                <th>登分</th>
            </tr>
            </thead>
            <tbody>
            <?php
            include_once("../util/Connection.php");
            include_once("../util/Course.php");
            $db = connect();
            $stmt = $db->prepare("select course_id,section_id,year,semester,stu_num,max_stu from section where teacher_id=?");
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $stmt->bind_result($cid, $sid, $year, $semester, $stu_num, $max_stu);


            while($stmt->fetch()){
                echo '<tr>';
                echo '<td>' . $cid . '.' . $sid . '</td>';
                echo '<td>' . get_course_name($cid) . '</td>';
                echo '<td>' . $year . '</td>';
                echo '<td>' . $semester . '</td>';
                echo '<td>' . $stu_num . '/' . $max_stu . '</td>';

                echo '<td>';
                echo '<form method="post" action="StuList.php">';
                echo '<input hidden name="course_id" value="'. $cid .'">';
                echo '<input hidden name="section_id" value="'. $sid .'">';
                echo '<input hidden name="year" value="'. $year .'">';
                echo '<input hidden name="semester" value="'. $semester .'">';
                echo '<input type="submit" value="查看花名册">';
                echo '</form>';
                echo '</td>';

                echo '<td>';
                echo '<form method="post" action="UploadGrade.php">';
                echo '<input hidden name="course_id" value="'. $cid .'">';
                echo '<input hidden name="section_id" value="'. $sid .'">';
                echo '<input hidden name="year" value="'. $year .'">';
                echo '<input hidden name="semester" value="'. $semester .'">';
                echo '<input type="submit" value="登分">';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }


            $stmt->close();
            $db->close();
            ?>
            <!-- sample -->
            <!--            <tr>-->
            <!--                <td>CS101.1</td>-->
            <!--                <td>ICS</td>-->
            <!--                <td>2019</td>-->
            <!--                <td>第二学期</td>-->
            <!--                <td>90/100</td>-->
            <!--                <td>-->
            <!--                    <form method="post">-->
            <!--                        <input hidden name="course_id" value="CS101">-->
            <!--                        <input hidden name="section_id" value="1">-->
            <!--                        <input hidden name="year" value="2019">-->
            <!--                        <input hidden name="semester" value="第二学期">-->
            <!--                        <input type="submit" value="查看花名册">-->
            <!--                    </form>-->
            <!---->
            <!--                </td>-->
            <!--            </tr>-->
            </tbody>
        </table>
    </div>
    </body>
    </html>

    <?php
} else {
    echo '请登录';
    header("refresh:1; url=Login.html");
}