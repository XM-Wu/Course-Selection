<?php

session_start();
if (isset($_SESSION['username']) && $_SESSION['type'] == 'admin') {
    if (isset($_POST['cid']) || isset($_GET['cid'])) {
        if (isset($_POST['cid']))
            $course_id = $_POST['cid'];
        else
            $course_id = $_GET['cid'];
        include_once("../../util/Connection.php");
        include_once("../Constants.php");
        $db = connect();

        // 处理删除
        if (isset($_POST['d_section_id'])) {
            $db->autocommit(false);
            $need_roll_back = false;

            // 删除课程
            $stmt = $db->prepare('delete from section where course_id=? and section_id=? and year=? and semester=?');
            $stmt->bind_param('siis', $course_id, $_POST['d_section_id'], $_POST['d_year'], $_POST['d_semester']);
            $stmt->execute();
            if (mysqli_stmt_error($stmt)) {
                $need_roll_back = true;
                echo '<script> alert("课程删除失败，存在关联数据"); </script>';
            }
            $stmt->close();

            // 删除考核方式
            // 其他信息联级删除
            $stmt = $db->prepare('delete from assessment where assessment_id=?');
            $stmt->bind_param('s', $_POST['d_assessment_id']);
            $stmt->execute();
            if (mysqli_stmt_error($stmt)) {
                $need_roll_back = true;
                echo '<script> alert("考核方式删除失败，存在关联数据"); </script>';
            }
            $stmt->close();

            if($need_roll_back) $db->rollback();
            else $db->commit();

            $db->autocommit(true);



        } /**************************************** 处理添加 ************************************/
        elseif (isset($_POST['section_id'])) {
            $db->autocommit(false);

            $need_roll_back = false;
            $date_info = explode(";", $_POST['time']);
            foreach ($date_info as $each_date) {
                $tmp = explode(":", $each_date); // 拆出周几
                $seqs = explode(",", $tmp[1]); // 拆出课节
                //select teacher_id, classroom_code from `section` where course_id=some(select course_id from sec_time where day_of_week=? and lesson_seq=?)

                foreach ($seqs as $seq) {
                    $stmt = $db->prepare("select course_id,section_id,year,semester,teacher_id, classroom_code from section where year=? and semester=? and (course_id,section_id,year,semester) in (select course_id,section_id,year,semester from sec_time where day_of_week=? and lesson_seq=?)"); // 寻找同时间的所有课程
                    $stmt->bind_param("dssd", $_POST['year'], $_POST['semester'], $tmp[0], $seq);
                    $stmt->execute();
                    $stmt->bind_result($cid1, $sid1, $year1, $semester1, $teacher, $classroom);
                    while ($stmt->fetch()) {
                        if ($teacher == $_POST['teacher_id'] || $classroom == $_POST['classroom_code']) {
                            $need_roll_back = true;
//                            printf("<p>数据行：%s.%s, %s, %s, %s, %s, %s, %s, %s</p><p>冲突相关数据：%s.%s, %s, %s, %s, %s</p>", $v[0], $v[1], $v[2], $v[3], $v[4], $v[5], $v[6], $v[7], $v[8],
//                                $cid1,$sid1,$year1,$semester1,$teacher,$classroom);
                            echo '<script> alert("存在冲突数据"); </script>';
                            break;
                        }
                    }
                    $stmt->close();
                    if ($need_roll_back) break;
                }
                if ($need_roll_back) break;
            }

            //新建考核方式数据
            $ass_type = 'exam';
            $stmt = $db->prepare("insert into assessment (type) values (?)");
            $stmt->bind_param("s", $ass_type);
            $stmt->execute();
            if (mysqli_stmt_error($stmt) != null) {
                echo mysqli_error($db);
                $need_roll_back = true;
                echo '<script> alert("未知错误1"); </script>';
            }
            $last_id = mysqli_insert_id($db);

            //插入section数据
            $stmt = $db->prepare("insert into `section` (course_id, section_id, year, semester, teacher_id, classroom_code, max_stu, assessment_id) values (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssdsssdd", $course_id, $_POST['section_id'], $_POST['year'], $_POST['semester'], $_POST['teacher_id'], $_POST['classroom_code'], $_POST['max_stu'], $last_id);
            //printf("%s,%s,%d,%s,%s,%s,%d\n", $v[0], $v[1], $v[3], $v[4], $v[2], $v[7], $v[6]);
            $stmt->execute();
            if (mysqli_stmt_error($stmt) != null) {
                echo mysqli_error($db);
                $need_roll_back = true;
                echo '<script> alert("未知错误2"); </script>';
            }

            $stmt->close();
            //插入每个时间点
            foreach ($date_info as $each_date) {
                $tmp = explode(":", $each_date); // 拆出周几
                $seqs = explode(",", $tmp[1]); // 拆出课节
                foreach ($seqs as $seq) {
                    $stmt = $db->prepare("insert into sec_time (course_id, section_id, `year`, semester, day_of_week, lesson_seq) values (?,?,?,?,?,?)");
                    $stmt->bind_param("ssdssd", $course_id, $_POST['section_id'], $_POST['year'], $_POST['semester'], $tmp[0], $seq);
                    $stmt->execute();
                    if (mysqli_stmt_error($stmt) != null) {
                        $need_roll_back = true;
                        echo '<script> alert("未知错误3"); </script>';
                        break;
                    }
                    $stmt->close();
                }
            }

            if ($need_roll_back) $db->rollback();
            else $db->commit();

            $db->autocommit(true);

        }
        /**************************************** 处理考核更新 ************************************/
        elseif (isset($_POST['u_section_id'])){
            if($_POST['u_type'] == 'exam'){
                $stmt = $db->prepare("update assessment set type=?,date=?,start_time=?,end_time=?,location=? where assessment_id in (select assessment_id from section where course_id=? and section_id=? and year=? and semester=?)");
                $stmt->bind_param('ssssssiis',$_POST['u_type'], $_POST['u_date'],$_POST['u_start_time'],$_POST['u_end_time'],$_POST['u_location'],$course_id, $_POST['u_section_id'], $_POST['u_year'],$_POST['u_semester']);
                $stmt->execute();
                if(mysqli_stmt_error($stmt)){
                    echo '<script> alert("更新失败, '. mysqli_stmt_error($stmt).'"); </script>';
                }
                $stmt->close();

            } else {
                $stmt = $db->prepare("update assessment set type=?,date=null,start_time=null,end_time=null,location=null where assessment_id in (select assessment_id from section where course_id=? and section_id=? and year=? and semester=?)");
                $stmt->bind_param('ssiis',$_POST['u_type'],$course_id, $_POST['u_section_id'], $_POST['u_year'],$_POST['u_semester']);
                $stmt->execute();
                if(mysqli_stmt_error($stmt)){
                    echo '<script> alert("更新失败"); </script>';
                }
                $stmt->close();
            }

        }

        $firstPage = 1;

        $stmt = $db->prepare("select count(*) from section where course_id=?");
        $stmt->bind_param("s", $course_id);
        $stmt->execute();
        $stmt->bind_result($total_amount);
        $stmt->fetch();
        $stmt->close();

        $totalPage = $total_amount % 10 == 0 ? (int)($total_amount / 10) : (int)($total_amount / 10) + 1;

        if (isset($_GET['page'])) {
            if ($_GET['page'] <= 0) $currentPage = 1;
            else if ($_GET['page'] > $totalPage) $currentPage = $totalPage;
            else $currentPage = $_GET['page'];
        } else $currentPage = 1;
        $prePage = $currentPage - 1;
        $nextPage = $currentPage + 1;

        $stmt = $db->prepare("select A.assessment_id,section_id,year,semester,teacher_id,classroom_code,stu_num,max_stu,type,date,start_time,end_time,location from section A,assessment B where A.course_id=? and A.assessment_id=B.assessment_id order by A.year limit ?,?");
        $start = ($currentPage - 1) * 10;
        $len = $start + 1 + 10 > $total_amount ? $total_amount - $start : 10;
        $stmt->bind_param('sii', $course_id, $start, $len);
        $stmt->execute();

        $stmt->bind_result($assessment_id, $section_id, $year, $semester, $teacher_id, $classroom_code, $stu_num, $max_stu, $type, $date, $start_time, $end_time, $location);
        ?>

        <html>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
        <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <style>
            .pageButton {
                cursor: pointer;
                padding: 5px;
                border: 1px black solid;
                border-radius: 3px;
                background: white;
                color: black;
            }

            .pageButton:hover {
                background: black;
                color: white;
            }

            .pageButtonGroup {
                text-align: center;
            }

        </style>

        <title>
            课程数据
        </title>

        <body>

        <div class="container mt-3">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>课程代码</th>
                    <th>开课代码</th>
                    <th>年份</th>
                    <th>教师代码</th>
                    <th>教师</th>
                    <th>已选学生人数</th>
                    <th>最大学生人数</th>
                    <th>考试</th>
                    <th>删除</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    while ($stmt->fetch()) {
                        echo '<tr>';
                        echo '<td>' . $course_id . '</td>';
                        echo '<td>' . $section_id . '</td>';
                        echo '<td>' . $year . '</td>';
                        echo '<td>' . $semester . '</td>';
                        echo '<td>' . $teacher_id . '</td>';
                        echo '<td>' . $stu_num . '</td>';
                        echo '<td>' . $max_stu . '</td>';
                        echo '<td>' . $type . ' ' . $date . ' ' . $start_time . ' ' . $end_time . ' ' . $location . '</td>';

                        echo '<td>';
                        echo '<form action="SectionData.php" method="post">';
                        echo '<input hidden name="cid" value="' . $course_id . '">';
                        echo '<input hidden name="d_section_id" value="' . $section_id . '">';
                        echo '<input hidden name="d_year" value="' . $year . '">';
                        echo '<input hidden name="d_semester" value="' . $semester . '">';
                        echo '<input hidden name="d_assessment_id" value="' . $assessment_id . '">';
                        echo '<button type="submit">删除</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    $stmt->close();

                    ?>
                </tr>
                </tbody>
            </table>

            <div class="pageButtonGroup">
                <a href="SectionData.php?page=<?php echo $firstPage; ?>&cid=<?php echo $course_id; ?>">
                    <button class="pageButton">首页</button>
                </a>
                <a href="SectionData.php?page=<?php echo $prePage; ?>&cid=<?php echo $course_id; ?>">
                    <button class="pageButton"><<</button>
                </a>
                <?php
                if ($currentPage <= 3) {
                    for ($counter = 1; $counter <= 5 && $counter <= $totalPage; $counter++)
                        echo '<a href="SectionData.php?page=' . $counter . '&cid=' . $course_id . '"><button class="pageButton">' . $counter . '</button></a>';
                } else if ($currentPage > 3) {
                    echo '...';
                    for ($counter = $currentPage - 2; $counter <= $currentPage + 2 && $counter <= $totalPage; $counter++)
                        echo '<a href="SectionData.php?page=' . $counter . '&cid=' . $course_id . '"><button class="pageButton">' . $counter . '</button></a>';

                }
                if ($currentPage < $totalPage - 2) {
                    echo '...';
                }
                ?>

                <a href="SectionData.php?page=<?php echo $nextPage; ?>&cid=<?php echo $course_id; ?>">
                    <button class="pageButton">>></button>
                </a>
                <a href="SectionData.php?page=<?php echo $totalPage; ?>&cid=<?php echo $course_id; ?>">
                    <button class="pageButton">末页</button>
                </a>
                <?php echo $currentPage; ?>/<?php echo $totalPage; ?>&nbsp;Pages

            </div>
            <hr>
            <div>
                <h4>插入新课程数据</h4>
                <table class="table table-bordered">

                    <tr>
                        <th>开课代码</th>
                        <th>年份</th>
                        <th>学期</th>
                        <th>教师代码</th>
                    </tr>

                    <form action="SectionData.php" method="post">
                        <tr>

                            <input hidden name="cid" value="<?php echo $course_id; ?>">
                            <td><input name="section_id"></td>
                            <td><input name="year" value="<?php echo $current_year; ?>"></td>
                            <td><input name="semester" value="<?php echo $current_semester; ?>"></td>
                            <td><input name="teacher_id"></td>
                        </tr>
                        <tr>
                            <th>上课时间</th>
                            <th>教室</th>
                            <th>最大学生人数</th>
                            <th>操作</th>
                        </tr>
                        <tr>
                            <td><input name="time"></td>
                            <td><input name="classroom_code"></td>
                            <td><input type="number" name="max_stu"></td>
                            <td>
                                <button type="submit">插入</button>
                            </td>

                        </tr>
                    </form>
                </table>
            </div>
            <hr>
            <div>
                <h4>修改考试信息</h4>

                <form action="SectionData.php" method="post">

                    <input hidden name="cid" value="<?php echo $course_id; ?>">
                    <div class="input-group mb-3">
                        <label for="sid">开课代码</label>
                        <input id="sid" name="u_section_id" type="number">
                    </div>

                    <div class="input-group mb-3">
                        <label for="year">年份</label>
                        <input id="year" name="u_year" value="<?php echo $current_year; ?>">
                    </div>

                    <div class="input-group mb-3">
                        <label for="semester">学期</label>
                        <input id="semester" name="u_semester" value="<?php echo $current_semester; ?>">
                    </div>

                    <div class="input-group mb-3">
                        <label for="sel1">考核方式</label>
                        <select class="form-control" id="sel1" name="u_type">
                            <option selected value="exam">考试</option>
                            <option value="paper">论文</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <label for="date">考核日期(YYYY-MM-DD)</label>
                        <input id="date" name="u_date">
                    </div>

                    <div class="input-group mb-3">
                        <label for="st">考核开始时间（考试必填 HH:MM:SS）</label>
                        <input id="st" name="u_start_time">
                    </div>

                    <div class="input-group mb-3">
                        <label for="en">考核结束时间（考试必填 HH:MM:SS）</label>
                        <input id="en" name="u_end_time">
                    </div>

                    <div class="input-group mb-3">
                        <label for="lo">考核地点（考试必填）</label>
                        <input id="lo" name="u_location">
                    </div>

                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-primary">修改</button>
                    </div>

                </form>
            </div>
        </div>


        </body>
        </html>

        <?php
    }

} else {
    echo "请登录";
    header('refresh:3; url=../Login.html');
}