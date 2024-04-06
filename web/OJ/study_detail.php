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
//统计里程碑非叶子节点的需要做题的数量
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


$studycolor = array();
//统计里程碑节点颜色
foreach ($studytags as $tag) {
  $ancestor = $tag;
  while (isset($tagfa[$ancestor])) {
    $ancestor = $tagfa[$ancestor];
  }
  //找到祖先节点，使用对应颜色类型、深浅
  $index = array_search($ancestor, $studytags1);
  for ($i = 3; $i >= 0; $i--) {
    if ($studydata[$tag] >= $neednum[$tag][$i]) {
      $studycolor[$tag] = $pointcolor[$index][$i];
      break;
    }
  }
}

// SELECT
//     SUM(CASE WHEN p.score >= 0 AND p.score < 12 THEN 1 ELSE 0 END) as range0,
//     SUM(CASE WHEN p.score >= 12 AND p.score < 25 THEN 1 ELSE 0 END) as range1,
//     SUM(CASE WHEN p.score >= 25 AND p.score < 40 THEN 1 ELSE 0 END) as range2,
//     SUM(CASE WHEN p.score >= 40 AND p.score < 60 THEN 1 ELSE 0 END) as range3,
//     SUM(CASE WHEN p.score >= 60 AND p.score < 70 THEN 1 ELSE 0 END) as range4,
//     SUM(CASE WHEN p.score >= 70 AND p.score < 80 THEN 1 ELSE 0 END) as range5,
//     SUM(CASE WHEN p.score >= 80 AND p.score < 90 THEN 1 ELSE 0 END) as range6,
//     SUM(CASE WHEN p.score >= 90 AND p.score <= 100 THEN 1 ELSE 0 END) as range7
//     FROM
//     (SELECT DISTINCT s.problem_id, p.score
//     FROM solution s
//     INNER JOIN problem p ON s.problem_id = p.problem_id
//     WHERE s.user_id = '' AND s.result = 4) as p;

// SELECT
//     SUM(CASE WHEN p.score >= 0 AND p.score < 11 THEN 1 ELSE 0 END) as range0,
//     SUM(CASE WHEN p.score >= 11 AND p.score < 20 THEN 1 ELSE 0 END) as range1,
//     SUM(CASE WHEN p.score >= 20 AND p.score < 30 THEN 1 ELSE 0 END) as range2,
//     SUM(CASE WHEN p.score >= 30 AND p.score < 40 THEN 1 ELSE 0 END) as range3,
//     SUM(CASE WHEN p.score >= 50 AND p.score < 65 THEN 1 ELSE 0 END) as range4,
//     SUM(CASE WHEN p.score >= 65 AND p.score < 80 THEN 1 ELSE 0 END) as range5,
//     SUM(CASE WHEN p.score >= 80 AND p.score < 90 THEN 1 ELSE 0 END) as range6,
//     SUM(CASE WHEN p.score >= 90 AND p.score < 100 THEN 1 ELSE 0 END) as range7
// FROM
//     (SELECT DISTINCT s.problem_id, p.score
//     FROM solution s
//     INNER JOIN problem p ON s.problem_id = p.problem_id
//     INNER JOIN problem_tag pt ON s.problem_id = pt.problem_id
//     WHERE s.user_id = '' AND s.result = 4 AND pt.tag IS NOT NULL) as p;

$sql = "SELECT ";
for ($i = 0; $i < 8; $i++) {
  $nowpoint = $abilitypoint[$abilities[$i]];
  $sql .= "SUM(CASE WHEN p.score >= {$nowpoint['scorel']} AND p.score < {$nowpoint['scorer']} THEN 1 ELSE 0 END) as range{$i} ";
  if ($i != 7) {
    $sql .= ",";
  }
}
$sql .= "FROM (
  SELECT DISTINCT s.problem_id, p.score FROM solution s
  INNER JOIN problem p ON s.problem_id = p.problem_id
  INNER JOIN problem_tag pt ON s.problem_id = pt.problem_id
  WHERE s.user_id = '$user' AND s.result = 4 AND pt.tag IS NOT NULL
  ) as p;";

// Execute the SQL query
$result = $mysqli->query($sql);

$abilitycolor = array();
$okabilitynum = array();

$flag = 1; //点亮签名才能点亮后面
while ($row = $result->fetch_assoc()) {
  for ($i = 0; $i < 8; $i++) {
    $nowpoint = $abilitypoint[$abilities[$i]];
    $okabilitynum[$abilities[$i]] = $row['range' . $i];
    if ($row['range' . $i] > $nowpoint['need'] && $flag == 1) {
      $abilitycolor[$abilities[$i]] = $pointcolor[0][2];
    } else {
      $abilitycolor[$abilities[$i]] = $pointcolor[0][0];
      $flag = 0;
    }
  }
}

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/study_detail.php");
/////////////////////////Common foot

?>
