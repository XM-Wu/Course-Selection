<?php
session_start();
//if(isset($_SESSION["username"]) && $_SESSION["type"] == "admin") {
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
            </div>
            <div>
                <div class="radio">
                    <label><input type="radio" name="optradio" value="0">导入学生数据</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="optradio" value="1">导入老师数据</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="optradio" value="2">导入课程数据</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        function upload_file() {
            // 检查是否选择文件
            let fileInput = $("input[name='excel']").get(0).files[0];
            if (!fileInput){
                alert("请选择文件");
                return false
            }

            // 检查是否选择按钮
            let tag = false;
            let radios = document.getElementsByName("optradio");
            for(radio in radios) {
                if(radios[radio].checked) {
                    tag = true;
                    break;
                }
            }
            if(!tag) {
                alert("请选择所传数据分类");
                return false;
            }
        }
    </script>
    </body>
    </html>
    <?php
//} else {
//    echo "请登录";
//    header('refresh:3; url=Login.html');
//}