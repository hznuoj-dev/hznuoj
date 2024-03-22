<?php $title = "Study Detail"; ?>
<?php
require_once('./include/db_info.inc.php');

require_once("study_detail_data.php");
?>

<?php
$user = $_GET['user'];
if (!is_valid_user_name($user)) {
  echo "No such User!";
  exit(0);
}

$studydata = array();
$studydata = array_fill_keys($studytags, 0);
//studydata['xx']=0;

$sql = "SELECT pt.tag, COUNT(DISTINCT s.problem_id) as count
        FROM solution s
        JOIN problem_tag pt ON s.problem_id = pt.problem_id
        WHERE s.user_id = '$user' AND s.result = 4 AND pt.tag IN ('" . implode("', '", $studytags) . "')
        GROUP BY pt.tag;";
$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
  $studydata[$row['tag']] = $row['count'];
}

$result->free();

///////////////////////////////////////////////////////////////////////////////cs
// if($user == "admin")
//   foreach ($studytags as $tag)
//     $studydata[$tag] = 1;

///////////////////////////////////////////////////////////////////////////////cs

//此处修改层次颜色
$pointcolor = ['#e5e5e5', '#d0f0c0', '#aadf8f', '#85c96e'];

$neednum = array();
$neednum = array_fill_keys($studytags, [0, 1, 2, 4]);
//neednum['xx'] = [0, 1, 2, 4];


foreach ($studytags3 as $tag) {
  $studydata[$tagfa[$tag]] = 0;
  $neednum[$tagfa[$tag]] = [0, 0, 0, 0];
}
foreach ($studytags2 as $tag) {
  $studydata[$tagfa[$tag]] = 0;
  $neednum[$tagfa[$tag]] = [0, 0, 0, 0];
}

foreach ($studytags3 as $tag) {
  $studydata[$tagfa[$tag]] += $studydata[$tag];
  $counts = $neednum[$tag];
  for ($i = 0; $i < count($counts); $i++) {
    $neednum[$tagfa[$tag]][$i] += $counts[$i];
  }
}
foreach ($studytags2 as $tag) {
  $studydata[$tagfa[$tag]] += $studydata[$tag];
  $counts = $neednum[$tag];
  for ($i = 0; $i < count($counts); $i++) {
    $neednum[$tagfa[$tag]][$i] += $counts[$i];
  }
}


$nowcolor = array();

foreach ($studytags as $tag) {
  for ($i = 3; $i >= 0; $i--) {
    if ($studydata[$tag] >= $neednum[$tag][$i]) {
      $nowcolor[$tag] = $pointcolor[$i];
      break;
    }
  }
}


/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/study_detail.php");
/////////////////////////Common foot

?>
