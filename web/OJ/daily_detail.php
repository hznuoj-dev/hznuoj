
<?php
require_once('./include/db_info.inc.php');


$current_year = date("Y");

$sql = "SELECT * FROM more_settings";
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

// 通过题目按照分数难度分类
$score_ranges = ['0~10', '10~20', '20~40', '40~60', '60~70', '70~80', '80~90', '90~99', '99~100'];
$color_ranges = ['#aaaaaa', '#a5fc93', '#9bdec4', '#b2b2f9', '#f19af9', '#f8d39c', '#f6c476', '#f08b88', '#ed5a52'];
$score_data = array();

foreach ($score_ranges as $index => $range) {
  list($min_score, $max_score) = explode('~', $range);
  $sql = "SELECT COUNT(DISTINCT problem_id) as count
          FROM (
            SELECT s.problem_id, MIN(s.judgetime), p.score as score
            FROM solution s
            JOIN problem p ON s.problem_id = p.problem_id
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id
          ) as first_ac_problem
          WHERE score > $min_score AND score <= $max_score";
  $result = $mysqli->query($sql);
  $count = 0;
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
  }
  $result->free();
  $score_data[] = array(
    'value' => (int)$count,
    'itemStyle' => array(
      'color' => $color_ranges[$index]
    )
  );
}
$score_ranges_json = json_encode($score_ranges);
$score_data_json = json_encode($score_data);


// 获取通过题目数量的函数
function get_passed_problems_count($user_mysql, $interval = null)
{
  global $mysqli;
  $interval_condition = $interval ? "AND first_ac_time >= DATE_SUB(NOW(), INTERVAL $interval)" : "";
  $sql = "SELECT COUNT(DISTINCT problem_id) as problems_count
          FROM (
            SELECT problem_id, MIN(judgetime) as first_ac_time
            FROM solution
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id
          ) as first_ac
          WHERE 1=1 $interval_condition";
  $result = $mysqli->query($sql);
  $problems_count = 0;
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $problems_count = $row['problems_count'];
  }
  $result->free();
  return $problems_count;
}

$total_problems = get_passed_problems_count($user_mysql);
$month_problems = get_passed_problems_count($user_mysql, '1 MONTH');
$week_problems = get_passed_problems_count($user_mysql, '1 WEEK');

// 获取连续做题天数
function calculate_max_streak($user_mysql, $interval = null)
{
  global $mysqli;
  $interval_condition = $interval ? "AND first_ac_time >= DATE_SUB(NOW(), INTERVAL $interval)" : "";
  $sql = "SELECT first_ac_time as judgetime
          FROM (
            SELECT problem_id, MIN(judgetime) as first_ac_time
            FROM solution
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id
          ) as first_ac
          WHERE 1=1 $interval_condition
          ORDER BY first_ac_time ASC";
  $result = $mysqli->query($sql);

  $max_streak = 0;
  $current_streak = 0;
  $last_date = null;

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $current_date = date("Y-m-d", strtotime($row['judgetime']));
      if ($last_date) {
        $diff = (strtotime($current_date) - strtotime($last_date)) / (60 * 60 * 24);
        if ($diff == 1) {
          $current_streak++;
        } else {
          $current_streak = 1;
        }
      } else {
        $current_streak = 1;
      }
      $last_date = $current_date;
      if ($current_streak > $max_streak) {
        $max_streak = $current_streak;
      }
    }
  }
  $result->free();
  return $max_streak;
}

$max_streak = calculate_max_streak($user_mysql);
$max_streak_month = calculate_max_streak($user_mysql, '1 MONTH');
$max_streak_week = calculate_max_streak($user_mysql, '1 WEEK');

function calculate_ac_rate($user_mysql)
{
  global $mysqli;
  // AC数量
  $sql = "SELECT COUNT(*) as ac_count FROM solution WHERE result=4 AND user_id='$user_mysql'";
  $result = $mysqli->query($sql);
  $ac_count = 0;
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ac_count = $row['ac_count'];
  }
  $result->free();

  // 总提交数量
  $sql = "SELECT COUNT(*) as total_submissions FROM solution WHERE user_id='$user_mysql'";
  $result = $mysqli->query($sql);
  $total_submissions = 0;
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_submissions = $row['total_submissions'];
  }
  $result->free();

  if ($total_submissions == 0) {
    return 0;
  }
  return ($ac_count / $total_submissions) * 100;
}

$ac_rate = calculate_ac_rate($user_mysql);


// 筛选出当前用户所在班级的所有人，计算出它们的平均数据
$sql = "SELECT class FROM `users` WHERE `user_id` = '$user'";
$result = $mysqli->query($sql);
$class = '';
if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $class = $row['class'];
}
$result->free();

// 计算班级平均数据
if ($class) {
  $sql2 = "SELECT user_id FROM `users` WHERE class = '$class' LIMIT 30";
  $result2 = $mysqli->query($sql2);
  $count_users = 0;
  $sum_total_problems = 0;
  $sum_month_problems = 0;
  $sum_week_problems = 0;
  $sum_max_streak = 0;
  $sum_max_streak_month = 0;
  $sum_max_streak_week = 0;
  $sum_ac_rate = 0;

  while ($row2 = $result2->fetch_assoc()) {
    $count_users++;
    $class_user_id = $row2['user_id'];

    $sum_total_problems += get_passed_problems_count($class_user_id);
    $sum_month_problems += get_passed_problems_count($class_user_id, '1 MONTH');
    $sum_week_problems += get_passed_problems_count($class_user_id, '1 WEEK');
    $sum_max_streak += calculate_max_streak($class_user_id);
    $sum_max_streak_month += calculate_max_streak($class_user_id, '1 MONTH');
    $sum_max_streak_week += calculate_max_streak($class_user_id, '1 WEEK');
    $sum_ac_rate += calculate_ac_rate($class_user_id);
  }
  $result2->free();

  $avg_total_problems = $sum_total_problems / $count_users;
  $avg_month_problems = $sum_month_problems / $count_users;
  $avg_week_problems = $sum_week_problems / $count_users;
  $avg_max_streak = $sum_max_streak / $count_users;
  $avg_max_streak_month = $sum_max_streak_month / $count_users;
  $avg_max_streak_week = $sum_max_streak_week / $count_users;
  $avg_ac_rate = $sum_ac_rate / $count_users;
}

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/daily_detail.php");
/////////////////////////Common foot

?>
