<?php

require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
session_start();
$json = array(); 
if (HAS_PRI("edit_contest")) { 
    $cid = intval($_POST['cid']);
    $content = $mysqli->real_escape_string($_POST['content']);
    $sql = "INSERT INTO contest_broadcast(contest_id, content, date) VALUES ('$cid', '$content', NOW())";

    if ($mysqli->query($sql)) {
        $json['result'] = true;
        $json['msg'] = "send done!";
    } else {
        $json['result'] = false;
        $json['msg'] = "database error!";
    }

    echo json_encode($json);
} else {
    $json['result'] = false;
    $json['msg'] = "your have no privilege!";
}
?>