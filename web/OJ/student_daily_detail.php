
<?php
require_once('./include/db_info.inc.php');

$sql = "SELECT * FROM dailydetails";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();
$start_date_db = date("Y-m-d", strtotime($row['start_time']));
$end_date_db = date("Y-m-d", strtotime($row['end_time']));

if (
  isset($_GET['start_date']) && !empty($_GET['start_date']) &&
  isset($_GET['end_date']) && !empty($_GET['end_date'])
) {
  $start_date = $_GET['start_date'];
  $end_date = $_GET['end_date'];
} else {
  $start_date = $start_date_db;
  $end_date = $end_date_db;
}
?>

<?php

if (!HAS_PRI("set_dailydetails")) {
  $view_errors = "<a href=loginpage.php style='color:red;text-decoration:underline;'>$MSG_Login</a>";
  require("template/" . $OJ_TEMPLATE . "/error.php");
  exit(0);
}

// 教师页面数据

$OJ_CACHE_SHARE = false;
$cache_time = 30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');

$filter_sql = "";

$order_by = "submit, reg_time";
if (isset($_GET['order_by'])) {
  $first_order_by = $_GET['order_by'];
  if ($order_by == 'user_id') $order_by = $first_order_by . ", strength DESC, " . $order_by;
  else $order_by = $first_order_by." DESC , " . $order_by;
}

$page_size = 100;
$rank = 0;

if (isset($_GET['class'])) {
  $cls = $mysqli->real_escape_string($_GET['class']);
  if ($_GET['class'] != "all")
    $filter_sql = " WHERE class='" . $cls . "' ";
}

if (isset($OJ_LANG)) {
  require_once("./lang/$OJ_LANG.php");
}

if ($rank < 0) $rank = 0;

$sql = "SELECT * FROM users " . $filter_sql . " ORDER BY " . $order_by . " LIMIT " . strval($rank) . ",$page_size";

$result = $mysqli->query($sql) or die("Error! " . $mysqli->error);
if ($result) $rows_cnt = $result->num_rows;
else $rows_cnt = 0;

// 筛选当前所有用户每日的做题情况
function filter_dates_by_range_for_users($user_ids, $start_date, $end_date, $mysqli)
{
  if (empty($user_ids)) {
    return [];
  }
  $user_id_list = implode(',', array_map('intval', $user_ids)); // 将用户ID数组转换为逗号分隔的字符串

  // 获取所有用户热力图相关数据的SQL查询
  $sql = "SELECT user_id, MIN(judgetime) as actime
          FROM solution
          WHERE user_id IN ($user_id_list) AND result = 4
          GROUP BY user_id, problem_id;";
  $daily_result = $mysqli->query($sql);

  $daily_detail_data = [];
  if ($daily_result && $daily_result->num_rows > 0) {
    // 将结果组织成以user_id为键的数组
    while ($num_rows = $daily_result->fetch_assoc()) {
      $user_id = $num_rows['user_id'];
      if (!isset($daily_detail_data[$user_id])) {
        $daily_detail_data[$user_id] = [];
      }
      $daily_detail_data[$user_id][] = $num_rows['actime'];
    }
  }

  // 为每个用户处理日期和计数数据
  $final_data = [];
  foreach ($user_ids as $user_id) {
    $data = [];
    $start_time = strtotime($start_date);
    $end_time = strtotime($end_date);
    $day_time = 86400;
    for ($time = $start_time; $time <= $end_time; $time += $day_time) {
      $time_str = date('Y-m-d', $time);
      $count = count(array_filter($daily_detail_data[$user_id] ?? [], function ($record) use ($time_str) {
        return strpos($record, $time_str) === 0;
      }));
      $data[] = [$time_str, $count];
    }
    $final_data[$user_id] = $data;
  }

  return $final_data;
}
// 获取两个日期之间的天数
function getDays($start_date, $end_date)
{
  $start_time = strtotime($start_date);
  $end_time = strtotime($end_date);
  $day_time = 86400;
  $days = 0;
  for ($time = $start_time; $time <= $end_time; $time += $day_time) {
    $days++;
  }
  return $days;
}
// 获取颜色
function getColor($value)
{
  if ($value >= 5) {
    return '#006400';
  }
  switch ($value) {
    case 4:
      return '#60b34d';
    case 3:
      return '#85c96e';
    case 2:
      return '#aadf8f';
    case 1:
      return '#d0f0c0';
    default:
      return '#eeeeee';
  }
}

$user_ids = [];
$user_data = [];
$view_rank = [];
$i = 0;
for ($i = 0; $i < $rows_cnt; $i++) {
  $row = $result->fetch_array();
  $user_ids[] = $row['user_id'];
  $user_data[$row['user_id']] = $row;
}

// 一次性为所有用户获取数据
$filtered_data_for_users = filter_dates_by_range_for_users($user_ids, $start_date, $end_date, $mysqli);
$alldays = getDays($start_date, $end_date);

$i = 0;
foreach ($user_ids as $user_id) {
  $row = $user_data[$user_id];
  $filtered_data = $filtered_data_for_users[$user_id] ?? [];

  $rank++;
  $view_rank[$i][0] = "<div class='am-text-center'>" . $rank . "</div>";
  $view_rank[$i][1] = "<div class='am-text-center'><a href='userinfo.php?user=" . $row['user_id'] . "'>" . $row['user_id'] . "</a>" . "</div>";
  $view_rank[$i][2] = "<div class='am-text-center'>" . htmlentities($row['real_name']) . "</div>";
  $view_rank[$i][3] = "<div class='am-text-center'><a href='status.php?user_id=" . $row['user_id'] . "&jresult=4'>" . $row['solved'] . "</a>" . "</div>";
  $view_rank[$i][4] = "<div class='am-text-center' style='dispaly:flex;flex-warp:wrap;position:relative;'>";

  $previous_month = null;
  foreach ($filtered_data as $record) {
    // 日期
    if ($i == 0) {
      $current_month = date('Y-m', strtotime($record[0]));
      if ($current_month !== $previous_month) {
        $view_rank[$i][4] .= "<span style='position:absolute;top:-32px;transform:translateX(-50%);'>$current_month</span>";
        $previous_month = $current_month;
      }
    }
    $color = getColor($record[1]);
    $width = 55 / $alldays;
    $view_rank[$i][4] .= "<span style='width:{$width}vw;height:24px;background-color:$color;display:inline-block;'></span>";
  }
  $view_rank[$i][4] .= "</div>";
  $i++;
}

/* 获取所有班级 start */
$sql_class = "SELECT DISTINCT(class) FROM users";
$result_class = $mysqli->query($sql_class);
$classSet = array();
while ($row_class = $result_class->fetch_array()) {
  $class = $row_class['class'];
  //    echo $class."<br />";
  if (!is_null($class) && $class != "" && $class != "null" && $class != "其它") {
    $grade = "";
    $strlen = strlen($class);
    for ($i = 0; $i < $strlen; ++$i) {
      if (is_numeric($class[$i])) {
        $grade = $class[$i] . $class[$i + 1];
        break;
      }
    }
    $classSet[] = $grade . " - " . $class;
    //echo $grade." - ".$class."<br />";
  }
}
rsort($classSet);
$result_class->free();
/* 获取所有班级 end */

$result->free();

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/student_daily_detail.php");
/////////////////////////Common foot

?>
