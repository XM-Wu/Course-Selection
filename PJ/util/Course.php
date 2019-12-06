<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/6
 * Time: 15:46
 */

function get_course_name($course_id){
    include_once ("Connection.php");
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

function get_grade($student_id, $course_id, $section_id, $year, $semester){
    include_once ("Connection.php");
    $mysqli = connect();

    $stmt = $mysqli->prepare("select grade from stu_take_sec where student_id=? and course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdds", $student_id,$course_id,$section_id,$year,$semester);
    $stmt->execute();
    $stmt->bind_result($grade);

    $stmt->fetch();
    $stmt->close();
    $mysqli->close();

    return $grade;
}

function get_section_info($course_id, $section_id, $year, $semester){

}

function get_section_time($course_id, $section_id, $year, $semester){
    include_once ("Connection.php");
    $mysqli = connect();

    $time = array();
    $stmt = $mysqli->prepare("select day_of_week, lesson_seq from sec_time where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssds",$course_id,$section_id,$year,$semester);
    $stmt->execute();
    $stmt->bind_result($dow, $ls);

    $i = 0;
    while($stmt->fetch()){
        $time[$i] = ds2i($dow, $ls);
        $i++;
    }
    $stmt->close();
    $mysqli->close();

    return $time;
}

function ds2i($day_of_week, $lesson_seq){
    return t2n($day_of_week) * 100 + ($lesson_seq-1);
}

function t2n($day_of_week){
    switch ($day_of_week){
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
        default: return false;
    }
}