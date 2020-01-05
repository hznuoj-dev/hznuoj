<?php
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
$contest_id = 3;

$sql="SELECT password, is_end FROM formal_contest WHERE id = $contest_id";
$res = $mysqli->query($sql)->fetch_array();

$is_end = $res['is_end'];
$contest_password = $res['password']; 


$has_login = isset($_SESSION['user_id']);

$team_name = "";
$school = ""; 
$name = array(); 
$stu_id = array();
$phone = array();
$email = array(); 
for ($i = 0; $i < 3; ++$i) {
	$name[$i] = "";
	$stu_id[$i] = "";
	$phone[$i] = "";
	$email[$i] = "";
}
$anonymous = 0;
if ($has_login) {
	$user_id = $_SESSION['user_id'];   
	$sql = "SELECT stu_id, name, phone, email, team_name, school, anonymous
			FROM formal_contest_team 
			LEFT JOIN formal_contest_tmlist on formal_contest_team.team_id = formal_contest_tmlist.team_id 
			LEFT JOIN formal_contest_member on formal_contest_tmlist.member_id = formal_contest_member.member_id
			WHERE user_id = '$user_id' AND contest_id = '$contest_id'
			ORDER BY formal_contest_tmlist.id";
	$res = $mysqli->query($sql);
	$i = 0; 
	while ($form_data = $res->fetch_array()) {
		$has_register = 1;
		$team_name = $form_data['team_name'];   
		$school = $form_data['school'];
		$anonymous = $form_data['anonymous'];
		$stu_id[$i] = $form_data['stu_id'];
		$name[$i] = $form_data['name'];
		$phone[$i] = $form_data['phone'];
		$email[$i] = $form_data['email']; 
		++$i;
	}
}
?>