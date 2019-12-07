<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/6
 * Time: 15:46
 */

function get_course_name($course_id)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select course_name from course where course_id=?");
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $stmt->bind_result($rlt);

    $stmt->fetch();
    $stmt->close();
    $mysqli->close();
    return $rlt;
}

function get_grade($student_id, $course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select grade from stu_take_sec where student_id=? and course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdds", $student_id, $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->bind_result($grade);

    $stmt->fetch();
    $stmt->close();
    $mysqli->close();

    return $grade;
}

function get_section_info($course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select teacher_id, classroom_code, stu_num, max_stu from section where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdd", $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->bind_result($tid, $cc, $sn, $ms);
    $stmt->fetch();
    $info["course_name"] = get_course_name($course_id);
    $info["teacher_name"] = get_teacher_name($tid);
    $info["classroom_code"] = $cc;
    $info["stu_num"] = $sn;
    $info["max_stu"] = $ms;

    // 代码转字符串
    $time_str = '';
    $time = get_section_time($course_id, $section_id, $year, $semester);
    $week = array(
        array(), array(), array(), array(), array(), array(), array()
    );
    foreach ($time as $t) {
        array_push($week[(int)($t / 100)], $t % 100);
    }
    for ($i = 0; $i < 7; $i++) { // 拼接周
        if (count($week[$i]) == 0) continue;
        $str = n2t($i) . ': 第';
        $len = count($week[$i]);
        for ($j = 0; $j < $len; $j++) { // 拼接课节
            $str .= ($week[$i][$j] + 1);
            if ($j != $len - 1) $str .= '、';
        }
        $str .= '节课';

        $time_str .= $str . ';';
    }
    $info["time"] = $time_str;


    $stmt->close();
    $mysqli->close();
    return $info;
}

function get_teacher_name($tid)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select name from teacher where teacher_id=?");
    $stmt->bind_param("s", $tid);
    $stmt->execute();
    $stmt->bind_result($rlt);

    $stmt->fetch();
    $stmt->close();
    $mysqli->close();
    return $rlt;
}

/**
 * @return array 给一个代码形式的时间，如 102 代表第2周第3节课
 */
