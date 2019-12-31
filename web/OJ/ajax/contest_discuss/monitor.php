<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/static.php";  

session_start();
$json = array();
if ($OJ_MONITOR) {
    $cid = $mysqli->real_escape_string($_POST['cid']);
    $user_id = $mysqli->real_escape_string($_POST['user_id']); 
    if (!isset($_SESSION['broadcast_id'])) {
        $sql = "SELECT max(id) as Max FROM contest_broadcast WHERE contest_id = '$cid'";
        if ($res = $mysqli->query($sql)) {
            $row = $res->fetch_object();
            $_SESSION['broadcast_id'] = intval($row->Max);         
        } else {
            $_SESSION['broadcast_id'] = 0;  
        }
    } 
    if (!isset($_SESSION['discuss_id'])) {
        $sql = "SELECT max(id) as Max FROM contest_discuss WHERE contest_id = '$cid'";
        if ($res = $mysqli->query($sql)) {
            $row = $res->fetch_object();
            $_SESSION['discuss_id'] = intval($row->Max);         
        } else {
            $_SESSION['discuss_id'] = 0;      
        }
    }

    // echo $_SESSION['broadcast_id']."\n";
    // echo $_SESSION['discuss_id']."\n"; 

    $json['result'] = false;

    $broadcast_id = intval($_SESSION['broadcast_id']);
    $discuss_id = intval($_SESSION['discuss_id']);

    $sql = "SELECT max(id) as Max FROM contest_broadcast 
            WHERE contest_id = '$cid' 
            AND id > $broadcast_id"; 
    
    if ($res = $mysqli->query($sql)) {
        $row = $res->fetch_object();
        if (intval($row->Max) > $broadcast_id) {
            $_SESSION['broadcast_id'] = intval($row->Max);
            $json['result'] = true; 
            $json['msg'] = "有新的广播消息，请前往Clarifications查看！"; 
        }
    }

    $sql = "SELECT max(id) as Max FROM contest_discuss 
            WHERE contest_id = '$cid' 
            AND Reply IS NOT NULL
            AND id > $discuss_id
            AND user_id = '$user_id'"; 
    
    if ($res = $mysqli->query($sql)) {
        $row = $res->fetch_object();
        if (intval($row->Max) > $discuss_id) { 
            $_SESSION['discuss_id'] = intval($row->Max); 
            $json['result'] = true;
            if (!isset($json['msg'])) {
                $json['msg'] = "收到新的回复，请前往Clarifications查看！"; 
            }
        }
    }
    echo json_encode($json); 
} else {
    $json['result'] = false;
    echo json_encode($json);     
}
?>