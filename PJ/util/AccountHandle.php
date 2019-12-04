<?php
/**
 * Created by PhpStorm.
 * User: Mean
 * Date: 2019/12/4
 * Time: 13:53
 */

function correct_account($mysqli, $usr, $pwd){
    $stmt = $mysqli->prepare("select uid, password from account where uid =?");
    $stmt->bind_param("s", $usr);
    $stmt->execute();

    $stmt->bind_result($u, $p);
    if($stmt->fetch() == 0 || $p != $pwd) return False;

    return True;
}