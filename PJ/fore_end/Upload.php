<html>
<title>Handling file...</title>
<body>
<h1>Handling file...</h1>
<?php
include_once ("../lib/Classes/PHPExcel.php");
include_once('../lib/Classes/PHPExcel/IOFactory.php');
include_once ("../util/Connection.php");

if ($_FILES['excel']['error'] > 0) {
    echo "Something wrong!";
    header('refresh:3; url=InputData.php');
}

$arr = explode('.', $_FILES["excel"]["name"]);
$su = $arr[count($arr)-1];
if ($su != 'xls' && $su != 'xlsx') {
    echo 'The file is not a excel file';
    header('refresh:3; url=InputData.php');
}
else {
    $table = "account";
    echo $table."\n";
    switch ($_POST["optradio"]){
        case 0:
            //$table = "student";
            break;
        case 1:
            //$table = "teacher";
            break;
        case 2:
            //$table = "section";
            break;
        default:
            break;
    }
    $path = $_FILES['excel']['tmp_name'];

    $xlsReader = PHPExcel_IOFactory::createReader('Excel2007');
    $xlsReader->setReadDataOnly(true);
    $xlsReader->setLoadSheetsOnly(true);
    $Sheets = $xlsReader->load($path);
    $data = $Sheets->getSheet(0)->toArray();

    $db = connect();
    $stmt = $db->prepare("insert into account (uid, password, acc_type) values (?, ?, ?)");
    foreach ($data as $v) {
        if(count($v) != 3) exit(1);
        $stmt->bind_param("sss", $v[0], $v[1], $v[2]);
        $result = $stmt->execute();

        if (!$result) {
            array_push($arr, $v);
        }
    }

    $db->close();
    unlink($path);

    echo 'uploaded!';
}
?>

</body>
</html>

