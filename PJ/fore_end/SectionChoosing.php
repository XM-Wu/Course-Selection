<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/11/16
 * Time: 11:02
 */
session_start();
if (isset($_SESSION["username"])) {
    include_once("../util/Connection.php");
    include_once("../util/Course.php");
    $mysqli = connect();
    $current_year = 2019;
    $current_semester = "第一学期";
    $stmt = $mysqli->prepare("select course_id,section_id from stu_take_sec where student_id=? and year=? and semester=?");
    $stmt->bind_param("sds", $_SESSION['username'], $current_year, $current_semester);
    $stmt->execute();
    $stmt->bind_result($cid, $sid);

    $map = array( // 7*14
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('', '', '', '', '', '', '', '', '', '', '', '', '', '')
    );

    // 建立节次到课程的映射
    while ($stmt->fetch()) {
        $time = get_section_time($cid, $sid, $current_year, $current_semester);
        $name = get_course_name($cid);
        foreach ($time as $t){
            $index1 = (int)floor($t / 100);
            $index2 = $t-$index1*100;
            $map[$index1][$index2] = $name;
        }


    }

    $stmt->close();
    $mysqli->close();

    ?>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
        <script src="../lib/table/Timetables.min.js"></script>
        <style>
            #coursesTable {
                padding: 15px 10px;
            }

            .Courses-head {
                background-color: #edffff;
            }

            .Courses-head > div {
                text-align: center;
                font-size: 14px;
                line-height: 28px;
            }

            .left-hand-TextDom, .Courses-head {
                background-color: #f2f6f7;
            }

            .Courses-leftHand {
                background-color: #f2f6f7;
                font-size: 12px;
            }

            .Courses-leftHand .left-hand-index {
                color: #9c9c9c;
                margin-bottom: 4px !important;
            }

            .Courses-leftHand .left-hand-name {
                color: #666;
            }

            .Courses-leftHand p {
                text-align: center;
                font-weight: 900;
            }

            .Courses-head > div {
                border-left: none !important;
            }

            .Courses-leftHand > div {
                padding-top: 5px;
                border-bottom: 1px dashed rgb(219, 219, 219);
            }

            .Courses-leftHand > div:last-child {
                border-bottom: none !important;
            }

            .left-hand-TextDom, .Courses-head {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
            }

            .Courses-content > ul {
                border-bottom: 1px dashed rgb(219, 219, 219);
                box-sizing: border-box;
            }

            .Courses-content > ul:last-child {
                border-bottom: none !important;
            }

            .highlight-week {
                color: #02a9f5 !important;
            }

            .Courses-content li {
                text-align: center;
                color: #666666;
                font-size: 14px;
                line-height: 50px;
            }

            .Courses-content li span {
                padding: 6px 2px;
                box-sizing: border-box;
                line-height: 18px;
                border-radius: 4px;
                white-space: normal;
                word-break: break-all;
                cursor: pointer;
            }

            .grid-active {
                z-index: 9999;
            }

            .grid-active span {
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            }
        </style>
        <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
        <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>


    <title>
        选课
    </title>

    <body clss="container">
    <!-- 导航栏 -->
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link active" href="SectionChoosing.php">选课</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="SectionApplication.php">选课事务申请</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="GradeChecking.php">成绩查询</a>
        </li>
    </ul>
    <!-- 课表 -->
    <div id="coursesTable"></div>

    <div id="accordion">
        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapseOne">
                    已选课程
                </a>
            </div>
            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                    <!--  内容1 /////////////////////////////////////////////////////////////// -->
                    <table class="table">
                        <thead>
                        <tr>
                            <th>课程代码</th>
                            <th>课程名称</th>
                            <th>教师</th>
                            <th>时间</th>
                            <th>地点</th>
                            <th>已选人数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- sample php code TODO here -->
                        <?php


                        if (isset($_POST["course_id"])) {
                            echo "<script> alert('" . $_POST["course_id"] . " 退课成功'); </script>";
                        }
                        ?>
                        <tr>
                            <td>SOFT1101.1</td>
                            <td>java程序设计</td>
                            <td>陆逸凡</td>
                            <td>周日 2-3</td>
                            <td>Z2204</td>
                            <td>90/100</td>
                            <td>
                                <form method="post" action="SectionChoosing.php">
                                    <input hidden value="CS101" name="course_id">
                                    <input hidden value="1" name="section_id">
                                    <button type="submit">退课</button>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                    课程搜索
                </a>
            </div>
            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                <!--  内容2 /////////////////////////////////////////////////////////////// -->
                <div class="card-body">
                    <form class="form-inline">
                        <label for="course_code">课程代码</label>
                        <input type="text" class="form-control" id="course_code" placeholder="输入课程代码">
                        <label for="course_name">课程名称</label>
                        <input type="text" class="form-control" id="course_name" placeholder="输入课程名称">
                        <button type="submit" class="btn btn-primary">搜索</button>
                    </form>
                    <div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>课程代码</th>
                                <th>课程名称</th>
                                <th>教师</th>
                                <th>时间</th>
                                <th>地点</th>
                                <th>已选人数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- sample php code TODO here -->
                            <tr>
                                <td>SOFT1101.1</td>
                                <td>java程序设计</td>
                                <td>陆逸凡</td>
                                <td>周日 2-3</td>
                                <td>Z2204</td>
                                <td>90/100</td>
                                <td>
                                    <button>选课</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="card">-->
        <!--            <div class="card-header">-->
        <!--                <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">-->
        <!--                    选项三-->
        <!--                </a>-->
        <!--            </div>-->
        <!--            <div id="collapseThree" class="collapse" data-parent="#accordion">-->
        <!--                <div class="card-body">-->
        <!--                    #3 内容：菜鸟教程 -- 学的不仅是技术，更是梦想！！！-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>


    </body>
    <script>
        // var courseList = [
        //     ['大学英语(Ⅳ)@10203', '大学英语(Ⅳ)@10203', '', '', '', '', '毛概@14208', '毛概@14208', '', '', '', '选修', '', ''],
        //     ['', '', '信号与系统@11302', '信号与系统@11302', '模拟电子技术基础@16204', '模拟电子技术基础@16204', '', '', '', '', '', '', '', ''],
        //     ['大学体育(Ⅳ)', '大学体育(Ⅳ)', '形势与政策(Ⅳ)@15208', '形势与政策(Ⅳ)@15208', '', '', '电路、信号与系统实验', '电路、信号与系统实验', '', '', '', '', '', ''],
        //     ['', '', '', '', '电装实习@11301', '电装实习@11301', '', '', '', '大学体育', '大学体育', '', '', ''],
        //     ['', '', '数据结构与算法分析', '数据结构与算法分析', '', '', '', '', '信号与系统', '信号与系统', '', '', '', ''],
        //     ['', '', '', '', '', '', '', '', '', '', '', '', '', ''],
        //     ['', '', '', '', '', '', '', '', '', '', '', '', '', '']
        // ];
        <?php
                echo 'var courseList = [';
                for($i = 0; $i<7; $i++){
                    echo '[';
                    for($j = 0; $j<14; $j++){
                        echo '\''. $map[$i][$j] . '\'';
                        if($j!=13) echo ',';
                    }
                    echo ']';
                    if ($i !=6) echo ',';
                }
                echo '];';
        ?>
        var week = window.innerWidth > 360 ? ['周一', '周二', '周三', '周四', '周五', '周六', '周日'] :
            ['一', '二', '三', '四', '五', '六', '日'];
        var day = new Date().getDay();
        var courseType = [
            [{index: '1', name: '8:30'}, 1],
            [{index: '2', name: '9:30'}, 1],
            [{index: '3', name: '10:30'}, 1],
            [{index: '4', name: '11:30'}, 1],
            [{index: '5', name: '12:30'}, 1],
            [{index: '6', name: '14:30'}, 1],
            [{index: '7', name: '15:30'}, 1],
            [{index: '8', name: '16:30'}, 1],
            [{index: '9', name: '17:30'}, 1],
            [{index: '10', name: '18:30'}, 1],
            [{index: '11', name: '19:30'}, 1],
            [{index: '12', name: '20:30'}, 1],
            [{index: '13', name: '19:30'}, 1],
            [{index: '14', name: '20:30'}, 1]
        ];
        // 实例化(初始化课表)
        var Timetable = new Timetables({
            el: '#coursesTable',
            timetables: courseList,
            week: week,
            timetableType: courseType,
            highlightWeek: day,
            gridOnClick: function (e) {
                alert(e.name + '  ' + e.week + ', 第' + e.index + '节课, 课长' + e.length + '节');
                console.log(e);
            },
            styles: {
                Gheight: 50
            }
        });
    </script>

    </html>
    <?php
} else {
    echo "请登录";
    header('refresh:3; url=Login.html');
}