
<?php
require_once('./include/db_info.inc.php');


$current_year = date("Y");

$sql = "SELECT * FROM dailydetails";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();

$lastday = date("Y-m-d", strtotime($row['start_time']));
$today = date("Y-m-d", strtotime($row['end_time']));
?>

<?php
// 学生页面数据
if ($_GET['user']) $user = $_GET['user'];

$sql = "SELECT * FROM `users` WHERE `user_id` = '$user'";
$result = $mysqli->query($sql);
if ($result && $result->num_rows > 0) {
  $udoc = $result->fetch_assoc();
} else {
  echo "No such User!";
  exit(0);
}
define('USER_DETAIL', (!empty($udoc['real_name'])) ? $udoc['real_name'] : $udoc['user_id']);

$user_mysql = $mysqli->real_escape_string($user);

// 获取热力图相关数据
$sql = "SELECT MIN(judgetime) as actime
            FROM solution
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id";
$result = $mysqli->query($sql);

$daily_detail_data = array();
if ($result->num_rows > 0) {
  // 输出每行数据
  while ($row = $result->fetch_assoc()) {
    $daily_detail_data[] = $row["actime"];
  }
}

$daily_detail_data_json = json_encode($daily_detail_data);
$result->free();

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/daily_detail.php");
/////////////////////////Common foot

?>
