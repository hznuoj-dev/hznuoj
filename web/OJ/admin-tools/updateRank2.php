<?php return; ?>

<?php
/**
 * This file is created
 * by yybird
 * @2015.06.27
 * last modified
 * by yybird
 * @2015.07.03
 **/
?>

<?php

//跑这个脚本之前先跑updateScores更新题目分数
require_once('../include/db_info.inc.php');

// 获取用户总量
$sql = "SELECT user_id FROM users";
$result_user  = $mysqli->query($sql) or die($mysqli->error);
if($result_user) $user_cnt = $result_user->num_rows;
else $user_cnt = 0;

$user_info = array();
for ($i=0; $i<$user_cnt; $i++) {
    $user_info[$i] = $result_user->fetch_object();
}
$result_user->free();
echo $user_cnt."<br>";

// 获取hznuoj分数
for ($i=0; $i<$user_cnt; $i++) {
    
    // 获取用户
    $user_mysql = $user_info[$i]->user_id;
    
    $strength = 0;
    $level = "斗之气一段";
    $color = "#E0E0E0";

    //calculate strength
    $sql = <<<SQL
        SELECT SUM(score) AS sum FROM problem WHERE problem_id IN (
        SELECT problem_id FROM solution
        WHERE user_id = '$user_mysql'
        AND result = 4
);
SQL;

    $res = $mysqli->query($sql) or die($mysqli->error);
    if ($res->num_rows > 0) {
        $row = $res->fetch_object();
        $strength = floatval($row->sum);
    }
    
    // count hznuoj solved
    $sql="SELECT count(DISTINCT problem_id) as ac FROM solution WHERE user_id='".$user_mysql."' AND result=4";
    $result=$mysqli->query($sql) or die($mysqli->error);
    $row=$result->fetch_object();
    $AC=$row->ac;
    $result->free();
    
    // count hznuoj submission
    $sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'";
    $result=$mysqli->query($sql) or die($mysqli->error);
    $row=$result->fetch_object();
    $Submit=$row->Submit;
    $result->free();
    
    require_once("../include/rank.inc.php");
    
    // 根据数组计算该实力对应的等级和颜色
    if ($strength > $max_strength) {
        $color = "#6C3365";
        $level = "斗战胜佛";
    } else for ($j=1; $j<$level_total; $j++) {
        
        if ($strength < $level_strength[$j]) {
            $level = $level_name[$j-1];
            $color = $level_color[$j-1];
            break;
        }
    }
    
    // 更新用户信息
    $sql="UPDATE users SET solved=".$AC.",submit=".$Submit.",level='".$level."',strength=".$strength.",color='".$color."' WHERE user_id='".$user_mysql."'";
    $result=$mysqli->query($sql);
    echo $user_mysql.": ".$AC.",".$Submit.",".$strength;
    echo "<br>";
}

echo "update rank successfully!";
?>
