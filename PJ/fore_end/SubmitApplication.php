<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/8
 * Time: 11:59
 */

session_start();
if(isset($_SESSION['username'])){
    include_once ("../util/Connection.php");


    $id=explode('.',$_POST['cid']);
    $state = '已提交';

    if (sizeof($id) != 2){
        echo '<script> alert("提交未成功，不能存在该课程"); window.location.href="SectionApplication.php"; </script>';
        exit(0);
    }
    $db = connect();

    // 检查是否存在课程
    $stmt = $db->prepare("select course_id from section where course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("sdds",  $id[0], $id[1], $_POST['year'], $_POST['semester']);
    $r = $stmt->execute();
    $stmt->bind_result($rlt);
    if(!$stmt->fetch()){
        echo '<script> alert("提交未成功，不能存在该课程"); window.location.href="SectionApplication.php"; </script>';
        $stmt->close();
        $db->close();
        exit(0);
    }
    $stmt->close();

    // 检查是否是已退的课程
    $stmt = $db->prepare("select course_id from quit where student_id=? and course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdds", $_SESSION['username'], $id[0], $id[1], $_POST['year'], $_POST['semester']);
    $r = $stmt->execute();
    $stmt->bind_result($rlt);
    if($stmt->fetch()){
        echo '<script> alert("提交未成功，不能申请已退课程"); window.location.href="SectionApplication.php"; </script>';
        $stmt->close();
        $db->close();
        exit(0);
    }
    $stmt->close();

    // 检出是否是已选的课程
    $stmt = $db->prepare("select course_id from stu_take_sec where student_id=? and course_id=? and section_id=? and year=? and semester=?");
    $stmt->bind_param("ssdds", $_SESSION['username'], $id[0], $id[1], $_POST['year'], $_POST['semester']);
    $r = $stmt->execute();
    $stmt->bind_result($rlt);
    if($stmt->fetch()){
        echo '<script> alert("提交未成功，不能申请已选的课程"); window.location.href="SectionApplication.php"; </script>';
        $stmt->close();
        $db->close();
        exit(0);
    }
    $stmt->close();

    //检查是否超出教室容量
    $stmt = $db->prepare("select A.stu_num,B.capacity from section A, classroom B where course_id=? and section_id=? and year=? and semester=? and A.classroom_code=B.classroom_code");
    $stmt->bind_param("sdds",  $id[0], $id[1], $_POST['year'], $_POST['semester']);
    $r = $stmt->execute();
    $stmt->bind_result($num, $max);
    $stmt->fetch();
    if($num >= $max){
        echo '<script> alert("提交未成功，教室已达到最大容量"); window.location.href="SectionApplication.php"; </script>';
        $stmt->close();
        $db->close();
        exit(0);
    }
    $stmt->close();


    $stmt = $db->prepare("insert into application_transaction (student_id,course_id,section_id,year,semester,apply_reason,state) values (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssddsss", $_SESSION['username'], $id[0], $id[1], $_POST['year'], $_POST['semester'], $_POST['reason'], $state);
    $rlt = $stmt->execute();
    $stmt->close();

    if (mysqli_error($db) > 0 || !$rlt) {
        echo '<script> alert("提交未成功' . mysqli_error($db) . '"); window.location.href="SectionApplication.php"; <script>';
    } else {
        echo '<script> alert("提交成功"); window.location.href="SectionApplication.php"; </script>';
    }
    $db->close();

}