<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
session_start();
$json = array(); 
if (HAS_PRI("edit_contest")) { 
    $question_id = intval($_POST['id']);
    $sql = "SELECT reply FROM contest_discuss WHERE id = '$question_id'";
    if($res = $mysqli->query($sql)) {
        $row = $res->fetch_object();
        $json['result'] = true;
        $json['reply'] = $row->reply;
    } else {
        $json['result'] = false;
        $json['msg'] = "database error!";
    }
    echo json_encode($json);
} else {
    $json['result'] = false;
    $json['msg'] = "your have no privilege!";
    echo json_encode($json);
}