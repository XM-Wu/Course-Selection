<html>
<title>Handling file...</title>
<body>
<h1>Handling file...</h1>
<?php
include_once("../lib/Classes/PHPExcel.php");
include_once('../lib/Classes/PHPExcel/IOFactory.php');
include_once("../util/Connection.php");

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
                    break;
                }
                $acc_type = "student";
                $stmt = $db->prepare("insert into account (uid, password, acc_type) values (?, ?, ?)");
                $stmt->bind_param("sss", $v[0], $v[0], $acc_type); // 初始密码设为账号名
                $result = $stmt->execute();
                if (!$result) {
                    $need_roll_back = true;
                    break;
                }

                $stmt = $db->prepare("insert into student (student_id, name, enrollment, major, credit, gpa) values (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $v[0], $v[1], $v[2], $v[3], $v[4], $v[5]);
                $result = $stmt->execute();
                if (!$result) {
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
                $result = $stmt->execute();
                if (!$result) {
                    $need_roll_back = true;
                    echo_error(1);
                    break;
                }

                $stmt = $db->prepare("insert into teacher (teacher_id, name, title, department) values (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $v[0], $v[1], $v[2], $v[3]);
                $result = $stmt->execute();
                if (!$result) {
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
            //$table = "section";
            break;
        case 3:
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
        default:
            echo "unknown error\n";
    }
}

?>

</body>
</html>

