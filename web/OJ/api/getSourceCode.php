<?php

require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/const.inc.php");

header('Content-Type: application/json');

if (!isset($_GET['solution_id'])) {
    echo json_encode(array("error" => "No such code!"));
    exit(0);
}

$sid = strval(intval($_GET['solution_id']));
$sql = "SELECT `source` FROM `source_code` WHERE `solution_id` = '" . $sid . "'";
$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode(array("error" => "Database query failed: " . $mysqli->error));
    exit(0);
}

$row = $result->fetch_object();

if ($row) {
    echo json_encode(array("source_code" => $row->source));
} else {
    echo json_encode(array("error" => "No source code available!"));
}

$result->free();
$mysqli->close();

exit(0);