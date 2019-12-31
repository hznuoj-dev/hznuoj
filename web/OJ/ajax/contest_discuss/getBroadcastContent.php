<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
session_start();
$json = array(); 
if (HAS_PRI("edit_contest")) { 
    $id = intval($_POST['id']); 
    $sql = "SELECT content FROM contest_broadcast WHERE id = '$id'";
    if($res = $mysqli->query($sql)) {
        $row = $res->fetch_object();
        $json['result'] = true;
        $json['content'] = $row->content;
    } else {
        $json['result'] = false;
        $json['msg'] = "database error!";
    }
    echo json_encode($json);
} else {
    $json['result'] = false;
    $json['msg'] = "your have no privilege!";
}