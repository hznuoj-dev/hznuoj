<?php
/**
 * This file is modified
 * by yybird
 * @2016.05.24
 **/
?>

<?php

$OJ_CACHE_SHARE = !isset($_GET['cid']);
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once('./include/const.inc.php');

function formatTimeLength($length) {
    $result = "";
    $day = floor($length/86400); $length%=86400;
    $hour = floor($length/3600); $length%=3600;
    $minute = floor($length/60); $length%=60;
    $second = $length;
    if ($day > 0) {
        $result .= $day.":";
    }
    $result .= sprintf("%02d", $hour).":";
    $result .= sprintf("%02d", $minute);
    // $result .= $day." Day".($day>1?"s":"")." ";
    // $result .= $hour." Hour".($hour>1?"s":"")." ";
    // $result .= $minute." Minute".($minute>1?"s":"")." ";
    // $result .= $second." Second".($second>1?"s":"")." ";
    return $result;
}

/* 获取当前页数 start */
$page_cnt = 50;
$page="1";
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}
/* 获取当前页数 start */

$search = "";
if(isset($_GET['search'])) {
    $search = $mysqli->real_escape_string($_GET['search']);
}

$sql = <<<SQL
        SELECT * FROM `contest`
        WHERE `defunct` = 'N' 
        AND title LIKE '%$search%'
        ORDER BY `contest_id` DESC
SQL;

$res = $mysqli->query($sql);
$total = $res->num_rows;
$view_total_page = intval(($total + $page_cnt - 1) / $page_cnt);
$start = ($page - 1) * $page_cnt;
$sql .= " LIMIT $start, $page_cnt";


$result = $mysqli->query($sql);
$view_contest = Array();
$i = 0;
while ($row = $result->fetch_object()) {
    $view_contest[$i][0]= $row->contest_id;
    $view_contest[$i][1]= "<a href='contest.php?cid=$row->contest_id'>$row->title</a>";
    $start_time=strtotime($row->start_time);
    $end_time=strtotime($row->end_time);
    $now=time();
    $length=$end_time - $start_time;
    $left=$end_time-$now;
    
    $view_contest[$i][2] = $row->start_time;
    $view_contest[$i][3] = $row->end_time;

    if ($now > $end_time) { // past
        // $view_contest[$i][4]= "<span style='color: #9e9e9e;'>$MSG_Ended@$row->end_time</span>";
        $view_contest[$i][4] = "<span style='color: #9e9e9e;'>$MSG_Ended</span>";
    } else if ($now < $start_time){ // pending
        // $view_contest[$i][4]= "<span style='color: #03a9f4;'>$MSG_Start@$row->start_time&nbsp;";
        // $view_contest[$i][4].= "$MSG_TotalTime ".formatTimeLength($length)."</span>";
        $view_contest[$i][4] = "<span style='color: #03a9f4;'>$MSG_Start";
    } else { // running
        // $view_contest[$i][4]= "<span style='color: #ff5722;'> $MSG_Running&nbsp;";
        // $view_contest[$i][4].= "$MSG_LeftTime ".formatTimeLength($left)." </span>";
        $view_contest[$i][4] = "<span style='color: #ff5722;'> $MSG_Running";
    }

    $type = "<span style='color: green;'>Public</span>";
    if($row->private) $type = "<span style='color: dodgerblue;'>Password</span>";
    if($row->user_limit=="Y") $type = "<span style='color: #f44336;'>Special</span>";
    if($row->practice) $type = "<span style='color: #009688;'>Practice</span>";
    
    $view_contest[$i][5] = $type;
    $i++;
}
$result->free();



/////////////////////////Template
require("template/".$OJ_TEMPLATE."/contestset.php");
/////////////////////////Common foot

if(file_exists('./include/cache_end.php')) {
    require_once('./include/cache_end.php');
}

?>
