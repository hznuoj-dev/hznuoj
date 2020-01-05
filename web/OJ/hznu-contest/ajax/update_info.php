<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/config.php";
if($is_end) {
    echo "报名已结束";
    exit(0);
}

$ok = true;
$return_msg = "";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $team_name = $mysqli->real_escape_string($_POST['team_name']);
    $school = $mysqli->real_escape_string($_POST['school']);
    $name = array();
    $stu_id = array();
    $phone = array();
    $email = array(); 
    for ($i = 0; $i < 3; ++$i) {
        $name[$i] = $mysqli->real_escape_string($_POST['name'][$i]);
        $stu_id[$i] = $mysqli->real_escape_string($_POST['stu_id'][$i]);
        $phone[$i] = $mysqli->real_escape_string($_POST['phone'][$i]);
        $email[$i] = $mysqli->real_escape_string($_POST['email'][$i]);
    } 
    $anonymous = intval($_POST['anonymous']);  
    // var_dump($name, $stu_id, $phone, $email); 

    if ($name[0] == "") {
        echo "队员一的姓名不能为空！";
        exit(0);
    } else if ($phone[0] == "") {
        echo "队员一的联系电话不能为空！";
        exit(0);
    } else if ($email[0] == "") {
        echo "队员一的邮箱不能为空！";
        exit(0);
    }

    $sql = "SELECT team_id, COUNT(*) as C from formal_contest_team WHERE user_id='$user_id' AND contest_id=$contest_id";   
    $res = $mysqli->query($sql)->fetch_array();
    $has_record = $res['C'];
    $team_id = 0;
    $member_id = array();
    if (!$has_record) {
        $sql = "INSERT INTO formal_contest_team 
                (contest_id, user_id, team_name, school, anonymous, register_time) 
                VALUES ('$contest_id', '$user_id', '$team_name', '$school', '$anonymous', NOW())";
        $ok &= $mysqli->query($sql);
        if (!$ok) {
            echo "信息更新失败，请重试！";
            exit(0);
        }
        $team_id = $mysqli->insert_id;
        for ($i = 0; $i < 3; ++$i) {  
            $sql = "INSERT INTO formal_contest_member (stu_id, name, phone, email) 
                    VALUES ('$stu_id[$i]', '$name[$i]', '$phone[$i]', '$email[$i]')";
            $ok &= $mysqli->query($sql);
            if (!$ok) {
                echo "信息更新失败，请重试！";
                exit(0);
            }
            $m_id = $mysqli->insert_id;
            $sql = "INSERT INTO formal_contest_tmlist (team_id, member_id) 
                    VALUES ('$team_id', '$m_id')";
            $ok &= $mysqli->query($sql);
            if (!$ok) {
                echo "信息更新失败，请重试！";
                exit(0);
            }
        }
    }
    else {
        $team_id = $res['team_id'];
        $sql = "SELECT member_id FROM formal_contest_tmlist WHERE team_id = '$team_id' ORDER BY member_id";
        $res = $mysqli->query($sql); 
        $i = 0;
        while ($form_data = $res->fetch_array()) {
            $member_id[$i] = $form_data['member_id'];
            ++$i;
        }
        $sql = "UPDATE formal_contest_team SET 
                team_name = '$team_name', school = '$school', anonymous = '$anonymous'
                WHERE team_id = '$team_id'";
        $ok &= $mysqli->query($sql);
        if (!$ok) {
            echo "信息更新失败，请重试！";
            exit(0);
        }
        for ($i = 0; $i < 3; ++$i) {
            $sql = "UPDATE formal_contest_member SET 
            stu_id = '$stu_id[$i]', name = '$name[$i]', phone = '$phone[$i]', email = '$email[$i]'
            WHERE member_id = '$member_id[$i]'";
            $ok &= $mysqli->query($sql);
            if (!$ok) {
                echo "信息更新失败，请重试！";
                exit(0);
            }
        }
    }

    //echo "$sql";
    if($ok){
        $return_msg = "信息更新成功!"; 
    }
    else{
        $return_msg = $mysqli->error;
        //$return_msg = "信息更新失败! 请检查信息是否填写有误!";
    }
}
else {
    $return_msg = "请先登录！";
}

echo $return_msg;
?>