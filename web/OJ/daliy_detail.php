
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


// 教师页面数据



$OJ_CACHE_SHARE = false;
$cache_time = 30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
// require_once('updateRank.php'); // 有此语句后每次点击ranklist会自动更新排名

$view_title = $MSG_RANKLIST;
$filter_url = ""; // URL中的筛选语句
$filter_sql = ""; // SQL中的筛选语句

$scope = "";
if (isset($_GET['scope'])) {
  $scope = $_GET['scope'];
  $filter_url .= "&scope=" . $scope;
}
if ($scope != "" && $scope != 'd' && $scope != 'w' && $scope != 'm')
  $scope = 'y';

$order_by = "";
if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
  $filter_url .= "&order_by=" . $order_by;
}
if ($order_by != "" && $order_by != 'ac')
  $order_by = 's';

$page_size = 200;
$rank = 0;
$start = 0;
$page = 1;
if (isset($_GET['page'])) {
  $page = intval($_GET['page']);
}

$start = $rank = ($page - 1) * $page_size;

if (isset($_GET['class'])) {
  $cls = $mysqli->real_escape_string($_GET['class']);
  if ($_GET['class'] != "all")
    $filter_sql = " WHERE class='" . $cls . "' ";
  $filter_url .= "&class=" . $cls;
}

$filter_url = htmlentities($filter_url);

if (isset($OJ_LANG)) {
  require_once("./lang/$OJ_LANG.php");
}

if ($rank < 0) $rank = 0;

if ($order_by == 'ac') $sql = "SELECT * FROM users " . $filter_sql . "ORDER BY solved DESC, submit, reg_time LIMIT " . strval($rank) . ",$page_size";
else $sql = "SELECT * FROM users " . $filter_sql . " ORDER BY strength DESC, solved, submit, reg_time LIMIT " . strval($rank) . ",$page_size";

if ($scope) {
  $s = "";
  switch ($scope) {
    case 'd':
      $s = date('Y') . '-' . date('m') . '-' . date('d');
      break;
    case 'w':
      $monday = mktime(0, 0, 0, date("m"), date("d") - (date("w") + 7) % 8 + 1, date("Y"));
      //$monday->subDays(date('w'));
      $s = strftime("%Y-%m-%d", $monday);
      break;
    case 'm':
      $s = date('Y') . '-' . date('m') . '-01';;
      break;
    default:
      $s = date('Y') . '-01-01';
  }
  $sql = "SELECT * FROM `users`
                    right join
                    (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc limit " . strval($rank) . ",$page_size) s on users.user_id=s.user_id
                    left join
                    (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc limit " . strval($rank) . "," . ($page_size * 2) . ") t on users.user_id=t.user_id
            " . $class_filter . " ORDER BY solved DESC,t.submit,reg_time  LIMIT  0,200";
}


//         $result = $mysqli->query ( $sql ); //$mysqli->error;
if ($OJ_MEMCACHE) {
  require("./include/memcache.php");
  $result = $mysqli->query_cache($sql); //or die("Error! ".$mysqli->error);
  if ($result) $rows_cnt = count($result);
  else $rows_cnt = 0;
} else {
  $result = $mysqli->query($sql) or die("Error! " . $mysqli->error);
  if ($result) $rows_cnt = $result->num_rows;
  else $rows_cnt = 0;
}

function filter_dates_by_range($user_id, $lastday, $today, $mysqli)
{
  // 获取热力图相关数据
  $sql = "SELECT MIN(judgetime) as actime
          FROM solution
          WHERE user_id = '$user_id' AND result = 4
          GROUP BY problem_id;";
  $daily_result = $mysqli->query($sql);

  $daliy_detail_data = array();
  if ($daily_result->num_rows > 0) {
    // 输出每行数据
    while ($num_rows = $daily_result->fetch_assoc()) {
      $daliy_detail_data[] = $num_rows["actime"];
    }
  }
  $data = array();
  $start_time = strtotime($lastday);
  $end_time = strtotime($today);
  $day_time = 86400;
  for ($time = $start_time; $time <= $end_time; $time += $day_time) {
    $time_str = date('Y-m-d', $time);
    $count = count(array_filter($daliy_detail_data, function ($record) use ($time_str) {
      return strpos($record, $time_str) === 0;
    }));
    $data[] = array($time_str, $count);
  }
  return $data;
}

function getColor($value)
{
  if ($value >= 5) {
    return '#006400';
  } elseif ($value == 4) {
    return '#60b34d';
  } elseif ($value == 3) {
    return '#85c96e';
  } elseif ($value == 2) {
    return '#aadf8f';
  } elseif ($value == 1) {
    return '#d0f0c0';
  } else {
    return '#eeeeee';
  }
}

$view_rank = array();
$i = 0;
for ($i = 0; $i < $rows_cnt; $i++) {
  if ($OJ_MEMCACHE)
    $row = $result[$i];
  else
    $row = $result->fetch_array();

  $user_id = $row['user_id'];
  $filtered_data = filter_dates_by_range($user_id, $lastday, $today, $mysqli);

  $rank++;
  $total = $row['solved'] + $row['ZJU'] + $row['HDU'] + $row['PKU'] + $row['UVA'] + $row['CF'];
  $view_rank[$i][0] = "<div class='am-text-center'>" . $rank . "</div>";
  $view_rank[$i][1] = "<div class='am-text-center'><a href='userinfo.php?user=" . $row['user_id'] . "'>" . $row['user_id'] . "</a>" . "</div>";
  $view_rank[$i][2] = "<div class='am-text-center'>" . htmlentities($row['real_name']) . "</div>";
  $view_rank[$i][3] = "<div class='am-text-center'><a href='status.php?user_id=" . $row['user_id'] . "&jresult=4'>" . $row['solved'] . "</a>" . "</div>";
  $view_rank[$i][4] = "<div class='am-text-center' style='flex-warp:wrap;position:relative;height:24px;'>";

  $previous_month = null;
  foreach ($filtered_data as $record) {
    if ($i == 0) {
      $current_month = date('Y-m', strtotime($record[0]));
      if ($current_month !== $previous_month) {
        $view_rank[$i][4] .= "<span style='position:absolute;top:-32px;transform:translateX(-50%);'>$current_month</span>";
        $previous_month = $current_month;
      }
    }
    $color = getColor($record[1]);
    $view_rank[$i][4] .= "<span style='width:4.5px;height:24px;background-color:$color;display:inline-block;'></span>";
  }
  $view_rank[$i][4] .= "</div>";
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


if (!$OJ_MEMCACHE) $result->free();

$sql = "SELECT count(1) as `mycount` FROM `users` " . $filter_sql;
//        $result = $mysqli->query ( $sql );
if ($OJ_MEMCACHE) {
  // require("./include/memcache.php");
  $result = $mysqli->query_cache($sql); // or die("Error! ".$mysqli->error);
  if ($result) $rows_cnt = count($result);
  else $rows_cnt = 0;
} else {

  $result = $mysqli->query($sql); // or die("Error! ".$mysqli->error);
  if ($result) $rows_cnt = $result->num_rows;
  else $rows_cnt = 0;
}
if ($OJ_MEMCACHE)
  $row = $result[0];
else
  $row = $result->fetch_array();
echo $mysqli->error;

//$row = mysql_fetch_object ( $result );
$view_total = $row['mycount'];
$view_total_page = intval(($view_total + $page_size - 1) / $page_size);

if (!$OJ_MEMCACHE)  $result->free();


/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/daliy_detail.php");
/////////////////////////Common foot

?>
