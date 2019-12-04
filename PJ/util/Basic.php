<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/4
 * Time: 14:20
 */
function insert($mysqli, $table, $attributes, $values){
    $mysqli = connect();
    $stmt = $mysqli->prepare("insert into account (uid, password) values (?, ?)"); // 防sql注入
    $stmt->bind_param("ss", $id, $pw);

    $stmt->execute();
}
function select($mysqli){
    $stmt = $mysqli->prepare("select uid from account where uid ='uid11'");
    $stmt->execute();

    $stmt->bind_result($district);
    $stmt->fetch();
    echo $district;
}