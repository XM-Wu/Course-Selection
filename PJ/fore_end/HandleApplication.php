<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/8
 * Time: 16:27
 */
include_once("../util/Connection.php");
include_once("../util/Course.php");
if (!isset($_POST['optradio'])) {
    echo '<script> alert("提交未成功，请选择处理结果"); window.location.href="SectionApplication.php"; </script>';
    exit(0);
}

// 检查冲突，冲突则自动否决
if (check_conf($_POST['student_id'], $_POST['course_id'], $_POST['section_id'], $_POST['year'], $_POST['semester']))
    $auto_false = true;
else $auto_false = false;

if ($_POST['optradio'] == 0 || $auto_false) {
    $state = '未通过';

    if ($auto_false) $reason = '选课冲突';
    else $reason = $_POST['reason'];

    if ($_POST['reason'] == '' && !$auto_false) {
        echo '<script> alert("提交未成功，不通过请填写理由"); window.location.href="SectionApplication.php"; </script>';
        exit(0);
    }

    $db = connect();
    $stmt = $db->prepare("update application_transaction set state=?,handle_reason=? where apply_id=?");
    $stmt->bind_param('ssd', $state, $reason, $_POST['apply_id']);
    $stmt->execute();

    $stmt->close();
    $db->close();

    if ($auto_false) {
        echo '<script> alert("该学生选课冲突，自动否决"); window.location.href="SectionApplication.php"; </script>';
    } else{
        echo '<script> alert("已提交"); window.location.href="SectionApplication.php"; </script>';
    }
} else {
    $state = '通过';

    $db = connect();

    $db->autocommit(false);
    // 选课申请为设置通过
    $stmt = $db->prepare("update application_transaction set state=?,handle_reason=? where apply_id=?");
    $stmt->bind_param('ssd', $state, $reason, $_POST['apply_id']);
    $stmt->execute();
    $stmt->close();

    // 为学生添加选课
    $stmt = $db->prepare('insert into stu_take_sec (student_id,course_id,section_id,year,semester) values (?,?,?,?,?)');
    $stmt->bind_param('ssdds', $_POST['student_id'], $_POST['course_id'], $_POST['section_id'], $_POST['year'], $_POST['semester']);
    $stmt->execute();
    $stmt->close();

    $stmt = $db->prepare('update section set stu_num=stu_num+1 where course_id=? and section_id=? and year=? and semester=?');
    $stmt->bind_param('sdds', $_POST['course_id'], $_POST['section_id'], $_POST['year'], $_POST['semester']);
    $stmt->execute();
    $stmt->close();

    // 检查人数，若达到教室人数就将其他申请一律否决
    $stmt = $db->prepare('select A.stu_num,B.capacity from section A,classroom B where A.classroom_code=B.classroom_code and A.course_id-? and A.section_id=? and A.year=? and A.semester=?');
    $stmt->bind_param('sdds', $_POST['course_id'], $_POST['section_id'], $_POST['year'], $_POST['semester']);
    $stmt->execute();
    $stmt->bind_result($num, $max);
    $stmt->fetch();
    $stmt->close();
    if($num >= $max){
        $h_reason = '教室容量达到上限';
        $state = '未通过';
        $curr_state = '已提交';
        $stmt=$db->prepare('update application_transaction set handle_reason=?,state=? where apply_id=? and state=?');
        $stmt->bind_param('ssds', $h_reason, $state, $_POST['apply_id'],$curr_state);
        $stmt->execute();
    }


    if(mysqli_error($db) > 0) {
        $db->rollback();
        echo '处理失败';
        header("refresh:3;url=SectionApplication.php");
    } else {
        $db->commit();
    }

    $db->autocommit(true);
    $db->close();
    echo '<script> alert("处理成功"); window.location.href="SectionApplication.php"; </script>';
}