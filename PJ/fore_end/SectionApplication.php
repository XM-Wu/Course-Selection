<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/1
 * Time: 22:05
 */
include_once("Constants.php");
include_once("../util/Connection.php");


session_start();
if (isset($_SESSION["username"])) {
    ?>
    <html>

    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <?php

    if ($_SESSION['type'] == 'student') {
        ?>


        <title>
            选课事务申请
        </title>
        <body>
        <div class="container">
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

                    <?php
                    $db = connect();
                    $stmt = $db->prepare("select A.course_id, A.section_id, B.course_name,A.apply_reason,A.state,A.handle_reason from application_transaction A ,course B where student_id=? and A.course_id=B.course_id");
                    $stmt->bind_param("s", $_SESSION['username']);
                    $stmt->execute();
                    $stmt->bind_result($cid, $sid, $c_name, $ar, $state, $hr);
                    while ($stmt->fetch()) {
                        echo '<tr>';
                        echo '<td>' . $cid . '.' . $sid . '</td>';
                        echo '<td>' . $c_name . '</td>';
                        echo '<td>' . $ar . '</td>';
                        echo '<td>' . $hr . '</td>';
                        echo '<td>' . $state . '</td>';
                        echo '</tr>';
                    }
                    $stmt->close();
                    $db->close();
                    ?>
                    <!-- sample -->
                    <!--                    <tr>-->
                    <!--                        <td>SOFT1101.1</td>-->
                    <!--                        <td>java程序设计</td>-->
                    <!--                        <td width="400px">-->
                    <!--                            陆逸凡傻逼，陆逸凡就你最抽象，陆逸凡胡凑ou森en，陆逸凡你啥时候写代码啊，陆逸凡你怎么天天找女朋友去啊，陆逸凡你能不能先写个pj啊，陆逸凡你代码写到哪了-->
                    <!--                        </td>-->
                    <!--                        <td>说得好！</td>-->
                    <!--                        <td>通过</td>-->
                    <!--                    </tr  width="200px">-->
                    </tbody>
                </table>

            </div>
            <h2>申请</h2>
            <form class="col-3" action="SubmitApplication.php" method="post" onsubmit="return upload_file();">
                <div class="form-group">
                    <label for="cid">课程代码:</label>
                    <input type="text" class="form-control" name="cid" id="cid">
                </div>
                <div class="form-group">
                    <label for="yy">年份:</label>
                    <input type="text" class="form-control" name="year" value="<?php echo $current_year; ?>" id="yy">
                </div>
                <div class="form-group">
                    <label for="se">学期:</label>
                    <select class="form-control" id="se" name="semester">
                        <option value="寒假学期">寒假学期</option>
                        <option selected value="<?php echo $current_semester; ?>">第一学期</option>
                        <option value="暑假学期">暑假学期</option>
                        <option value="第二学期">第二学期</option>
                    </select>
                </div>

                <div>
                    <label for="reason">申请理由:</label>
                    <textarea class="form-control" rows="3" id="reason" name="reason"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>

        </div>
        </body>
        <script>
            function upload_file() {
                let input1 = document.getElementById("cid");
                let input2 = document.getElementById("yy");
                let input3 = document.getElementById("reason");

                if (input1.value == "" || input2.value == "" || input3.value == "") {
                    alert("请填写完整信息");
                    return false;
                }
                return true;
            }
        </script>

    <?php
    } elseif ($_SESSION['type'] == 'teacher') {
    ?>
        <title>选课事务申请处理</title>
        <body>
        <h2>待处理</h2>
        <div>
            <table class="table table-striped" style="word-break:break-all;">
                <thead>
                <tr>
                    <th>申请课程代码</th>
                    <th>申请课程名称</th>
                    <th>年份</th>
                    <th>学期</th>
                    <th>申请人</th>
                    <th>理由</th>
                    <th>回复</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <!-- sample -->
                <?php
                $db = connect();
                $toget = '已提交';
                $stmt = $db->prepare("select B.apply_id,B.student_id,B.course_id, B.section_id,B.year,B.semester,B.apply_reason,C.course_name from section A, application_transaction B,course C where A.teacher_id=? and A.course_id=B.course_id and A.section_id=B.section_id and A.year=B.year and A.semester=B.semester and B.state=? and A.course_id=C.course_id");
                $stmt->bind_param('ss', $_SESSION['username'], $toget);
                $stmt->execute();
                $stmt->bind_result($aid, $student_id, $cid, $sid, $year, $semester, $ar, $cname);
                while ($stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $cid . '.' . $sid . '</td>';
                    echo '<td>' . $cname . '</td>';
                    echo '<td>' . $year . '</td>';
                    echo '<td>' . $semester . '</td>';
                    echo '<td>' . $student_id . '</td>';
                    echo '<td>' . $ar . '</td>';
                    echo '<form action="HandleApplication.php" method="post">';
                    echo '<td>';
                    echo '<div class="radio"><label><input type="radio" name="optradio" value="1">通过</label></div>';
                    echo '<div class="radio"><label><input type="radio" name="optradio" value="0">不通过</label></div>';
                    echo '<textarea class="form-control" rows="2" id="comment" name="reason"></textarea>';
                    echo '<td>';
                    echo '<td>';
                    echo '<button type="submit">提交处理</button>';
                    echo '</td>';
                    echo '<input hidden name="apply_id" value="'. $aid .'">';
                    echo '<input hidden name="course_id" value="'. $cid .'">';
                    echo '<input hidden name="section_id" value="'. $sid .'">';
                    echo '<input hidden name="year" value="'. $year .'">';
                    echo '<input hidden name="semester" value="'. $semester .'">';
                    echo '<input hidden name="student_id" value="'. $student_id .'">';
                    echo '</form>';
                    echo '</tr>';
                }
                ?>
<!--                <tr>-->
<!--                    <td>CS001.1</td>-->
<!--                    <td>ICD</td>-->
<!--                    <td>2019</td>-->
<!--                    <td>第二学期</td>-->
<!--                    <td>i love it!</td>-->
<!--                    <form action="HandleApplication.php" method="post">-->
<!--                        <td>-->
<!--                            <div class="radio">-->
<!--                                <label><input type="radio" name="optradio" value="1">通过</label>-->
<!--                            </div>-->
<!--                            <div class="radio">-->
<!--                                <label><input type="radio" name="optradio" value="0">不通过</label>-->
<!--                            </div>-->
<!--                            <textarea class="form-control" rows="2" id="comment" name="reason"></textarea>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <input hidden name="">-->
<!--                            <button type="submit">提交处理</button>-->
<!--                        </td>-->
<!--                    </form>-->
<!--                </tr>-->
                </tbody>
            </table>
        </div>
        </body>
        <?php
    }
    ?>
    </html>
    <?php
} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}
?>