function get_section_time($course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();

    $time = array();
    $stmt = $mysqli->prepare("select day_of_week, lesson_seq from sec_time where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssds", $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->bind_result($dow, $ls);

    $i = 0;
    while ($stmt->fetch()) {
        $time[$i] = ds2i($dow, $ls);
        $i++;
    }
    $stmt->close();
    $mysqli->close();

    return $time;
}

function get_section_assessment_id($course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select assessment_id from section where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssds", $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->bind_result($ai);
    $stmt->fetch();

    $stmt->close();
    $mysqli->close();

    return $ai;
}

/**
 * @return bool false：有冲突; true：没有
 */
function check_exam_conf($ai1, $ai2)
{
    include_once("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select type,date,start_time,end_time from assessment where assessment_id=?");
    $stmt->bind_param("d", $ai1);
    $stmt->execute();
    $stmt->bind_result($type1, $date1, $start_time1, $end_time1);
    $stmt->fetch();

    $stmt->close();

    $stmt = $mysqli->prepare("select type,date,start_time,end_time from assessment where assessment_id=?");
    $stmt->bind_param("d", $ai2);
    $stmt->execute();
    $stmt->bind_result($type2, $date2, $start_time2, $end_time2);
    $stmt->fetch();

    $stmt->close();

    if ($type1 == 'paper' || $type2 == 'paper') return true;

    //直接字符串比较
    if ($date1 != $date2) return true;
    if ($start_time1 == null || $start_time2 == null) return true;
    if (($start_time1 >= $start_time2 && $start_time1 <= $end_time2)
        || ($end_time1 >= $start_time2 && $end_time1 <= $end_time2)
        || ($start_time2 >= $start_time1 && $start_time2 <= $end_time1)
        || ($end_time2 >= $start_time1 && $end_time2 <= $end_time1))
        return false;
    return true;
}

function ds2i($day_of_week, $lesson_seq)
{
    return t2n($day_of_week) * 100 + ($lesson_seq - 1);
}

function t2n($day_of_week)
{
    switch ($day_of_week) {
        case "周一":
            return 0;
        case "周二":
            return 1;
        case "周三":
            return 2;
        case "周四":
            return 3;
        case "周五":
            return 4;
        case "周六":
            return 5;
        case "周日":
            return 6;
        default:
            return false;
    }
}

function n2t($code)
{
    switch ($code) {
        case 0:
            return "周一";
        case 1:
            return "周二";
        case 2:
            return "周三";
        case 3:
            return "周四";
        case 4:
            return "周五";
        case 5:
            return "周六";
        case 6:
            return "周日";
        default:
            return false;
    }
}

/**
 * 有冲突则返回冲突数据，没有冲突返回flase
 * @return bool|array
 */
function check_conf($student_id, $course_id, $section_id, $year, $semester)
{
    $s_time = get_section_time($course_id, $section_id, $year, $semester);

    include_once("Connection.php");
    $mysqli = connect();
    $stmt = $mysqli->prepare("select course_id,section_id,year,semester from stu_take_sec where student_id=?"); // 查找所有已选课程
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($h_course_id, $h_section_id, $h_year, $h_semester);
    while ($stmt->fetch()) {
        $h_time = get_section_time($h_course_id, $h_section_id, $h_year, $h_semester);

        // 重复查找
        $result = array_intersect($s_time, $h_time);

        if (count($result) > 0) {
            $conf = array('type' => '上课时间冲突', 'course_id' => $h_course_id, 'section_id' => $h_section_id, 'year' => $h_year, 'semester' => $h_semester);
            return $conf;
        }

        // 检查考试重复
        if (!check_exam_conf(get_section_assessment_id($course_id, $section_id, $year, $semester),
            get_section_assessment_id($h_course_id, $h_section_id, $h_year, $h_semester))
        ) {
            $conf = array('type' => '考试时间冲突', 'course_id' => $h_course_id, 'section_id' => $h_section_id, 'year' => $h_year, 'semester' => $h_semester);
            return $conf;
        }
    }
    $stmt->close();
    $mysqli->close();
    return false;
}

function check_num($course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();
    $stmt = $mysqli->prepare("select stu_num,max_stu from section where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("sdds", $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->bind_result($curr, $max);
    $stmt->fetch();

    if ($curr >= $max) return false;
    else return true;
}

function insert_course($stid, $cid, $seid, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();
    $mysqli->autocommit(false);

    $stmt = $mysqli->prepare("insert into stu_take_sec (student_id, course_id, section_id, year, semester) values (?,?,?,?,?)");
    $stmt->bind_param("ssdds", $stid, $cid, $seid, $year, $semester);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("update section set stu_num=stu_num+1 where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("sdds", $cid, $seid, $year, $semester);
    $stmt->execute();

    if (mysqli_error($mysqli) > 0) {
        $mysqli->rollback();
    } else $mysqli->commit();

    $mysqli->autocommit(true);
    $stmt->close();
    $mysqli->close();
}

function quit_course($student_id, $course_id, $section_id, $year, $semester)
{
    include_once("Connection.php");
    $mysqli = connect();
    $mysqli->autocommit(false);

    $stmt = $mysqli->prepare("delete from stu_take_sec where student_id=? and course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdds", $student_id, $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("update section set stu_num=stu_num-1 where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("sdds", $course_id, $section_id, $year, $semester);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("insert into quit (student_id, course_id, section_id, year, semester) values (?,?,?,?,?)");
    $stmt->bind_param("ssdds", $student_id, $course_id, $section_id, $year, $semester);
    $stmt->execute();

    if (mysqli_error($mysqli) > 0) {
        $mysqli->rollback();
        echo mysqli_error($mysqli);
    } else $mysqli->commit();

    $mysqli->autocommit(true);
    $stmt->close();
    $mysqli->close();
}