<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
session_start();
$json = array();
if (HAS_PRI("edit_contest")) {
    $id = intval($_POST['id']);
    $content = $mysqli->real_escape_string($_POST['content']);

    $sql = "UPDATE contest_broadcast SET content='$content', date=NOW() WHERE id='$id'"; 

    if($mysqli->query($sql)) {
        $json['result'] = true;
        $json['msg'] = "update done!";
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