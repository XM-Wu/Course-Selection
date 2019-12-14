<?php

session_start();
if (isset($_SESSION['username']) && $_SESSION['type'] == 'admin') {
    include_once("../../util/Connection.php");
    $db = connect();

    // 处理删除
    if(isset($_POST['delete'])){
        $stmt = $db->prepare('delete from major where major_name=?');
        $stmt->bind_param('s', $_POST['delete']);
        $stmt->execute();
        if(mysqli_stmt_error($stmt)) {
            echo '<script> alert("删除失败，存在关联数据"); </script>';
            $need_roll_back = true;
        }
        $stmt->close();
    }
    // 处理添加
    elseif(isset($_POST['major_name'])){
        $stmt = $db->prepare("insert into major (major_name, department) values (?, ?)");
        $stmt->bind_param("ss", $_POST['major_name'], $_POST['department']); // 初始密码设为账号名
        $stmt->execute();
        if (mysqli_stmt_error($stmt)) {
            echo '<script> alert("插入失败，查看是否存在冲突数据"); </script>';
            $need_roll_back = true;
        }
        $stmt->close();

    }

    $firstPage = 1;

    $stmt = $db->prepare("select count(*) from major");
    $stmt->execute();
    $stmt->bind_result($total_amount);
    $stmt->fetch();
    $stmt->close();

    $totalPage = $total_amount % 10 == 0 ? (int)($total_amount / 10) : (int)($total_amount / 10) + 1;

    if(isset($_GET['page'])){
        if($_GET['page'] <= 0) $currentPage = 1;
        else if ($_GET['page'] > $totalPage) $currentPage = $totalPage;
        else $currentPage = $_GET['page'];
    } else $currentPage = 1;
    $prePage = $currentPage - 1;
    $nextPage = $currentPage + 1;

    $stmt = $db->prepare("select * from major order by major_name limit ?,?");
    $start = ($currentPage - 1) * 10;
    $len = $start + 1 + 10 > $total_amount ? $total_amount - $start : 10;
    $stmt->bind_param('ii', $start, $len);
    $stmt->execute();

    $stmt->bind_result($major_name, $department);
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
        学院数据
    </title>

    <body>

    <div class="container mt-3">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>专业</th>
                <th>学院</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $major_name . '</td>';
                    echo '<td>' . $department . '</td>';
                    echo '<td>';
                    echo '<form action="MajorData.php" method="post">';
                    echo '<input hidden name="delete" value="'. $major_name .'">';
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
            <a href="MajorData.php?page=<?php echo $firstPage; ?>">
                <button class="pageButton">首页</button>
            </a>
            <a href="MajorData.php?page=<?php echo $prePage; ?>">
                <button class="pageButton"><<</button>
            </a>
            <?php
            if ($currentPage <= 3) {
                for ($counter = 1; $counter <= 5 && $counter <= $totalPage; $counter++)
                    echo '<a href="MajorData.php?page=' . $counter . '"><button class="pageButton">' . $counter . '</button></a>';
            } else if ($currentPage > 3) {
                echo '...';
                for ($counter = $currentPage - 2; $counter <= $currentPage + 2 && $counter <= $totalPage; $counter++)
                    echo '<a href="MajorData.php?page=' . $counter . '"><button class="pageButton">' . $counter . '</button></a>';

            }
            if ($currentPage < $totalPage - 2) {
                echo '...';
            }
            ?>

            <a href="MajorData.php?page=<?php echo $nextPage; ?>">
                <button class="pageButton">>></button>
            </a>
            <a href="MajorData.php?page=<?php echo $totalPage; ?>">
                <button class="pageButton">末页</button>
            </a>
            <?php echo $currentPage; ?>/<?php echo $totalPage; ?>&nbsp;Pages

        </div>
        <div>
            <h4>插入新数据</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>专业</th>
                    <th>学院</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <form action="MajorData.php" method="post">
                        <td><input name="major_name"></td>
                        <td><input name="department"></td>
                        <td><button type="submit">插入</button></td>
                    </form>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </body>
    </html>

    <?php

} else {
    echo "请登录";
    header('refresh:3; url=../Login.html');
}