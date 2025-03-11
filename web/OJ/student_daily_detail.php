
<?php
require_once('./include/db_info.inc.php');
require_once('./include/cache_start.php');
require_once('./include/classList.inc.php');

$sql = "SELECT * FROM more_settings";
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
// 可以set_more_settings就可以看到这个页面
if (!HAS_PRI("set_more_settings")) {
  $view_errors = "<a href=loginpage.php style='color:red;text-decoration:underline;'>$MSG_Login</a>";
  require("template/" . $OJ_TEMPLATE . "/error.php");
  exit(0);
}

// 筛选所有课程组
$sql = "SELECT DISTINCT * FROM course_team";
$result = $mysqli->query($sql);
$course_team_list = array();
while ($row = $result->fetch_array()) {
  $course_team_list[] = array('team_name' => $row['term'] . '-' . $row['course_name'] . '-' . $row['teacher_name'] . '-' . $row['class_week_time'], 'team_id' => $row['team_id']);
}
$result->free();

// 教师页面数据

$OJ_CACHE_SHARE = false;
$cache_time = 30;

$filter_sql = "";
$where_conditions = [];

$order_by = "user_id, reg_time";
if (isset($_GET['order_by'])) {
  $first_order_by = $_GET['order_by'];
  if ($first_order_by !== 'user_id') {
    $order_by = $first_order_by . " DESC, " . $order_by;
  }
}

$page_size = 100;
$rank = 0;

// 筛选课程组(必选)
if (isset($_GET['course_team'])) {
  $team_id = intval($_GET['course_team']);
  $filter_sql = "JOIN course_team_relation ctr ON users.user_id = ctr.user_id";
  $where_conditions[] = "ctr.team_id = $team_id";
} else {
  $page_size = 0;
}

// 筛选班级
if (isset($_GET['class']) && $_GET['class'] !== 'all') {
  $class = $mysqli->real_escape_string($_GET['class']);
  $where_conditions[] = "class = '$class'";
}

// 合成WHERE子句
$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

if ($rank < 0) $rank = 0;

// Use indexed columns in SELECT and add FORCE INDEX hint if needed
$sql = "SELECT SQL_CALC_FOUND_ROWS users.user_id, users.real_name, users.solved, users.reg_time 
        FROM users 
        $filter_sql 
        $where_clause 
        ORDER BY $order_by 
        LIMIT ?, ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $rank, $page_size);
$stmt->execute();
$result = $stmt->get_result();
$rows_cnt = $result->num_rows;

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
  $view_rank[$i][4] = "<div class='am-text-center' style='dispaly:flex;height:24px;flex-warp:wrap;position:relative;'>";

  $previous_month = null;
  foreach ($filtered_data as $record) {
    // 日期
    if ($i == 0) {
      $current_month = date('Y-m', strtotime($record[0]));
      if ($current_month !== $previous_month) {
        $view_rank[$i][4] .= "<span style='position:absolute;top:-32px;transform:translateX(-50%);white-space:nowrap;'>$current_month</span>";
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

$result->free();

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/student_daily_detail.php");
/////////////////////////Common foot

?>
