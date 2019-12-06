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
                <th>时间</th>
                <th>成绩</th>
            </tr>
            </thead>
            <tbody>
            <?php
            include_once ("../util/Course.php");
            include_once ("../util/Connection.php");
            $mysqli = connect();
            $stmt = $mysqli->prepare("select course_id,section_id,year,semester,teacher_id from section where (course_id,section_id,year,semester) in (select course_id,section_id,year,semester from stu_take_sec where student_id=?)");
            $stmt->bind_param("s", $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($course_id, $section_id, $year, $semester, $classromm_code);
            while($stmt->fetch()){
                echo '<tr>';
                echo '<td>' . $course_id . $section_id . '</td>';
                echo '<td>' . get_course_name($course_id) . '</td>';
                echo '<td>' . $year. ' ' . $semester . '</td>';
                echo '<td>' . get_grade($_SESSION["username"], $course_id,$section_id,$year,$semester) . '</td>';
                echo '</tr>';
            }
            $stmt->close();
            $mysqli->close();
            ?>
            </tbody>
        </table>

    </div>
    </html>
    <?php
} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}