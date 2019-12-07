<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/7
 * Time: 21:52
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>导入数据</title>
</head>
<body>
<div class="container">
    <form action="Upload.php" method="post" onsubmit="return upload_file();" enctype="multipart/form-data"> 
        <div class="form-group">
            <label for="excel_file">请选择excel文件:</label>
            <input id="excel_file" type="file" name="excel">
            <p>格式：学号，评分</p>
        </div>
        <input hidden name="optradio" value="4">
        <input hidden name="course_id" value="<?php echo $_POST['course_id']; ?>">
        <input hidden name="section_id" value="<?php echo $_POST['section_id']; ?>">
        <input hidden name="year" value="<?php echo $_POST['year']; ?>">
        <input hidden name="semester" value="<?php echo $_POST['semester']; ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    function upload_file() {
        let fileInput = $("input[name='excel']").get(0).files[0];
        if (!fileInput) {
            alert("请选择文件");
            return false;
        }
    }
</script>
</body>
</html>