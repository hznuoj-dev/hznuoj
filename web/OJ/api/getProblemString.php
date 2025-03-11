<?php

require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/const.inc.php");

header('Content-Type: application/json');

if (!isset($_GET['problem_id'])) {
    echo json_encode(array("error" => "No such problem!"));
    exit(0);
}

$pid = strval(intval($_GET['problem_id']));
$sql = "SELECT * FROM `problem` WHERE `problem_id` = '" . $pid . "'";
$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode(array("error" => "Database query failed: " . $mysqli->error));
    exit(0);
}

$row = $result->fetch_object();

$samples = array();
$sample_sql = "SELECT input, output, show_after FROM problem_samples WHERE problem_id='$pid' AND show_after=0 ORDER BY sample_id";
$sample_res = $mysqli->query($sample_sql);
while ($sample_row = $sample_res->fetch_array()) {
    array_push($samples, array(
        "input" => $sample_row['input'],
        "output" => $sample_row['output'],
        "show_after" => $sample_row['show_after'],
    ));
}

if ($row) {
    $description = "题目描述：" . $row->description . "题目输入" . $row->input . "题目输出" . $row->output;

    // Append samples to description
    foreach ($samples as $sample) {
        $description .= " 样例输入：" . $sample['input'] . " 样例输出：" . $sample['output'];
    }

    echo json_encode(array("problem_description" => $description));
} else {
    echo json_encode(array("error" => "No such problem!"));
}

$result->free();
$mysqli->close();

exit(0);
