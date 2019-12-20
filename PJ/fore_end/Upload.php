<html>
<title>Handling file...</title>
<body>
<h1>Handling file...</h1>
<?php
include_once("../lib/Classes/PHPExcel.php");
include_once('../lib/Classes/PHPExcel/IOFactory.php');
include_once("../util/Connection.php");
include_once ("../util/Course.php");

if ($_FILES['excel']['error'] > 0) {
    echo "Something wrong!";
    header('refresh:3; url=InputData.php');
}

$arr = explode('.', $_FILES["excel"]["name"]);
$su = $arr[count($arr) - 1];
if ($su != 'xls' && $su != 'xlsx') {
    echo 'The file is not a excel file';
    header('refresh:3; url=InputData.php');
} else {
    $path = $_FILES['excel']['tmp_name'];
    $xlsReader = PHPExcel_IOFactory::createReader('Excel2007');
    $xlsReader->setReadDataOnly(true);
    $xlsReader->setLoadSheetsOnly(true);
    $Sheets = $xlsReader->load($path);
    $data = $Sheets->getSheet(0)->toArray();
    $db = connect();

    switch ($_POST["optradio"]) {
        case 0: // student info
            $db->autocommit(false); // 开启事务
            $need_roll_back = false;

            foreach ($data as $v) {
                if (count($v) != 6) { // 某行数据量不匹配
                    $need_roll_back = true;
                    echo_error(0);
                    break;
                }
                $acc_type = "student";
                $stmt = $db->prepare("insert into account (uid, password, acc_type) values (?, ?, ?)");
                $stmt->bind_param("sss", $v[0], $v[0], $acc_type); // 初始密码设为账号名
                $stmt->execute();
                if (mysqli_stmt_error($stmt)) {
                    echo_error(1);
                    $need_roll_back = true;
                    break;
                }

                $stmt = $db->prepare("insert into student (student_id, name, enrollment, major, credit, gpa) values (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $v[0], $v[1], $v[2], $v[3], $v[4], $v[5]);
                $result = $stmt->execute();
                if (mysqli_stmt_error($stmt)) {
                    echo_error(8);
                    $need_roll_back = true;
                    break;
                }
            }

            if ($need_roll_back) $db->rollback();
            else $db->commit();
            $db->autocommit(true); // 关闭事务

            break;
        case 1: // teacher info
            $db->autocommit(false); // 开启事务
            $need_roll_back = false;

            foreach ($data as $v) {
                if (count($v) != 4) { // 某行数据量不匹配
                    $need_roll_back = true;
                    echo_error(0);
                    break;
                }
                $acc_type = "teacher";
                $stmt = $db->prepare("insert into account (uid, password, acc_type) values (?, ?, ?)");
                $stmt->bind_param("sss", $v[0], $v[0], $acc_type); // 初始密码设为账号名
                $stmt->execute();
                if (mysqli_stmt_error($stmt)) {
                    $need_roll_back = true;
                    echo_error(1);
                    break;
                }

                $stmt = $db->prepare("insert into teacher (teacher_id, name, title, department) values (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $v[0], $v[1], $v[2], $v[3]);
                $stmt->execute();
                if (mysqli_stmt_error($stmt)) {
                    echo mysqli_error($db);
                    echo $v[0] . " " . $v[1] . " " . $v[2] . " " . $v[3] . "\n";
                    $need_roll_back = true;
                    echo_error(2);
                    break;
                }
            }

            if ($need_roll_back) $db->rollback();
            else $db->commit();
            $db->autocommit(true); // 关闭事务

            break;
        case 2: // course info
            $db->autocommit(false); // 开启事务
            $need_roll_back = false;

            foreach ($data as $v) {
                if (count($v) != 9) { // 某行数据量不匹配
                    $need_roll_back = true;
                    echo_error(0);
                    break;
                }

                // 检查是否有该课程大纲
                $stmt = $db->prepare("select count(course_id) from course where course_id=?");
                $stmt->bind_param("s", $v[0]);
                $stmt->execute();
                $stmt->bind_result($num);
                $stmt->fetch();
                if ($num == 0) {
                    $need_roll_back = true;
                    echo_error(3);
                    break;
                }
                $stmt->close();

                // 检查教室人数和最大人数是否冲突
                $stmt = $db->prepare("select capacity from classroom where classroom_code=?");
                $stmt->bind_param("s", $v[7]);
                $stmt->execute();
                $stmt->bind_result($the_cap);
                if (!$stmt->fetch()) {
                    printf('数据行：数据行：%s.%s, %s, %s, %s, %s, %s, %s, %s 没有该教室', $v[0], $v[1], $v[2], $v[3], $v[4], $v[5], $v[6], $v[7], $v[8]);
                    $need_roll_back = true;
                    $stmt->close();
                    break;
                }
                if($the_cap <= $v[6]){
                    printf('数据行：数据行：%s.%s, %s, %s, %s, %s, %s, %s, %s 教室最大容量小于最大选课人数', $v[0], $v[1], $v[2], $v[3], $v[4], $v[5], $v[6], $v[7], $v[8]);
                    $need_roll_back = true;
                    $stmt->close();
                    break;
                }
                $stmt->close();

                // 检查同时间是否有同教室的课程 和 相同时间是否有同一个教师的课
                // 未提交可以检查到
                $date_info = explode(";", $v[5]);
                foreach ($date_info as $each_date) {
                    $tmp = explode(":", $each_date); // 拆出周几
                    $seqs = explode(",", $tmp[1]); // 拆出课节
                    //select teacher_id, classroom_code from `section` where course_id=some(select course_id from sec_time where day_of_week=? and lesson_seq=?)

                    foreach ($seqs as $seq) {
                        $stmt = $db->prepare("select course_id,section_id,year,semester,teacher_id, classroom_code from section where year=? and semester=? and (course_id,section_id,year,semester) in (select course_id,section_id,year,semester from sec_time where day_of_week=? and lesson_seq=?)"); // 寻找同时间的所有课程
                        $stmt->bind_param("dssd", $v[3], $v[4], $tmp[0], $seq);
                        $stmt->execute();
                        $stmt->bind_result($cid1, $sid1, $year1, $semester1,$teacher, $classroom);
                        while ($stmt->fetch()) {
                            if ($teacher == $v[2] || $classroom == $v[7]) {
                                $need_roll_back = true;
                                printf("<p>数据行：%s.%s, %s, %s, %s, %s, %s, %s, %s</p><p>冲突相关数据：%s.%s, %s, %s, %s, %s</p>", $v[0], $v[1], $v[2], $v[3], $v[4], $v[5], $v[6], $v[7], $v[8],
                                    $cid1,$sid1,$year1,$semester1,$teacher,$classroom);
                                echo_error(4);
                                break;
                            }
                        }
                        $stmt->close();
                        if ($need_roll_back) break;
                    }
                    if ($need_roll_back) break;
                }
                if ($need_roll_back) break;

                //新建考核方式数据
                $stmt = $db->prepare("insert into assessment (type) values (?)");
                $stmt->bind_param("s", $v[8]);
                $stmt->execute();
                if (mysqli_error($db) != null) {
                    echo mysqli_error($db);
                    $need_roll_back = true;
                    echo_error(5);
                    break;
                }
                $last_id = mysqli_insert_id($db);

                //插入section数据
                $stmt = $db->prepare("insert into `section` (course_id, section_id, year, semester, teacher_id, classroom_code, max_stu, assessment_id) values (?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssdsssdd", $v[0], $v[1], $v[3], $v[4], $v[2], $v[7], $v[6], $last_id);
                //printf("%s,%s,%d,%s,%s,%s,%d\n", $v[0], $v[1], $v[3], $v[4], $v[2], $v[7], $v[6]);
                $stmt->execute();
                if (mysqli_error($db) != null) {
                    echo mysqli_error($db);
                    $need_roll_back = true;
                    echo_error(5);
                    break;
                }

                $stmt->close();
                //插入每个时间点
                foreach ($date_info as $each_date) {
                    $tmp = explode(":", $each_date); // 拆出周几
                    $seqs = explode(",", $tmp[1]); // 拆出课节
                    foreach ($seqs as $seq) {
                        $stmt = $db->prepare("insert into sec_time (course_id, section_id, `year`, semester, day_of_week, lesson_seq) values (?,?,?,?,?,?)");
                        $stmt->bind_param("ssdssd", $v[0], $v[1], $v[3], $v[4], $tmp[0], $seq);
                        $stmt->execute();
                        if (mysqli_error($db) != null) {
                            $need_roll_back = true;
                            echo_error(-1);
                            break;
                        }
                        $stmt->close();
                    }
                }

            }

            if ($need_roll_back) $db->rollback();
            else $db->commit();
            $db->autocommit(true); // 关闭事务
            break;
        case 3:
            $db->autocommit(false); // 开启事务
            $need_roll_back = false;

            foreach ($data as $v) {
                if (count($v) != 8) { // 某行数据量不匹配
                    $need_roll_back = true;
                    echo_error(0);
                    break;
                }

                $stmt = $db->prepare("update assessment set date=?,start_time=?,end_time=?,location=? where assessment_id=(select assessment_id from section where course_id=? and section_id=? and year=? and semester=?)");
                $stmt->bind_param('sssssdds', $v[4], $v[5], $v[6], $v[7], $v[0], $v[1], $v[2], $v[3]);
                $rlt = $stmt->execute();
                if (mysqli_error($db) > 0 || !$rlt) {
                    $need_roll_back = true;
                    echo_error(6);
                    break;
                }
            }
            $stmt->close();

            if ($need_roll_back) $db->rollback();
            else $db->commit();

            $db->autocommit(true);
            break;

        case 4:
            $db->autocommit(false);
            $need_roll_back = false;

            foreach ($data as $v) {
                if (count($v) != 2) { // 某行数据量不匹配
                    $need_roll_back = true;
                    echo_error(0);
                    break;
                }
                // 更新课程成绩
                $stmt = $db->prepare("update stu_take_sec set grade=? where student_id=? and course_id=? and section_id=? and year=? and semester=?");
                $stmt->bind_param("sssdds", $v[1], $v[0], $_POST['course_id'], $_POST['section_id'], $_POST['year'], $_POST['semester']);
                $rlt = $stmt->execute();
                $stmt->close();

                // 更新学生成绩
                $stmt = $db->prepare("select A.course_credit,B.grade from course A, stu_take_sec B where B.student_id=? and A.course_id=B.course_id");
                $stmt->bind_param("s", $v[0]);
                $stmt->execute();
                $stmt->bind_result($credit, $grade);
                $son = 0;
                $mon = 0;
                while($stmt->fetch()){
                    if($grade == null || $grade == '') continue;
                    $son += $credit*toG($grade);
                    $mon += $credit;
                }
                $stmt->close();

                $gpa = $mon==0? 0:$son/$mon;

                $stmt = $db->prepare("update student set credit=?,gpa=? where student_id=?");
                $stmt->bind_param('dds',$mon, $gpa, $v[0]);
                $stmt->execute();

                $stmt->close();

                if (mysqli_error($db) > 0 || !$rlt) {
                    $need_roll_back = true;
                    echo_error(6);
                    break;
                }
                if ($db->affected_rows == 0){
                    echo_error(7);
                    $need_roll_back = true;
                    break;
                }
            }

            if ($need_roll_back) $db->rollback();
            else $db->commit();

            $db->autocommit(true);
            break;
        default:
            break;
    }

    $db->close();
    unlink($path);
    if (!$need_roll_back)
        echo 'uploaded!';
    else
        echo '数据已回滚';
}

function echo_error($seq)
{
    switch ($seq) {
        case 0:
            echo "数据量不匹配\n";
            break;
        case 1:
            echo "账户创建错误，请查看是否有重复学/工号\n";
            break;
        case 2:
            echo "数据导入错误，查看数据是否存在\n";
            break;
        case 3:
            echo "不存在的课程代码，请先添加课程大纲";
            break;
        case 4:
            echo "课程同时间教师/教室冲突！";
            break;
        case 5:
            echo "请检查是否有不存在的教师或教室！";
            break;
        case 6:
            echo "更新/插入错误，请检查数据是否匹配";
            break;
        case 7:
            echo "某条数据无法更新数据库，学生是否匹配";
            break;
        case 8:
            echo "插入失败，请查相关信息是否存在";
            break;
        default:
            echo "unknown error\n";
    }
}

function toG($grade){
    switch ($grade){
        case "A": return 4.0;
        case "A-": return 3.7;
        case "B+": return 3.2;
        case "B": return 3.0;
        case 'B-': return 2.7;
        case 'C+': return 2.2;
        case 'C': return 2.0;
        case 'C-': return 1.7;
        case 'D+': return 1.2;
        case 'D': return 1;
        case 'D-': return 0.7;
        case 'F': return 0;
    }
}
?>

</body>
</html>

