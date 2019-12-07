<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/7
 * Time: 19:38
 */
session_start();
include_once("../util/Connection.php");
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
                <th>学号</th>
                <th>名称</th>
                <th>专业</th>
                <th>年级</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $db = connect();
            $stmt = $db->prepare("select student_id,name,major,enrollment from student where student_id in (select student_id from stu_take_sec where course_id=? and section_id=? and year=? and semester=?)");
            $stmt->bind_param("sdds",$_POST["course_id"],$_POST["section_id"],$_POST["year"],$_POST["semester"]);
            $stmt->execute();
            $stmt->bind_result($student_id, $name, $major, $enrollment);

            echo '<tr>';
            while($stmt->fetch()){
                echo '<tr>';
                echo '<td>' . $student_id . '</td>';
                echo '<td>' . $name . '</td>';
                echo '<td>' . $major . '</td>';
                echo '<td>' . $enrollment . '</td>';
                echo '</tr>';
            }
            echo '</tr>';

            $stmt->close();
            $db->close();
            ?>

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