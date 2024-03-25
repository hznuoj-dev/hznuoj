
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
if ($_GET['user']) $user = $_GET['user'];
else $user = $_POST['daliy_detail'];
if (!is_valid_user_name($user)) {
  echo "No such User!";
  exit(0);
}
$user_mysql = $mysqli->real_escape_string($user);

// 获取热力图相关数据
$sql = "SELECT MIN(judgetime) as actime
            FROM solution
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id;";
$result = $mysqli->query($sql);

$daliy_detail_data = array();
if ($result->num_rows > 0) {
  // 输出每行数据
  while ($row = $result->fetch_assoc()) {
    $daliy_detail_data[] = $row["actime"];
  }
}

$daliy_detail_data_json = json_encode($daliy_detail_data);
$result->free();



/////////////////////////Template
require("template/".$OJ_TEMPLATE."/daliy_detail.php");
/////////////////////////Common foot

?>
